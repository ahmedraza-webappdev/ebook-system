<?php
include("../config/db.php");

$success_msg = false;
$error_msg = false;

if(isset($_POST['add_book'])){
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $category    = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = !empty($_POST['price']) ? $_POST['price'] : 0;
    $weight      = isset($_POST['weight']) ? mysqli_real_escape_string($conn, $_POST['weight']) : '';
    $is_free     = isset($_POST['is_free']) ? 1 : 0;
    if($is_free == 1) { $price = 0; }
    if (!is_dir("../uploads/pdf")) { mkdir("../uploads/pdf", 0777, true); }
    if (!is_dir("../uploads/covers")) { mkdir("../uploads/covers", 0777, true); }
    $pdf_name   = time() . "_" . $_FILES['pdf_file']['name'];
    $image_name = time() . "_" . $_FILES['book_image']['name'];
    if(move_uploaded_file($_FILES['pdf_file']['tmp_name'], "../uploads/pdf/".$pdf_name) &&
       move_uploaded_file($_FILES['book_image']['tmp_name'], "../uploads/covers/".$image_name)){
        $sql = "INSERT INTO books (title, author, category, description, price, pdf_file, book_image, weight, is_free, created_at)
                VALUES ('$title', '$author', '$category', '$description', '$price', '$pdf_name', '$image_name', '$weight', '$is_free', NOW())";
        if(mysqli_query($conn, $sql)){ $success_msg = "Book published successfully!"; } else { $error_msg = "Database error. Please check your columns."; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Book | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Books</div>
            <h1 class="page-title">New Book Publication</h1>
        </div>
        <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
    </div>

    <?php if($success_msg): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if($error_msg): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 270px;gap:22px;align-items:start;">

        <div class="card">
            <div class="card-header">
                <span style="font-weight:600;font-size:0.85rem;">Book Details</span>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:0.72rem;color:var(--muted);">Mark as Free</span>
                    <label class="toggle">
                        <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" required placeholder="e.g. Advanced PHP Patterns" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-input">
                    </div>
                    <div class="form-group" id="priceDiv">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" id="bookPrice" step="0.01" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight</label>
                        <input type="text" name="weight" placeholder="500g" class="form-input">
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" placeholder="Describe the book content..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PDF File</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-file-pdf" style="font-size:1.3rem;color:var(--rose);display:block;margin-bottom:6px;"></i>
                            <div style="font-size:0.72rem;color:var(--muted);">Click to upload PDF</div>
                            <input type="file" name="pdf_file" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cover Image</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-image" style="font-size:1.3rem;color:var(--gold);display:block;margin-bottom:6px;"></i>
                            <div style="font-size:0.72rem;color:var(--muted);">Click to upload image</div>
                            <input type="file" name="book_image" id="imgInput" required>
                        </div>
                    </div>
                </div>
                <button name="add_book" class="btn btn-gold" style="width:100%;justify-content:center;padding:11px;margin-top:6px;">
                    <i class="fa-solid fa-plus"></i> Add to Collection
                </button>
            </div>
        </div>

        <div class="card" style="position:sticky;top:20px;">
            <div class="card-header"><span style="font-size:0.7rem;color:var(--muted);letter-spacing:0.1em;text-transform:uppercase;">Cover Preview</span></div>
            <div class="card-body" style="text-align:center;">
                <div style="aspect-ratio:3/4;background:rgba(255,255,255,0.02);border-radius:5px;overflow:hidden;border:1px solid var(--border);margin-bottom:10px;">
                    <img id="previewImg" src="https://via.placeholder.com/300x400/1c2333/c9a84c?text=No+Image" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <p style="font-size:0.68rem;color:var(--muted);font-style:italic;">Preview updates on cover selection</p>
            </div>
        </div>

    </form>
</main>

<script>
document.getElementById('imgInput').onchange = evt => {
    const [file] = imgInput.files;
    if(file){ previewImg.src = URL.createObjectURL(file); }
}
function togglePrice(){
    const isFree = document.getElementById('freeCheck').checked;
    const priceInput = document.getElementById('bookPrice');
    const priceDiv = document.getElementById('priceDiv');
    if(isFree){ priceInput.value="0"; priceDiv.style.opacity="0.3"; priceInput.readOnly=true; }
    else { priceDiv.style.opacity="1"; priceInput.readOnly=false; }
}
</script>
</body>
</html>
