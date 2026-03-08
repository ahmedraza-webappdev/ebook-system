<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Query check
$query = "SELECT books.* FROM books 
          LEFT JOIN orders ON books.id = orders.book_id AND orders.user_id = '$user_id'
          WHERE books.id = '$book_id' 
          AND (books.price <= 0 OR orders.id IS NOT NULL)";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $book = mysqli_fetch_assoc($result);
    $pdf_name = $book['pdf_file']; 
    $pdf_file = "../uploads/pdf/" . $pdf_name; 
} else {
    echo "<script>alert('Access Denied!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading | <?php echo htmlspecialchars($book['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background: #0f172a; margin: 0; padding: 0; overflow: hidden; }
        .reader-header { 
            background: #1e293b; color: white; padding: 10px 20px; 
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            height: 60px;
        }
        .pdf-viewer-container {
            height: calc(100vh - 60px); 
            width: 100%;
            background: #334155;
        }
        iframe { width: 100%; height: 100%; border: none; }
        .btn-download { background: #6366f1; color: white !important; border: none; font-weight: 600; }
        .btn-download:hover { background: #4f46e5; }
    </style>
</head>
<body>

<div class="reader-header">
    <div class="d-flex align-items-center">
        <a href="books.php" class="btn btn-outline-light btn-sm me-3">
            <i class="fa-solid fa-chevron-left"></i> Back
        </a>
        <div class="d-none d-md-block">
            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($book['title']); ?></h6>
        </div>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <?php if(file_exists($pdf_file)): ?>
            <a href="<?php echo $pdf_file; ?>" download="<?php echo htmlspecialchars($book['title']); ?>.pdf" class="btn btn-sm btn-download">
                <i class="fa-solid fa-download me-1"></i> Download PDF
            </a>
        <?php endif; ?>
        
        <span class="badge bg-success d-none d-sm-inline-block">
            <i class="fa-solid fa-check-circle"></i> Authorized
        </span>
    </div>
</div>

<div class="pdf-viewer-container">
    <?php if(!empty($pdf_name) && file_exists($pdf_file)): ?>
        <iframe src="<?php echo $pdf_file; ?>#toolbar=0" type="application/pdf"></iframe>
    <?php else: ?>
        <div class="container text-center pt-5 text-white">
            <i class="fa-solid fa-file-circle-exclamation fa-4x text-warning mb-3"></i>
            <h3>File Not Found</h3>
            <p class="text-white-50">System Path: <?php echo $pdf_file; ?></p>
            <a href="books.php" class="btn btn-primary mt-3">Back to Library</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>