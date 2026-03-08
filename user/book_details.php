<?php
include("../config/db.php");

$id=$_GET['id'];

$sql="SELECT * FROM books WHERE id=$id";

$result=mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);
?>

<h2><?php echo $row['title']; ?></h2>

<p><?php echo $row['description']; ?></p>

<p>Author: <?php echo $row['author']; ?></p>

<p>Price: <?php echo $row['price']; ?></p>

<a href="order.php?book_id=<?php echo $row['id']; ?>">
Buy Book
</a>