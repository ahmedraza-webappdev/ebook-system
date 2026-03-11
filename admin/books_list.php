<?php
include("../config/db.php");  
$result = mysqli_query($conn,"SELECT * FROM books");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
    <style>
        .book-cover{width:38px;height:52px;object-fit:cover;border-radius:3px;border:1px solid var(--border);}
    </style>
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Inventory</div>
            <h1 class="page-title">Books Inventory</h1>
        </div>
        <a href="upload_book.php" class="btn btn-gold"><i class="fa-solid fa-plus"></i> Add New Book</a>
    </div>

    <div class="card">
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">ID</th>
                        <th>Book Info</th>
                        <th>Category</th>
                        <th>Price Status</th>
                        <th>Physical Info</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td style="text-align:center;"><span style="font-size:0.72rem;color:var(--muted);font-weight:700;">#<?php echo $row['id']; ?></span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:11px;">
                                <img src="../uploads/covers/<?php echo $row['book_image']; ?>" class="book-cover" alt="Cover">
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.84rem;"><?php echo $row['title']; ?></div>
                                    <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;"><i class="fa-solid fa-user" style="margin-right:4px;"></i><?php echo $row['author']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-muted"><?php echo $row['category']; ?></span></td>
                        <td>
                            <?php if($row['is_free'] == 1): ?>
                                <span class="badge badge-green"><i class="fa-solid fa-unlock" style="margin-right:4px;"></i>FREE</span>
                            <?php else: ?>
                                <span style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1rem;color:var(--gold-light);">₹<?php echo number_format($row['price'], 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span style="font-size:0.78rem;color:var(--muted);"><i class="fa-solid fa-box-seam" style="margin-right:5px;"></i><?php echo $row['weight']; ?></span></td>
                        <td style="text-align:center;">
                            <div style="display:flex;justify-content:center;gap:6px;">
                                <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="fa-solid fa-pen" style="font-size:0.62rem;"></i></a>
                                <a href="delete_book.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Do you really want to delete this book?');" class="btn btn-danger btn-icon btn-sm" title="Delete"><i class="fa-solid fa-trash" style="font-size:0.62rem;"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if(mysqli_num_rows($result) == 0): ?>
        <div style="text-align:center;padding:60px 20px;color:var(--muted);">
            <i class="fa-solid fa-book" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.15;"></i>
            No books found in the database.
        </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
