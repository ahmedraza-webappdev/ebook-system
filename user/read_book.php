<?php

include("../config/db.php");

$id=$_GET['id'];

$sql="SELECT * FROM books WHERE id=$id";
$result=mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>
<head>

<title><?php echo $row['title']; ?></title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f4f4;
}

.header{
background:#2c3e50;
color:white;
padding:15px;
}

.viewer{
width:95%;
margin:auto;
margin-top:20px;
background:white;
padding:10px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

</style>

</head>

<body>

<div class="header">

<h2><?php echo $row['title']; ?></h2>

</div>

<div class="viewer">

<iframe
src="../uploads/pdf/<?php echo $row['pdf_file']; ?>"
width="100%"
height="700px">
</iframe>

</div>

</body>
</html>