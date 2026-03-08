<?php

include("../config/db.php");

$sql="SELECT orders.*,books.title
FROM orders
JOIN books ON orders.book_id=books.id";

$result=mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result)){

echo $row['title']."<br>";

echo $row['order_type']."<br>";

echo $row['payment_status']."<br>";

echo "<hr>";

}

?>