<?php
include("../config/db.php");

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
}else{
    die("Book ID missing");
}

$result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
$row = mysqli_fetch_assoc($result);
$success_msg = false;

if(isset($_POST['update'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $is_free = isset($_POST['is_free']) ? 1 : 0;
    $price = ($is_free == 1) ? 0 : $_POST['price'];
    $pdf = $_FILES['pdf_file']['name'];
    $image = $_FILES['book_image']['name'];
    if($pdf != ""){ $pdf_name = time()."_".$pdf; move_uploaded_file($_FILES['pdf_file']['tmp_name'], "../uploads/pdf/".$pdf_name); } else { $pdf_name = $row['pdf_file']; }
    if($image != ""){ $image_name = time()."_".$image; move_uploaded_file($_FILES['book_image']['tmp_name'], "../uploads/covers/".$image_name); } else { $image_name = $row['book_image']; }
    $sql = "UPDATE books SET title='$title', author='$author', category='$category', description='$description', price='$price', pdf_file='$pdf_name', book_image='$image_name', weight='$weight', is_free='$is_free' WHERE id=$id";
    if(mysqli_query($conn, $sql)){
        $success_msg = "Book updated successfully!";
        $result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Books</div>
            <h1 class="page-title">Edit Book</h1>
        </div>
        <a href="books_list.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
    </div>

    <?php if($success_msg): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo $success_msg; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 270px;gap:22px;align-items:start;">

        <div class="card">
            <div class="card-header">
                <span style="font-weight:600;font-size:0.85rem;">Book Details</span>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:0.72rem;color:var(--muted);">Mark as Free</span>
                    <label class="toggle">
                        <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" <?php echo ($row['is_free'] == 1) ? 'checked' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" value="<?php echo $row['title']; ?>" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author Name</label>
                        <input type="text" name="author" value="<?php echo $row['author']; ?>" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="<?php echo $row['category']; ?>" class="form-input">
                    </div>
                    <div class="form-group" id="priceDiv" style="<?php echo ($row['is_free'] == 1) ? 'opacity:0.3;' : ''; ?>">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" id="bookPrice" step="0.01" value="<?php echo $row['price']; ?>" <?php echo ($row['is_free'] == 1) ? 'readonly' : ''; ?> class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (g)</label>
                        <input type="text" name="weight" value="<?php echo $row['weight']; ?>" class="form-input">
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea"><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Update Cover</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-image" style="font-size:1.2rem;color:var(--gold);display:block;margin-bottom:5px;"></i>
                            <div style="font-size:0.7rem;color:var(--muted);">Click to choose image</div>
                            <input type="file" name="book_image" id="imgInput">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Update PDF File</label>
                        <div class="upload-zone">
                            <i class="fa-solid fa-file-pdf" style="font-size:1.2rem;color:var(--rose);display:block;margin-bottom:5px;"></i>
                            <div style="font-size:0.7rem;color:var(--muted);">Click to choose PDF</div>
                            <input type="file" name="pdf_file">
                        </div>
                    </div>
                </div>
                <button name="update" class="btn btn-gold" style="width:100%;justify-content:center;padding:11px;margin-top:6px;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </div>

        <div class="card" style="position:sticky;top:20px;">
            <div class="card-header"><span style="font-size:0.7rem;color:var(--muted);letter-spacing:0.1em;text-transform:uppercase;">Cover Preview</span></div>
            <div class="card-body" style="text-align:center;">
                <div style="aspect-ratio:3/4;overflow:hidden;border-radius:5px;border:1px solid var(--border);margin-bottom:10px;">
                    <img id="previewImg" src="../uploads/covers/<?php echo $row['book_image']; ?>" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <p style="font-size:0.65rem;color:var(--muted);word-break:break-all;">PDF: <?php echo $row['pdf_file']; ?></p>
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
