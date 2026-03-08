<?php
include("../config/db.php");

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
}else{
    die("Book ID missing");
}

// Get current book data
$result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

$success_msg = false;

if(isset($_POST['update'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    
    // Free/Paid Logic
    $is_free = isset($_POST['is_free']) ? 1 : 0;
    $price = ($is_free == 1) ? 0 : $_POST['price'];

    // File Handling
    $pdf = $_FILES['pdf_file']['name'];
    $image = $_FILES['book_image']['name'];

    if($pdf != ""){
        $pdf_name = time() . "_" . $pdf;
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], "../uploads/pdf/".$pdf_name);
    } else {
        $pdf_name = $row['pdf_file'];
    }

    if($image != ""){
        $image_name = time() . "_" . $image;
        move_uploaded_file($_FILES['book_image']['tmp_name'], "../uploads/covers/".$image_name);
    } else {
        $image_name = $row['book_image'];
    }

    $sql = "UPDATE books SET 
            title='$title', 
            author='$author', 
            category='$category', 
            description='$description', 
            price='$price', 
            pdf_file='$pdf_name', 
            book_image='$image_name', 
            weight='$weight',
            is_free='$is_free' 
            WHERE id=$id";

    if(mysqli_query($conn, $sql)){
        $success_msg = "Book updated successfully!";
        // Refresh data
        $result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Edit Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .form-input { border: 1px solid #e2e8f0; transition: all 0.2s; }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); outline: none; }
    </style>
</head>
<body class="p-6 md:p-12">

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Edit <span class="text-indigo-600">Book</span></h2>
            <a href="books_list.php" class="bg-white border border-slate-200 px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <?php if($success_msg): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-8 rounded-2xl flex items-center shadow-sm">
                <i class="fa-solid fa-circle-check mr-3 text-lg"></i> <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1 bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                
                <div class="bg-indigo-50 border border-indigo-100 p-5 rounded-2xl mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <div>
                            <p class="font-bold text-indigo-900 leading-tight">Price Status</p>
                            <p class="text-xs text-indigo-600">Switch between Free and Paid</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" class="sr-only peer" <?php echo ($row['is_free'] == 1) ? 'checked' : ''; ?>>
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Book Title</label>
                        <input type="text" name="title" value="<?php echo $row['title']; ?>" required class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Author Name</label>
                        <input type="text" name="author" value="<?php echo $row['author']; ?>" required class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Category</label>
                        <input type="text" name="category" value="<?php echo $row['category']; ?>" class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50">
                    </div>

                    <div id="priceDiv" style="<?php echo ($row['is_free'] == 1) ? 'opacity: 0.3;' : ''; ?>">
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Price (₹)</label>
                        <input type="number" name="price" id="bookPrice" step="0.01" value="<?php echo $row['price']; ?>" <?php echo ($row['is_free'] == 1) ? 'readonly' : ''; ?> class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Weight (g)</label>
                        <input type="text" name="weight" value="<?php echo $row['weight']; ?>" class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full form-input px-4 py-3.5 rounded-2xl bg-slate-50"><?php echo $row['description']; ?></textarea>
                    </div>

                    <div class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Update Cover</label>
                            <input type="file" name="book_image" id="imgInput" class="text-xs">
                        </div>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Update PDF File</label>
                            <input type="file" name="pdf_file" class="text-xs">
                        </div>
                    </div>
                </div>

                <button name="update" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 mt-8 transition-all active:scale-[0.98]">
                    Save Changes
                </button>
            </div>

            <div class="w-full lg:w-80 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 text-center">Cover Preview</h3>
                    <div class="w-full aspect-[3/4] bg-slate-50 rounded-2xl overflow-hidden mb-4 border border-slate-100 flex items-center justify-center">
                        <img id="previewImg" src="../uploads/covers/<?php echo $row['book_image']; ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-400">Current PDF: <span class="text-indigo-500 font-medium truncate block px-4"><?php echo $row['pdf_file']; ?></span></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Live Preview for Image
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