<?php
session_start(); 


if (!isset($_SESSION['user'])) {
   
    header("Location:loginAd.php");
    exit(); 
}else{
    header("Location:includes/logout.php");
}
?>

