<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 
$image_path = "../uploads/covers/";

// --- FILTER LOGIC ---
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EBook Library | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        html { scroll-behavior: smooth; }
        :root { 
            --primary: #6366f1; 
            --dark: #0f172a; 
            --dark-hover: #020617; 
            --free: #10b981; 
            --slate-100: #f1f5f9;
        }
        body { background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; overflow-x: hidden; }
        
        .hero { 
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.8)), 
                        url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; 
            padding: 100px 20px; color: white; margin-bottom: 0;
            border-radius: 0 0 50px 50px;
        }

        .stats-bar { margin-top: -50px; position: relative; z-index: 10; }
        .stat-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.02); }

        .cat-card { 
            background: white; border-radius: 20px; padding: 20px; text-align: center;
            transition: 0.3s; cursor: pointer; border: 1px solid transparent;
        }
        .cat-card:hover { border-color: var(--primary); background: #f5f3ff; transform: translateY(-5px); }
        .cat-icon { width: 60px; height: 60px; background: #eef2ff; color: var(--primary); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; }

        .book-card { 
            border: none; border-radius: 20px; transition: all 0.4s ease; 
            background: white; border: 1px solid rgba(0,0,0,0.05); height: 100%;
            display: flex; flex-direction: column;
        }
        .book-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        
        .book-img-wrapper { 
            position: relative; overflow: hidden; 
            border-radius: 20px 20px 0 0;
            aspect-ratio: 3/4;
        }
        .book-img { width: 100%; height: 100%; object-fit: cover; }
        
        .buy-btn { 
            background: var(--dark); border-radius: 10px; padding: 8px 15px;
            font-weight: 700; transition: all 0.3s; color: white !important;
            text-decoration: none; font-size: 0.85rem;
        }
        .buy-btn:hover { background: var(--primary); }

        .feature-box { padding: 40px; border-radius: 30px; background: var(--dark); color: white; }

        @media (max-width: 768px) {
            .hero { padding: 60px 15px; border-radius: 0 0 30px 30px; }
        }
    </style>
</head>
<body>

<section class="hero text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3 tracking-tight">Unlock a World of <span style="color: var(--primary);">Unlimited</span> Reading</h1>
        <p class="lead mb-5 text-white-50 mx-auto" style="max-width: 700px;">Join thousands of readers and access your favorite eBooks anywhere, anytime.</p>
        <form method="GET" action="index.php" class="d-flex justify-content-center">
            <div class="input-group mb-3" style="max-width: 600px; background: white; border-radius: 15px; padding: 5px;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control border-0 shadow-none px-4" placeholder="Search books...">
                <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 12px;"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>
</section>

<div class="container stats-bar">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="cat-icon mb-0 me-3"><i class="fa-solid fa-book"></i></div>
                <div><h4 class="fw-bold mb-0">12k+</h4><p class="text-muted small mb-0">Total EBooks</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="cat-icon mb-0 me-3"><i class="fa-solid fa-users"></i></div>
                <div><h4 class="fw-bold mb-0">45k+</h4><p class="text-muted small mb-0">Happy Readers</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="cat-icon mb-0 me-3"><i class="fa-solid fa-star"></i></div>
                <div><h4 class="fw-bold mb-0">4.9</h4><p class="text-muted small mb-0">Average Rating</p></div>
            </div>
        </div>
    </div>
</div>

<section class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-0"> Hall of Fame</h2>
            <p class="text-muted">Our latest competition champions</p>
        </div>
    </div>
    
    <div class="row g-4">
        <?php 
        // Sahi Column 'u.name' ke sath query
        $winner_query = "SELECT c.title as comp_title, c.prize, u.name 
                         FROM competitions c 
                         JOIN users u ON c.winner_id = u.id 
                         WHERE c.winner_id IS NOT NULL 
                         ORDER BY c.id DESC LIMIT 3";
        
        $winner_res = mysqli_query($conn, $winner_query);

        if($winner_res && mysqli_num_rows($winner_res) > 0):
            while($w = mysqli_fetch_assoc($winner_res)):
        ?>
        <div class="col-md-4">
            <div class="stat-card border-0 shadow-sm" style="background: linear-gradient(145deg, #ffffff, #f0f4ff); border-left: 5px solid #f1c40f !important; border-radius: 20px; padding: 25px;">
                <div class="d-flex align-items-center">
                    <div class="cat-icon bg-warning bg-opacity-10 text-warning me-3" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; min-width: 50px;">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($w['name']); ?></h6>
                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($w['comp_title']); ?></p>
                        <span class="badge bg-success bg-opacity-10 text-success mt-1" style="font-size: 0.7rem;">
                            Prize: ₹<?php echo htmlspecialchars($w['prize']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div class="col-12">
                <div class="p-4 text-center rounded-4 border border-dashed text-muted bg-white bg-opacity-50">
                    <i class="fa-solid fa-trophy mb-2 fa-2x"></i>
                    <p class="small mb-0">Winners will be announced soon! Stay tuned.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<div class="container my-5 pt-5" id="featured-section">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <h2 class="fw-bold mb-0">Featured Books</h2>
        <div class="btn-group shadow-sm rounded-pill p-1 bg-white">
            <a href="index.php?filter=all#featured-section" class="btn btn-sm rounded-pill px-3 <?php echo ($filter == 'all') ? 'btn-dark' : 'text-muted'; ?>">All</a>
            <a href="index.php?filter=new#featured-section" class="btn btn-sm rounded-pill px-3 <?php echo ($filter == 'new') ? 'btn-dark' : 'text-muted'; ?>">New</a>
            <a href="index.php?filter=free#featured-section" class="btn btn-sm rounded-pill px-3 <?php echo ($filter == 'free') ? 'btn-dark' : 'text-muted'; ?>">Free</a>
            <a href="index.php?filter=paid#featured-section" class="btn btn-sm rounded-pill px-3 <?php echo ($filter == 'paid') ? 'btn-dark' : 'text-muted'; ?>">Paid</a>
        </div>
    </div>

    <div class="row g-4 row-cols-2 row-cols-md-3 row-cols-lg-4">
        <?php
        $query = "SELECT * FROM books WHERE 1=1";
        if ($search) { $query .= " AND (title LIKE '%$search%' OR author LIKE '%$search%')"; }
        if ($filter == 'free') { $query .= " AND price <= 0"; } 
        elseif ($filter == 'paid') { $query .= " AND price > 0"; }
        $query .= ($filter == 'new') ? " ORDER BY id DESC LIMIT 12" : " ORDER BY id DESC";
        
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
                $isFree = ($row['price'] <= 0);
        ?>
        <div class="col d-flex">
            <div class="book-card w-100">
                <div class="book-img-wrapper">
                    <?php if($isFree): ?>
                        <div class="badge bg-success position-absolute" style="top:12px; right:12px; z-index:5; font-size: 0.7rem;">FREE</div>
                    <?php endif; ?>
                    <img src="<?php echo $image_path . $row['book_image']; ?>" class="book-img" alt="Cover">
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h6>
                    <p class="text-muted small mb-3 text-truncate"><?php echo htmlspecialchars($row['author']); ?></p>
                    
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold <?php echo $isFree ? 'text-success' : 'text-dark'; ?>">
                            <?php echo $isFree ? 'FREE' : '₹' . $row['price']; ?>
                        </span>
                        
                        <a href="<?php echo $isFree ? 'view_book.php?id='.$row['id'] : 'order.php?book_id='.$row['id']; ?>" 
                           class="buy-btn text-decoration-none">
                            <?php echo $isFree ? 'Read' : 'Buy'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-magnifying-glass fa-3x text-muted mb-3"></i>
                <h3 class="text-muted">No books found.</h3>
                <a href="index.php" class="btn btn-primary rounded-pill mt-3">Reset Filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<section class="container mb-5 pb-5">
    <div class="feature-box shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="display-6 fw-bold mb-4">Why choose EBook Library?</h2>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary"><i class="fa-solid fa-circle-check"></i></div>
                    <p class="mb-0">Instant access right after purchase.</p>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary"><i class="fa-solid fa-circle-check"></i></div>
                    <p class="mb-0">Read on any device - Phone or PC.</p>
                </div>
                <button class="btn btn-primary mt-4 px-4 py-2 rounded-pill fw-bold">Explore All</button>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=500&q=80" class="img-fluid rounded-4 shadow" alt="Reading">
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-5">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-5">What our readers say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded-4 h-100">
                    <div class="text-warning mb-2"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="small text-muted italic">"The best place to find niche tech books."</p>
                    <h6 class="fw-bold mb-0">- Rahul Verma</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 h-100">
                    <div class="text-warning mb-2"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="small text-muted italic">"So easy to buy and read. Love it!"</p>
                    <h6 class="fw-bold mb-0">- Priya Sharma</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 h-100">
                    <div class="text-warning mb-2"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="small text-muted italic">"Free section is a lifesaver for students."</p>
                    <h6 class="fw-bold mb-0">- Aman Gupta</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php")?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>