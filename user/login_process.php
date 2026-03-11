<?php
session_start();
include("../config/db.php");

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user'] = $row['name'];
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_name'] = $row['name'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
?>
