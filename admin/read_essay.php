<?php
$file = $_GET['file'];
$path = "../uploads/essays/" . $file;
if (!file_exists($path)) { die("Error: File not found."); }
$content = file_get_contents($path);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reading Submission</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root{--ink:#0d0d0d;--ink-soft:#111827;--surface:#1c2333;--border:rgba(255,255,255,0.07);--gold:#c9a84c;--text:#f0ece4;--muted:rgba(255,255,255,0.35);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{background:var(--ink-soft);font-family:'DM Sans',sans-serif;min-height:100vh;padding:36px 20px;}
        .back-bar{max-width:700px;margin:0 auto 22px;}
        .back-btn{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.05);border:1px solid var(--border);color:var(--muted);text-decoration:none;font-size:0.75rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;padding:7px 14px;border-radius:4px;transition:all 0.2s;}
        .back-btn:hover{color:#fff;border-color:var(--gold);}
        .essay-paper{max-width:700px;margin:0 auto;background:var(--surface);border:1px solid var(--border);border-radius:7px;padding:52px;position:relative;}
        .essay-paper::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;background:linear-gradient(to bottom,var(--gold),transparent);border-radius:7px 0 0 7px;}
        .essay-file{font-size:0.62rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:22px;display:block;}
        .essay-content{font-family:'Cormorant Garamond',Georgia,serif;font-size:1.12rem;line-height:1.9;color:rgba(240,236,228,0.82);white-space:pre-wrap;}
    </style>
</head>
<body>
    <div class="back-bar">
        <a href="view_submissions.php" class="back-btn"><i style="font-size:0.7rem;">←</i> Back to List</a>
    </div>
    <div class="essay-paper">
        <span class="essay-file">📄 <?php echo htmlspecialchars($file); ?></span>
        <div class="essay-content"><?php echo htmlspecialchars($content); ?></div>
    </div>
</body>
</html>
