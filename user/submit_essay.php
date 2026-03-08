<?php
session_start();
include("../config/db.php"); // Database connection

if (!isset($_SESSION['user_id'])) {
    die("Access Denied! Please login.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    
    // YAHAN FIX KIYA HAI: Ab ye hardcoded 1 nahi hai.
    // $_POST['comp_id'] se dynamic ID aayegi.
    $competition_id = isset($_POST['comp_id']) ? mysqli_real_escape_string($conn, $_POST['comp_id']) : 1; 
    
    // Essay content ko sanitize karna zaroori hai
    $essay_content = mysqli_real_escape_string($conn, $_POST['essay']);

    // 1. Essay ko file mein convert karna
    $filename = "essay_" . $user_id . "_" . time() . ".txt";
    $target_dir = "../uploads/essays/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_path = $target_dir . $filename;

    // File ke andar asli essay content likhna (yahan sanitize wala nahi, original content)
    $original_essay_content = $_POST['essay'];

    if (file_put_contents($file_path, $original_essay_content)) {
        
        // 2. Database mein record insert karna
        $query = "INSERT INTO submissions (user_id, competition_id, file, submitted_at) 
                  VALUES ('$user_id', '$competition_id', '$filename', NOW())";

        if (mysqli_query($conn, $query)) {
            // Success Message UI
            ?>
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset="UTF-8">
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                <style>
                    body { background: #0f172a; height: 100vh; display: flex; align-items: center; justify-content: center; color: white; font-family: 'Segoe UI', sans-serif; }
                    .card { background: #1e293b; border-radius: 20px; padding: 40px; text-align: center; border: 1px solid #334155; max-width: 500px; }
                    .icon-box { font-size: 50px; color: #10b981; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class='card shadow-lg'>
                    <div class="icon-box">✓</div>
                    <h1 class='text-success mb-3'>Submission Received</h1>
                    <p class='text-white-50'>Your essay has been successfully submitted for the competition.</p>
                    <p class="small text-muted">File: <?= $filename ?></p>
                    <a href='index.php' class='btn btn-primary px-5 py-2 mt-3 shadow-sm'>Return to Home</a>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "File Error: Could not save the essay file. Check folder permissions.";
    }
}
?>