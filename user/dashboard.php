<?php
session_start();

if(!isset($_SESSION['user'])){
header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>

<title>User Dashboard</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:0;
}

.header{
background:#2c3e50;
color:white;
padding:15px;
display:flex;
justify-content:space-between;
}

.container{
width:90%;
margin:auto;
margin-top:40px;
display:flex;
gap:30px;
flex-wrap:wrap;
}

.card{
width:250px;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0px 0px 10px #ccc;
text-align:center;
}

.card h3{
margin-bottom:10px;
}

.card a{
display:inline-block;
margin-top:10px;
padding:8px 15px;
background:#3498db;
color:white;
text-decoration:none;
border-radius:5px;
}

.logout{
background:red;
padding:6px 12px;
color:white;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<div class="header">

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>

<a class="logout" href="logout.php">Logout</a>

</div>


<div class="container">

<div class="card">

<h3>📚 Free Books</h3>

<p>Read free study books</p>

<a href="free_books.php">Read Books</a>

</div>


<div class="card">

<h3>📝 Essay Competition</h3>

<p>Participate in essay competition</p>

<a href="competition.php">Start Competition</a>

</div>


<div class="card">

<h3>📖 My Profile</h3>

<p>View your profile</p>

<a href="#">View</a>

</div>


</div>

</body>
</html>