<?php
session_start();
$connection = mysqli_connect('localhost', 'root', 'pass', 'users');
$errors=[];
$errorMessage='';

$userip=$_SERVER["REMOTE_ADDR"];
$secret=$_SESSION["secret"];
$username=$_SESSION["username"];
$email=$_SESSION["email"];
$subscription=$_SESSION["subscription"];
$top_menu='<li><a href="myaccount.php" style="color: deepskyblue">Logged in as '.$username.': '.$subscription.' account</a></li>
                    <li><a href="logout.php" style="color: deepskyblue">Logout</a></li>';

if(isset($_POST["submit"])){
    if(!empty($_POST["username"]) and !empty($_POST["new_password"])){
        $new_username=$_POST["username"];
        $new_password=$_POST["new_password"];
        $_SESSION["username"]=$new_username;
        $_SESSION["password"]=$new_password;
        $_SESSION["make changes"]="username, password";
        header("Location:device_confirmations.php");
    }else{
        $errors[]="Please fill out both the username and password.";
    }
}

if(isset($_POST["delete"])){
    $query=mysqli_query($connection,"DELETE FROM google_auth WHERE userip='$userip' AND secret='$secret'");
    unset($_SESSION["email"]);
    unset($_SESSION["secret"]);
    unset($_SESSION["username"]);
    unset($_SESSION["make changes"]);
    header("Location:register.php");
}

if(isset($_POST["subscription"])){
    #$subscription="gold";
    #$_SESSION["subscription"]=$subscription;
    #$query=mysqli_query("UPDATE google_auth SET subscription='$subscription' WHERE userip='$userip' and secret='$secret'");
    header("Location:buy_gold_subscription.php");
}

if(!empty($errors)){
    $allErrors = join('<br/>', $errors);
    $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
}

?>

<html>
    <head>
        <meta charset="utf-8">
        <title>My account</title>
        <link rel="stylesheet" href="contact_style.css">
    </head>
    
    <body>
        <section class="header">
            <nav>
                <a href="index.php"><img src="images/logo.jpg"></a>
                <div class="nav-links" id="navLinks">
                    <i class="fa-solid fa-xmark" onclick="hideMenu()"></i>
                    <ul>
                        <?php echo $top_menu; ?>
                        <li><a href="index.php" style="color: deepskyblue">Home</a></li>
                    </ul>
                </div>
                <i class="fa-solid fa-bars" onclick="showMenu()"></i>
            </nav>
            <div class="text-box">
                <div class="contact-form">
                    <form id="contact-form" method="POST" action="myaccount.php">
                        <?php echo((!empty($errorMessage)) ? $errorMessage : '') ?>
                        <p><?php echo $username;?>, if you want to download videos, buy a month of gold. Subscripton does not renew automatically.</p>
                        <p>As long as you are using the same device in the same place 
                            as when you first created the account, you can change 
                            the username and password. Please change both.</p>
                        <input name="username" type="text" id="username" class="form-control" placeholder="New username">
                        <br>
                        <input type="password" name="new_password" id="new_password" autocomplete="off" class="form-control" value="" placeholder="New password">
                        <br>
                        <input type="submit" class="form-control submit" value="Make changes" name="submit" id="submit">
                        <input type="submit" class="form-control submit" value="Delete account" name="delete" id="delete">
                        <br>
                        <input type="submit" class="form-control submit" value="Buy Gold Subscription" name="subscription" id="subscription">
                    </form>
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
</html>
