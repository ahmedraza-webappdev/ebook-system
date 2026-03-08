<?php
include("../config/db.php");  
$result = mysqli_query($conn,"SELECT * FROM books");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Books List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; }
        .table thead { background-color: #4e73df; color: white; }
        .table thead th { border: none; padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
        .book-img { width: 50px; height: 70px; object-fit: cover; border-radius: 6px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .book-img:hover { transform: scale(1.1); }
        .badge-category { background-color: #eef2ff; color: #4e73df; font-weight: 500; padding: 5px 12px; border-radius: 20px; }
        
        /* Naya Style: Free Badge */
        .badge-free {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            box-shadow: 0 2px 10px rgba(34, 197, 94, 0.3);
        }

        .action-btn { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; }
        .btn-add { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none; padding: 10px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Books Inventory</h2>
            <p class="text-muted">Manage your library's digital collection</p>
        </div>
        <a href="upload_book.php" class="btn btn-primary btn-add">
            <i class="bi bi-plus-lg me-2"></i> Add New Book
        </a>
    </div>

    <div class="card main-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Book Info</th>
                        <th>Category</th>
                        <th>Price Status</th>
                        <th>Physical Info</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td class="text-center text-muted fw-bold">#<?php echo $row['id']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="../uploads/covers/<?php echo $row['book_image']; ?>" class="book-img me-3" alt="Cover">
                                <div>
                                    <div class="fw-bold text-dark"><?php echo $row['title']; ?></div>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i><?php echo $row['author']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-category"><?php echo $row['category']; ?></span></td>
                        <td>
                            <?php if($row['is_free'] == 1): ?>
                                <span class="badge-free"><i class="bi bi-unlock-fill me-1"></i> FREE</span>
                            <?php else: ?>
                                <div class="fw-bold text-dark">₹<?php echo number_format($row['price'], 2); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="d-block text-muted">
                                <i class="bi bi-box-seam me-1"></i><?php echo $row['weight']; ?>
                            </small>
                        </td>
                        <td class="text-center">
                            <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary action-btn me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="delete_book.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-outline-danger action-btn" 
                               onclick="return confirm('Do you really want to delete this book?');"
                               title="Delete">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        
        <?php if(mysqli_num_rows($result) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">No books found in the database.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>