<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

// Path Logic: Check agar hum user folder ke andar hain
$is_in_user = (basename(dirname($_SERVER['PHP_SELF'])) == 'user');
$root = $is_in_user ? '../' : './';
$user_dir = $is_in_user ? '' : 'user/';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="index.php">
            <span class="text-primary">📚</span> E-LIBRARY
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link fw-semibold" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="#">Explore</a></li>
            </ul>

            <div class="d-flex align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="competition.php" class="btn btn-outline-warning btn-sm me-3">Competition</a>
                    
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm px-3 rounded-pill" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Hi, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="books.php"><i class="fa-solid fa-book me-2"></i>My Books</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-link text-white text-decoration-none me-3" href="login.php">Login</a>
                    <a class="btn btn-success rounded-pill px-4 shadow-sm" href="register.php">Join Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>