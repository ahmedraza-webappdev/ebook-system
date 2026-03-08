<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 

// User login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search = "";
$image_path = "../uploads/covers/"; // Path variable define kar diya

// SQL query same rakhi hai
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT books.* FROM books 
            INNER JOIN orders ON books.id = orders.book_id 
            WHERE orders.user_id = '$user_id' 
            AND (books.title LIKE '%$search%' OR books.author LIKE '%$search%')
            GROUP BY books.id";
} else {
    $sql = "SELECT books.* FROM books 
            INNER JOIN orders ON books.id = orders.book_id 
            WHERE orders.user_id = '$user_id'
            GROUP BY books.id 
            ORDER BY orders.id DESC";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library | Your Books</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root { --primary-color: #6366f1; }
        body { background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; }
        
        .hero-section { 
            background: #1e293b; padding: 60px 15px; color: white; 
            text-align: center; border-radius: 0 0 30px 30px; margin-bottom: 40px; 
        }

        .book-card { 
            border: none; border-radius: 20px; overflow: hidden; 
            background: #ffffff; transition: all 0.3s ease; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); height: 100%; 
        }
        
        .book-card:hover { transform: translateY(-8px); box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1); }
        
        .book-img-container { height: 260px; overflow: hidden; background: #f1f5f9; position: relative; }
        
        /* Mobile height adjustment */
        @media (max-width: 576px) { .book-img-container { height: 180px; } }
        
        .book-img { width: 100%; height: 100%; object-fit: cover; transition: 0.4s; }
        
        .btn-read { 
            background: #10b981; color: white; border-radius: 12px; 
            font-weight: 600; padding: 10px 15px; width: 100%; 
            text-align: center; text-decoration: none; display: block; border: none;
        }
        .btn-read:hover { background: #059669; color: white; }

        .search-container { max-width: 550px; margin: 0 auto; }
        .search-container .form-control { border-radius: 50px 0 0 50px; padding: 12px 25px; border: none; }
        .search-container .btn { border-radius: 0 50px 50px 0; padding: 0 25px; background: var(--primary-color); border: none; }
    </style>
</head>
<body>

<header class="hero-section shadow">
    <div class="container">
        <h2 class="fw-bold mb-2">My Digital Library</h2>
        <p class="text-white-50 mb-4">Your personal collection of knowledge</p>
        <div class="search-container">
            <form method="GET" class="input-group shadow-lg">
                <input type="text" name="search" class="form-control" placeholder="Search title or author..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary shadow-none" type="submit">
                    <i class="fa-solid fa-magnifying-glass text-white"></i>
                </button>
            </form>
        </div>
    </div>
</header>

<div class="container mb-5">
    <div class="row g-3 g-md-4">
        <?php 
        if(mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)): 
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card book-card">
                    <div class="book-img-container">
                        <img src="<?php echo $image_path . $row['book_image']; ?>" 
                             class="book-img" 
                             alt="Cover"
                             onerror="this.src='../assets/img/default-cover.jpg'"> </div>
                    
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <h6 class="fw-bold text-dark text-truncate mb-1" title="<?php echo htmlspecialchars($row['title']); ?>">
                            <?php echo $row['title']; ?>
                        </h6>
                        <p class="text-muted small mb-3 text-truncate">By <?php echo $row['author']; ?></p>
                        
                        <div class="mt-auto">
                            <a href="view_book.php?id=<?php echo $row['id']; ?>" class="btn btn-read btn-sm shadow-sm">
                                <i class="fa-solid fa-book-open me-1"></i> <span class="d-none d-sm-inline">Read Now</span><span class="d-inline d-sm-none">Read</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-book-bookmark fa-4x text-light"></i>
                </div>
                <h4 class="text-muted">No books found in your library.</h4>
                <a href="index.php" class="btn btn-primary mt-3 px-4 rounded-pill">Explore Store</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>