<?php

include("../config/db.php");

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM books WHERE id=$id");

header("Location: view_books.php");

?>