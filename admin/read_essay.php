<?php
$file = $_GET['file'];
$path = "../uploads/essays/" . $file;

if (!file_exists($path)) {
    die("Error: File not found.");
}

$content = file_get_contents($path);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reading Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e2e8f0; padding: 50px 0; }
        .essay-paper {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 60px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 5px;
            min-height: 100vh;
            white-space: pre-wrap; /* Lines break automatically */
            font-size: 1.2rem;
            line-height: 1.8;
            font-family: 'Georgia', serif;
        }
    </style>
</head>
<body>
    <div class="container text-center mb-4">
        <a href="view_submissions.php" class="btn btn-dark btn-sm">← Back to List</a>
    </div>
    <div class="essay-paper">
        <?= htmlspecialchars($content) ?>
    </div>
</body>
</html>