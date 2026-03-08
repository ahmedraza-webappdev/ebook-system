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

        if(mysqli_query($conn, $sql)){
            $success_msg = "Book published successfully!";
        } else {
            $error_msg = "Database error. Please check your columns.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | New Book Publication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; }
        .form-input { 
            border: 1px solid #e5e7eb; 
            transition: all 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        .preview-box {
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
        }
    </style>
</head>
<body class="p-6 md:p-12">

    <div class="max-w-5xl mx-auto">
        <?php if($success_msg): ?>
        <div class="bg-white border-l-4 border-green-500 shadow-sm p-4 mb-6 rounded-r-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fa-solid fa-check-circle text-green-500 mr-3 text-lg"></i>
                <span class="text-gray-700 font-medium"><?php echo $success_msg; ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">×</button>
        </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1 bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Book Details</h2>
                    <a href="index.php" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Back to Dashboard</a>
                </div>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <div class="flex items-center gap-3 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 mb-6">
                        <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" class="w-5 h-5 accent-indigo-600 cursor-pointer">
                        <label for="freeCheck" class="text-indigo-900 font-semibold cursor-pointer select-none">Mark as Free Content</label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Book Title</label>
                            <input type="text" name="title" required placeholder="e.g. Advanced PHP Patterns" class="w-full form-input px-4 py-3 rounded-xl bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Author</label>
                            <input type="text" name="author" required class="w-full form-input px-4 py-3 rounded-xl bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Category</label>
                            <input type="text" name="category" class="w-full form-input px-4 py-3 rounded-xl bg-gray-50">
                        </div>

                        <div id="priceDiv">
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Price (₹)</label>
                            <input type="number" name="price" id="bookPrice" step="0.01" class="w-full form-input px-4 py-3 rounded-xl bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Weight</label>
                            <input type="text" name="weight" placeholder="500g" class="w-full form-input px-4 py-3 rounded-xl bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full form-input px-4 py-3 rounded-xl bg-gray-50" placeholder="Describe the book content..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-indigo-400 transition cursor-pointer">
                            <i class="fa-solid fa-file-pdf text-red-400 text-2xl mb-2"></i>
                            <span class="block text-xs font-bold text-gray-500 uppercase">PDF File</span>
                            <input type="file" name="pdf_file" required class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-indigo-400 transition cursor-pointer">
                            <i class="fa-solid fa-image text-blue-400 text-2xl mb-2"></i>
                            <span class="block text-xs font-bold text-gray-500 uppercase">Cover Image</span>
                            <input type="file" name="book_image" id="imgInput" required class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>

                    <button name="add_book" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        Add to Collection
                    </button>
                </form>
            </div>

            <div class="w-full lg:w-80 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-6 text-center">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Cover Preview</h3>
                    <div class="preview-box w-full aspect-[3/4] bg-gray-100 rounded-2xl overflow-hidden mb-4 border border-gray-100 flex items-center justify-center">
                        <img id="previewImg" src="https://via.placeholder.com/300x400?text=No+Image" class="w-full h-full object-cover">
                    </div>
                    <p class="text-xs text-gray-400 px-4 italic">This is how your book will appear in the library store.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Live Preview Logic
        document.getElementById('imgInput').onchange = evt => {
            const [file] = imgInput.files
            if (file) {
                previewImg.src = URL.createObjectURL(file)
            }
        }

        // Price Toggle Logic
        function togglePrice() {
            const isFree = document.getElementById('freeCheck').checked;
            const priceInput = document.getElementById('bookPrice');
            const priceDiv = document.getElementById('priceDiv');
            if(isFree) {
                priceInput.value = "0";
                priceDiv.style.opacity = "0.3";
                priceInput.readOnly = true;
            } else {
                priceDiv.style.opacity = "1";
                priceInput.readOnly = false;
            }
        }
    </script>

</body>
</html>