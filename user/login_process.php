<?php

session_start();

include("config/db.php");

$email=$_POST['email'];
$password=$_POST['password'];

$sql="SELECT * FROM users WHERE email='$email' AND password='$password'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

$row=mysqli_fetch_assoc($result);

$_SESSION['user']=$row['name'];

header("Location: user/dashboard.php");

}else{

echo "Invalid Email or Password";

}

$_SESSION['user']=$email;

header("Location: dashboard.php");
?>


