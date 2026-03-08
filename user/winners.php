<?php
include("config/db.php");

// Query to get competitions with winners
$query = "SELECT c.*, u.username as winner_name 
          FROM competitions c 
          JOIN users u ON c.winner_id = u.id 
          WHERE c.winner_id IS NOT NULL 
          ORDER BY c.end_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Champions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background: #0f172a; color: white; padding: 50px 0; }
        .winner-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: 0.3s;
        }
        .winner-card:hover { transform: translateY(-10px); background: rgba(255,255,255,0.1); }
        .prize-tag { color: #f1c40f; font-weight: bold; }
        .trophy { font-size: 40px; color: #f1c40f; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center fw-bold mb-5 animate__animated animate__fadeInDown">🏆 Wall of Fame</h1>
    <div class="row g-4">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4">
            <div class="winner-card animate__animated animate__fadeInUp">
                <div class="trophy">🥇</div>
                <h4 class="mb-1"><?= htmlspecialchars($row['winner_name']) ?></h4>
                <p class="text-white-50 small mb-2"><?= $row['title'] ?></p>
                <div class="prize-tag">Winner of <?= $row['prize'] ?></div>
                <hr class="opacity-10">
                <small class="text-muted">Dated: <?= date('M Y', strtotime($row['end_date'])) ?></small>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>