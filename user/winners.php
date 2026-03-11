<?php
include("../config/db.php");
$query = "SELECT c.*, u.name as winner_name 
          FROM competitions c 
          JOIN users u ON c.winner_id = u.id 
          WHERE c.winner_id IS NOT NULL 
          ORDER BY c.end_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Wall of Fame | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--gold-light:#e8c96a;--border:rgba(255,255,255,0.07);--muted:rgba(255,255,255,0.38);}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
.page-hero{background:#141920;border-bottom:1px solid var(--border);padding:60px 30px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 80% at 50% 0%,rgba(201,168,76,0.07) 0%,transparent 70%);pointer-events:none;}
.page-hero .eyebrow{font-size:0.62rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.page-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,3.2rem);font-weight:700;color:#fff;}
.page-hero p{font-size:0.82rem;color:var(--muted);margin-top:10px;}
.wf-container{max-width:1200px;margin:0 auto;padding:60px 30px;}
.wf-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.wcard{background:#141920;border:1px solid var(--border);border-radius:10px;padding:30px;text-align:center;transition:all 0.25s;position:relative;overflow:hidden;}
.wcard::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);opacity:0;}
.wcard:hover{border-color:rgba(201,168,76,0.3);transform:translateY(-5px);}
.wcard:hover::before{opacity:1;}
.trophy-icon{font-size:2.2rem;margin-bottom:16px;display:block;}
.winner-name{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:#fff;margin-bottom:5px;}
.comp-title{font-size:0.78rem;color:var(--muted);margin-bottom:14px;}
.prize-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:5px 12px;border-radius:3px;margin-bottom:14px;}
.win-date{font-size:0.68rem;color:rgba(255,255,255,0.2);letter-spacing:0.06em;}
.empty-state{text-align:center;padding:80px 20px;grid-column:1/-1;}
.empty-state i{font-size:2.5rem;color:rgba(255,255,255,0.12);display:block;margin-bottom:16px;}
.empty-state h3{font-family:'Cormorant Garamond',serif;font-size:1.6rem;color:rgba(255,255,255,0.3);}
@media(max-width:900px){.wf-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:600px){.wf-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="page-hero">
  <span class="eyebrow">✦ Champions</span>
  <h1>🏆 Wall of Fame</h1>
  <p>Celebrating the best essay writers of every competition</p>
</div>
<div class="wf-container">
  <div class="wf-grid">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
      <div class="wcard">
        <span class="trophy-icon">🥇</span>
        <div class="winner-name"><?php echo htmlspecialchars($row['winner_name']); ?></div>
        <div class="comp-title"><?php echo htmlspecialchars($row['title']); ?></div>
        <div class="prize-badge"><i class="fa-solid fa-trophy"></i> <?php echo htmlspecialchars($row['prize']); ?></div>
        <div class="win-date"><?php echo date('F Y', strtotime($row['end_date'])); ?></div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="fa-solid fa-trophy"></i>
        <h3>Winners coming soon…</h3>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
