<?php 
session_start();
$errors=[];
$errorMessage='';

if(isset($_POST["login"])){
    $connection = mysqli_connect('localhost', 'root', 'pass', 'users');

    if(empty($_POST["username"]) or empty($_POST["password"])){
        $errors[]="Please enter both your username and password.";        
    }
    
    if(!empty($_POST["username"]) and !empty($_POST["password"])){
        
        $username=$_POST["username"];
        $password=$_POST["password"];
        $userip=$_SERVER['REMOTE_ADDR'];
    
        $query=mysqli_query($connection,"SELECT * FROM google_auth WHERE username='$username' AND password='$password' AND userip='$userip'");
        $nrows=mysqli_num_rows($query);
        if($nrows>0){
            $row=mysqli_fetch_array($query);
            $_SESSION["email"]=$row["email"];
            $_SESSION["secret"]=$row["secret"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["subscription"]=$row["subscription"];
            
        
            header("Location:device_confirmations.php");
        }else{
            $query=mysqli_query($connection,"SELECT * FROM google_auth WHERE username='$username' AND password='$password'");
            $nrows=mysqli_num_rows($query);
            if($nrows==0){
                $errors[]="Please create an account by clicking <b>Register</b> from the top menu or if you already did so, click on forgot account.";
                #header("Location:register.php");
            }else{
                $errors[]="For security reasons, please login from the same place where you created you account. If you changed homes, please create a new account";
            }
        }   
    }
}

if(isset($_POST["forgot"])){
    #$query=mysqli_query("DELETE FROM gogole_auth WHERE userip='$userip'");
    header("Location:forgot_account.php");
}

if(!empty($errors)){
    $allErrors = join('<br/>', $errors);
    $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="contact_style.css">
    </head>
    
    <body>
        <section class="header">
            <nav>
                <a href="index.php"><img src="images/logo.jpg"></a>
                <div class="nav-links" id="navLinks">
                    <i class="fa-solid fa-xmark" onclick="hideMenu()"></i>
                    <ul>
                        <li><a href="index.php" style="color: deepskyblue">Home</a></li>
                        <li><a href="porn.html" style="color: deepskyblue">Porn</a></li>
                        <li><a href="formula.php" style="color: deepskyblue">Formula</a></li>
                        <li><a href="moto.html" style="color: deepskyblue">Moto</a></li>
                        <li><a href="" style="color: deepskyblue">Top Gear</a></li>
                        <li><a href="donate.html" style="color: deepskyblue">Donate</a></li>
                        <li><a href="contact.php" style="color: deepskyblue">Contact</a></li>
                    </ul>
                </div>
                <i class="fa-solid fa-bars" onclick="showMenu()"></i>
            </nav>
            <div class="text-box">
                    <h2>Login.</h2>
                    <p>Please scroll to the bottom of the page to get the Google Authenticator app on your phone.
                    For security reasons, you will always use Google Authenticator to log in.</p>
                <div class="contact-form">
                    <form id="contact-form" method="POST" action="login.php">
                        <?php echo((!empty($errorMessage)) ? $errorMessage : '') ?> 
                        <br>
                        <input name="username" type="text" id="username" autocomplete="off" value="" class="form-control" placeholder="Your username">
                        <br>
                        <input type="password" name="password" id="password" autocomplete="off" class="form-control" value="" placeholder="Your password">
                        <br>
                        <input type="submit" class="form-control submit" value="Login" name="login" id="login">
                        <p>If you forgot your account information, you will need the email you used to create your account and your Google Authenticator app.</p>
                        <input type="submit" class="form-control submit" value="Forgot account" name="forgot" id="forgot">
                    </form>
                </div>
            </div>
        </section>  
        <section class="races">
            <div class="row">
                <div class="races-col">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US" target="_blank">
                        <img src="images/android.png">
                    </a>
                </div>
                <div class="races-col">
                    <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
                        <img src="images/iphone.png">
                    </a>
                </div>
            </div>
        </section>
    </body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script>
      const constraints = {
          name: {
              presence: { allowEmpty: false }
          },
          email: {
              presence: { allowEmpty: false },
              email: true
          },
          message: {
              presence: { allowEmpty: false }
          }
      };

      const form = document.getElementById('contact-form');

      form.addEventListener('submit', function (event) {
          const formValues = {
              name: form.elements.name.value,
              email: form.elements.email.value,
              message: form.elements.message.value
          };

          const errors = validate(formValues, constraints);

          if (errors) {
              event.preventDefault();
              const errorMessage = Object
                  .values(errors)
                  .map(function (fieldValues) {
                      return fieldValues.join(', ')
                  })
                  .join("\n");

              alert(errorMessage);
          }
      }, false);
    </script>
    <script>
            var navLinks = document.getElementById("navLinks");
            function showMenu(){
                navLinks.style.right="0";
            }
            function hideMenu(){
                navLinks.style.right="-200px";
            }
        </script>
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>
