<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
    <style>
        .book-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(195px,1fr));gap:18px;}
        .book-tile{background:var(--surface);border:1px solid var(--border);border-radius:7px;overflow:hidden;transition:all 0.3s;}
        .book-tile:hover{transform:translateY(-6px);border-color:rgba(201,168,76,0.25);box-shadow:0 20px 40px rgba(0,0,0,0.4);}
        .book-tile-img{aspect-ratio:3/4;overflow:hidden;position:relative;}
        .book-tile-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.4s;filter:brightness(0.88);}
        .book-tile:hover .book-tile-img img{transform:scale(1.05);filter:brightness(1);}
        .book-tile-body{padding:13px;}
        .book-tile-title{font-family:'Cormorant Garamond',serif;font-size:0.98rem;font-weight:600;color:#fff;margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        .book-tile-author{font-size:0.7rem;color:var(--muted);margin-bottom:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        .book-tile-footer{display:flex;align-items:center;justify-content:space-between;padding-top:9px;border-top:1px solid var(--border);}
    </style>
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Collection</div>
            <h1 class="page-title">Book Gallery</h1>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="index.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-gauge-high"></i> Admin Panel</a>
            <a href="upload_book.php" class="btn btn-gold"><i class="fa-solid fa-plus"></i> Add New Book</a>
        </div>
    </div>

    <?php
    include("../config/db.php");
    $result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
    if(mysqli_num_rows($result) > 0): ?>
    <div class="book-grid">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="book-tile">
            <div class="book-tile-img">
                <?php if($row['is_free'] == 1): ?>
                    <span class="badge badge-green" style="position:absolute;top:9px;right:9px;z-index:2;">Free</span>
                <?php endif; ?>
                <img src="../uploads/covers/<?php echo $row['book_image']; ?>" alt="<?php echo $row['title']; ?>">
            </div>
            <div class="book-tile-body">
                <div class="book-tile-title"><?php echo $row['title']; ?></div>
                <div class="book-tile-author"><i class="fa-solid fa-user-pen" style="margin-right:3px;"></i><?php echo $row['author']; ?></div>
                <div class="book-tile-footer">
                    <span style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:0.98rem;color:<?php echo ($row['is_free']==1)?'#6ee7a0':'var(--gold-light)'; ?>">
                        <?php echo ($row['is_free']==1) ? 'Free' : '₹'.number_format($row['price'],2); ?>
                    </span>
                    <div style="display:flex;gap:5px;">
                        <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="fa-solid fa-pen-to-square" style="font-size:0.6rem;"></i></a>
                        <a href="delete_book.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Pakka delete karna hai?')" class="btn btn-danger btn-icon btn-sm" title="Delete"><i class="fa-solid fa-trash-can" style="font-size:0.6rem;"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:80px 20px;color:var(--muted);">
        <i class="fa-solid fa-box-open" style="font-size:2.8rem;display:block;margin-bottom:14px;opacity:0.15;"></i>
        <p>No books available in the gallery.</p>
    </div>
    <?php endif; ?>
</main>

</body>
</html>
