<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}else{
    header("Location: dashboard.php");
    exit();
}
?>