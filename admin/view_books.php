<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Gallery | View Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="bg-white shadow-sm py-5 sticky top-0 z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-indigo-700 uppercase tracking-tight flex items-center">
                <i class="fa-solid fa-book-bookmark mr-3"></i> My Library
            </h1>
            
            <div class="flex items-center gap-4">
                <a href="index.php" class="text-gray-600 hover:text-indigo-600 font-semibold text-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-gauge-high"></i> Admin Panel
                </a>
                
                <a href="upload_book.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-indigo-100 transition-all transform hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-plus mr-1"></i> Add New Book
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12 pb-20">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            <?php
            include("../config/db.php");
            $result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                ?>
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    
                    <div class="relative overflow-hidden h-72 bg-gray-100">
                        <img src="../uploads/covers/<?php echo $row['book_image']; ?>" 
                             alt="<?php echo $row['title']; ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-indigo-700 text-[10px] font-extrabold px-3 py-1.5 rounded-lg uppercase shadow-sm">
                            <?php echo $row['category']; ?>
                        </span>
                    </div>

                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 line-clamp-1 mb-1 italic group-hover:text-indigo-600 transition-colors">
                            <?php echo $row['title']; ?>
                        </h3>
                        <p class="text-gray-500 text-sm mb-5 flex items-center">
                            <i class="fa-solid fa-user-pen mr-2 text-gray-300"></i> <?php echo $row['author']; ?>
                        </p>

                        <div class="flex items-center justify-between mt-auto border-t border-gray-50 pt-5">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Pricing</span>
                                <?php if($row['is_free'] == 1): ?>
                                    <span class="text-emerald-500 font-extrabold flex items-center gap-1 text-sm">
                                        <i class="fa-solid fa-circle-check"></i> FREE
                                    </span>
                                <?php else: ?>
                                    <span class="text-indigo-600 font-black">₹<?php echo number_format($row['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="flex gap-2">
                                <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm shadow-blue-100" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                <a href="delete_book.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Pakka delete karna hai?')"
                                   class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm shadow-rose-100" title="Delete">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php 
                } 
            } else {
                echo "<div class='col-span-full py-20 text-center text-gray-400'>
                        <i class='fa-solid fa-box-open text-5xl mb-4 opacity-20'></i>
                        <p>No books available in the gallery.</p>
                      </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>