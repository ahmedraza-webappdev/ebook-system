<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-name">📚 <span>E-Lib</span> Admin</div>
        <div class="sidebar-brand-sub">Management Panel</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group-label">Main</div>
        <a href="dashboard.php" class="nav-item <?= $cur=='dashboard.php'?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>

        <div class="nav-group-label" style="margin-top:10px;">Books</div>
        <a href="upload_book.php" class="nav-item <?= $cur=='upload_book.php'?'active':'' ?>"><i class="fa-solid fa-upload"></i> Upload Book</a>
        <a href="view_books.php"  class="nav-item <?= $cur=='view_books.php'?'active':'' ?>"><i class="fa-solid fa-book-open"></i> View Books</a>
        <a href="books_list.php"  class="nav-item <?= $cur=='books_list.php'?'active':'' ?>"><i class="fa-solid fa-list"></i> Books List</a>
        <a href="add_book.php"    class="nav-item <?= $cur=='add_book.php'?'active':'' ?>"><i class="fa-solid fa-plus"></i> Add Book</a>

        <div class="nav-group-label" style="margin-top:10px;">Community</div>
        <a href="view_users.php" class="nav-item <?= $cur=='view_users.php'?'active':'' ?>"><i class="fa-solid fa-users"></i> Users</a>
        <a href="orders.php"     class="nav-item <?= $cur=='orders.php'?'active':'' ?>"><i class="fa-solid fa-receipt"></i> Orders</a>

        <div class="nav-group-label" style="margin-top:10px;">Competitions</div>
        <a href="manage_competitions.php" class="nav-item <?= $cur=='manage_competitions.php'?'active':'' ?>"><i class="fa-solid fa-trophy"></i> Manage</a>
        <a href="view_submissions.php"    class="nav-item <?= $cur=='view_submissions.php'?'active':'' ?>"><i class="fa-solid fa-file-lines"></i> Submissions</a>

        <div style="margin-top:20px; padding-top:12px; border-top:1px solid var(--border);">
            <a href="logout.php" class="nav-item danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>
    <div class="sidebar-footer">© <?= date('Y') ?> E-Library</div>
</aside>
