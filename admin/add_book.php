<?php
include("../config/db.php");

if(isset($_POST['add_book'])){

$title = $_POST['title'];
$author = $_POST['author'];
$category = $_POST['category'];
$description = $_POST['description'];
$price = $_POST['price'];
$weight = $_POST['weight'];

$pdf = $_FILES['pdf_file']['name'];
$image = $_FILES['book_image']['name'];

move_uploaded_file($_FILES['pdf_file']['tmp_name'], "../uploads/pdf/".$pdf);
move_uploaded_file($_FILES['book_image']['tmp_name'], "../uploads/covers/".$image);

$sql = "INSERT INTO books (title,author,category,description,price,pdf_file,book_image,weight,created_at)
VALUES ('$title','$author','$category','$description','$price','$pdf','$image','$weight',NOW())";

mysqli_query($conn,$sql);
$success = true;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Book</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="admin_theme.css">
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Books</div>
            <h1 class="page-title">Add Book</h1>
        </div>
        <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <?php if(isset($success)): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Book Added Successfully</div>
    <?php endif; ?>

    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <span style="font-weight:600;font-size:0.85rem;">Book Details</span>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Book Title</label>
                    <input type="text" name="title" placeholder="Book Title" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" placeholder="Author" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" placeholder="Category" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" placeholder="Book Description" class="form-textarea"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" placeholder="Price" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight</label>
                        <input type="text" name="weight" placeholder="Weight" class="form-input">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label class="form-label">Upload PDF</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-file-pdf" style="font-size:1.3rem;color:var(--rose);display:block;margin-bottom:6px;"></i>
                            <div style="font-size:0.72rem;color:var(--muted);">Click to upload PDF</div>
                            <input type="file" name="pdf_file" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Cover Image</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-image" style="font-size:1.3rem;color:var(--gold);display:block;margin-bottom:6px;"></i>
                            <div style="font-size:0.72rem;color:var(--muted);">Click to upload image</div>
                            <input type="file" name="book_image" required>
                        </div>
                    </div>
                </div>
                <button name="add_book" class="btn btn-gold" style="width:100%;justify-content:center;padding:11px;margin-top:4px;">
                    <i class="fa-solid fa-plus"></i> Add Book
                </button>
            </form>
        </div>
    </div>
</main>

</body>
</html>
