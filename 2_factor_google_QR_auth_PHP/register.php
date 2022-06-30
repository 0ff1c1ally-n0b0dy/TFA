<?php 
session_start();
require_once("googleLib/GoogleAuthenticator.php");
$ga = new GoogleAuthenticator();

#The secret is some random choice of characters and numbers which is also embedded somehow in the QR.
$secret=$ga->createSecret();
$connection = mysqli_connect('localhost', 'root', 'PeDinafaraLeopardul04!', 'users');


if(isset($_POST["create"])){
    $errors=[];
    $errorMessage='';

    $googlecode=$_POST["googlecode"]; 
    $email=$_POST["email"];
    $username=$_POST["username"];
    $password=$_POST["password"];
    $userip=$_SERVER['REMOTE_ADDR'];
    
    $status=1;

    #check if username was used before  
    $query=mysqli_query($connection,"SELECT * FROM google_auth WHERE username='$username'");
    $nrows=mysqli_num_rows($query);
    if($nrows>0){
        $errors[]="Username is already used. Please choose another one.";
    }else{
        #check if email was used before
        $query=mysqli_query($connection,"SELECT * FROM google_auth WHER0E email='$email'");
        $nrows=mysqli_num_rows($query);
        if($nrows>0){
            $errors[]="Email is already used. Please use another one.";
        }else{
            #check if IP is associated with another account.
            $query=mysqli_query($connection,"SELECT * FROM google_auth WHERE userip='$userip'");
            $nrows=mysqli_num_rows($query);
            if($nrows>0){
                $errors[]="Your IP is used by another account. For security reasons, you are only allowed to have one account per IP.";
            }else{
                if(empty($username)){
                    $errors[]="Please enter a username";
                }
                if(empty($email)){
                    $errors[]="Please enter an email";
                }else{
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $errors[]="Email is not valid";
                    }
                }
                if(empty($password)){
                    $errors[]="Please enter a password";
                }
            }
        }    
    }
    
    if(empty($errors)){
        #$query=mysqli_query($connection,"INSERT INTO google_auth(email,username,password,userip,secret) VALUES('$email','$username','$password','$userip','$secret')");
        $_SESSION["email"]=$email;
        $_SESSION["secret"]=$secret;
        $_SESSION["username"]=$username;
        $_SESSION["password"]=$password;
        $_SESSION["subscription"]="silver";
        
        $_SESSION["make changes"]="all";
        
        header("Location:device_confirmations.php");
        exit();
    }else{
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    }
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Create account</title>
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
                    <h2>Create account.</h2>
                    <p>Please scroll to the bottom of the page to get the Google Authenticator app on your phone.
                    For security reasons, you will always use Google Authenticator to log in.</p>
                <div class="contact-form">
                    <form id="contact-form" method="POST" action="register.php">
                        <?php echo((!empty($errorMessage)) ? $errorMessage : '') ?> 
                        <br>
                        <input type="hidden" name="googlecode" value="<?php echo $secret;?>">
                        <br>
                        <input name="username" type="text" id="username" autocomplete="off" value="" class="form-control" placeholder="Choose username" required>
                        <br>
                        <input name="email" type="email" id="email" autocomplete="off" value="<?php echo $email;?>" class="form-control" placeholder="Your email" required>
                        <br>
                        <input type="password" name="password" id="password" autocomplete="off" class="form-control" value="" placeholder="Your password" required>
                        <br>
                        <input type="submit" class="form-control submit" value="Create account" name="create" id="create">
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
