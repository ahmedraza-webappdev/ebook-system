<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Overview</div>
            <h1 class="page-title">Admin Panel</h1>
        </div>
        <span class="badge badge-gold"><?php echo date('d M Y'); ?></span>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:14px;">

        <a href="upload_book.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-upload"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Upload Book</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Add to collection</div>
        </a>

        <a href="view_books.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-book-open"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">View Books</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Browse gallery</div>
        </a>

        <a href="view_submissions.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-file-lines"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Essay Submissions</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Read student essays</div>
        </a>

        <a href="manage_competitions.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-trophy"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Manage Competitions</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Add new events & winners</div>
        </a>

        <a href="view_users.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-users"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Users</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Manage members</div>
        </a>

        <a href="books_list.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--gold);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-pen-to-square"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Edit Books</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Update existing books</div>
        </a>

        <a href="logout.php" class="card" style="padding:22px; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--rose)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="color:var(--rose);font-size:1.3rem;margin-bottom:10px;"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div style="font-weight:600;color:#fff;font-size:0.88rem;">Logout</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">End session</div>
        </a>

    </div>
</main>

</body>
</html>
