<?php
session_start();
if(isset($_SESSION["secret"])){
    session_regenerate_id();
    unset($_SESSION["email"]);
    unset($_SESSION["secret"]);
    unset($_SESSION["username"]);
    unset($_SESSION["make changes"]);
    
    session_unset();
    header("Location:index.php");
}

?>