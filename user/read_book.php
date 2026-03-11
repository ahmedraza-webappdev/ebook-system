<?php
include("../config/db.php");
$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($row['title']); ?> | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--border:rgba(255,255,255,0.07);}
body{background:#0d0d0d;margin:0;padding:0;overflow:hidden;font-family:'DM Sans',sans-serif;}
.reader-bar{background:#141920;border-bottom:1px solid var(--border);height:58px;display:flex;align-items:center;justify-content:space-between;padding:0 20px;}
.reader-left{display:flex;align-items:center;gap:12px;}
.btn-back{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:rgba(255,255,255,0.5);font-size:0.75rem;font-weight:600;padding:7px 13px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-back:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.reader-title{font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:700;color:#fff;}
.free-badge{background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.2);color:#6abd7c;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:5px 10px;border-radius:3px;}
.pdf-viewer{width:100%;height:calc(100vh - 58px);border:none;background:#1c2333;}
</style>
</head>
<body>
<div class="reader-bar">
  <div class="reader-left">
    <a href="javascript:history.back()" class="btn-back"><i class="fa-solid fa-chevron-left"></i> Back</a>
    <div class="reader-title"><?php echo htmlspecialchars($row['title']); ?></div>
  </div>
  <span class="free-badge">Free Book</span>
</div>
<iframe src="../uploads/pdf/<?php echo $row['pdf_file']; ?>#toolbar=0" class="pdf-viewer" type="application/pdf"></iframe>
</body>
</html>
