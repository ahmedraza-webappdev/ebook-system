<?php
include("../config/db.php");

$sql="SELECT * FROM books WHERE type='free'";
$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Free Books</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
}

.container{
width:90%;
margin:auto;
display:flex;
flex-wrap:wrap;
gap:20px;
}

.card{
width:250px;
background:white;
border-radius:10px;
box-shadow:0px 0px 10px #ccc;
padding:15px;
text-align:center;
}

.card img{
width:100%;
height:200px;
object-fit:cover;
border-radius:8px;
}

.card h3{
margin:10px 0;
}

.btn{
display:inline-block;
padding:8px 15px;
background:#2ecc71;
color:white;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<h2 style="text-align:center;">Free Books</h2>

<div class="container">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="card">

<img src="../uploads/covers/<?php echo $row['cover']; ?>">

<h3><?php echo $row['title']; ?></h3>

<p><?php echo $row['author']; ?></p>

<a class="btn" href="read_book.php?id=<?php echo $row['id']; ?>">
Read Book
</a>

</div>

<?php } ?>

</div>

</body>
</html>