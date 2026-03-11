<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');
$today = date('Y-m-d');
$query = "SELECT * FROM competitions WHERE end_date >= '$today' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Competitions | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--gold-light:#e8c96a;--surface:#141920;--surface-2:#1c2333;--border:rgba(255,255,255,0.07);--muted:rgba(255,255,255,0.38);}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
.page-hero{background:#141920;border-bottom:1px solid var(--border);padding:60px 30px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 50% 0%,rgba(201,168,76,0.06) 0%,transparent 70%);pointer-events:none;}
.page-hero .eyebrow{font-size:0.62rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.page-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,3rem);font-weight:700;color:#fff;}
.page-hero p{font-size:0.82rem;color:var(--muted);margin-top:10px;}
.page-hero .back-btn{display:inline-flex;align-items:center;gap:8px;color:var(--muted);text-decoration:none;font-size:0.78rem;background:rgba(255,255,255,0.04);border:1px solid var(--border);padding:8px 16px;border-radius:5px;margin-bottom:24px;transition:all 0.2s;}
.page-hero .back-btn:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.comp-container{max-width:1200px;margin:0 auto;padding:56px 30px;}
.comp-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.comp-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:28px;display:flex;flex-direction:column;transition:all 0.25s;}
.comp-card:hover{border-color:rgba(201,168,76,0.3);transform:translateY(-4px);}
.live-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(74,124,89,0.12);border:1px solid rgba(74,124,89,0.25);color:#6abd7c;font-size:0.62rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 10px;border-radius:3px;margin-bottom:14px;}
.live-dot{width:5px;height:5px;background:#6abd7c;border-radius:50%;animation:pulse 1.5s infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.3;}}
.comp-title{font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#fff;margin-bottom:8px;}
.comp-desc{font-size:0.78rem;color:var(--muted);line-height:1.7;margin-bottom:20px;flex:1;}
.comp-meta{background:#1c2333;border-radius:6px;padding:14px;margin-bottom:20px;}
.meta-row{display:flex;align-items:center;gap:8px;font-size:0.76rem;color:var(--muted);margin-bottom:6px;}
.meta-row:last-child{margin-bottom:0;}
.meta-row i{color:var(--gold);width:12px;font-size:0.7rem;}
.meta-row strong{color:#fff;}
.btn-participate{background:var(--gold);color:#0d0d0d;border:none;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:12px;border-radius:6px;cursor:pointer;width:100%;font-family:'DM Sans',sans-serif;transition:background 0.2s;}
.btn-participate:hover{background:var(--gold-light);}
.empty-state{text-align:center;padding:80px 20px;grid-column:1/-1;}
.empty-state i{font-size:2.5rem;color:rgba(255,255,255,0.15);display:block;margin-bottom:16px;}
.empty-state h3{font-family:'Cormorant Garamond',serif;font-size:1.6rem;color:rgba(255,255,255,0.35);}

/* WRITING INTERFACE */
#writingInterface{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:#0d0d0d;z-index:1000;overflow-y:auto;}
.writing-wrap{max-width:860px;margin:0 auto;padding:40px 30px;}
.writing-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;padding-bottom:20px;border-bottom:1px solid var(--border);}
.writing-header h3{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#fff;}
#timer{font-size:3rem;font-weight:700;color:var(--gold);font-family:'Cormorant Garamond',serif;text-align:center;margin:0 0 28px;}
.word-badge{background:#1c2333;border:1px solid var(--border);color:var(--muted);font-size:0.72rem;padding:6px 14px;border-radius:5px;}
.essay-area{width:100%;background:#141920;border:1px solid var(--border);border-radius:8px;padding:20px;color:#fff;font-size:0.88rem;font-family:'DM Sans',sans-serif;line-height:1.8;resize:vertical;min-height:350px;outline:none;transition:border-color 0.2s;}
.essay-area:focus{border-color:rgba(201,168,76,0.35);}
.essay-area::placeholder{color:rgba(255,255,255,0.18);}
.btn-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px;}
.btn-cancel{background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--muted);padding:12px;border-radius:6px;font-size:0.8rem;font-weight:600;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all 0.2s;}
.btn-cancel:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.btn-submit-essay{background:#4a7c59;border:none;color:#fff;padding:12px;border-radius:6px;font-size:0.8rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background 0.2s;letter-spacing:0.05em;}
.btn-submit-essay:hover{background:#3d6b4a;}
@media(max-width:900px){.comp-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:600px){.comp-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<?php include("navbar.php"); ?>

<div class="page-hero">
  <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
  <span class="eyebrow">✦ Live Now</span>
  <h1>Available Competitions</h1>
  <p>Write your best essay and win exciting prizes</p>
</div>

<div class="comp-container" id="listView">
  <div class="comp-grid">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
      <div class="comp-card">
        <div><span class="live-badge"><span class="live-dot"></span> Live Now</span></div>
        <div class="comp-title"><?php echo htmlspecialchars($row['title']); ?></div>
        <p class="comp-desc">
          <?php echo !empty($row['description']) ? substr(htmlspecialchars($row['description']), 0, 120).'…' : 'Details coming soon.'; ?>
        </p>
        <div class="comp-meta">
          <div class="meta-row"><i class="fa-solid fa-trophy"></i> Prize: <strong><?php echo htmlspecialchars($row['prize']); ?></strong></div>
          <div class="meta-row"><i class="fa-regular fa-calendar-xmark"></i> Ends: <strong><?php echo date('d M, Y', strtotime($row['end_date'])); ?></strong></div>
        </div>
        <button onclick="openWritingArea(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>')" class="btn-participate">
          Participate Now →
        </button>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="fa-solid fa-hourglass-half"></i>
        <h3>No active competitions right now.</h3>
      </div>
    <?php endif; ?>
  </div>
</div>

<div id="writingInterface">
  <div class="writing-wrap">
    <div class="writing-header">
      <h3 id="displayTitle"></h3>
      <span class="word-badge"><span id="wordCount">0</span> Words</span>
    </div>
    <div id="timer">03:00:00</div>
    <form id="essayForm" method="POST" action="submit_essay.php">
      <input type="hidden" name="comp_id" id="comp_id_input">
      <textarea name="essay" id="essayText" class="essay-area" placeholder="Start writing your essay here…" onkeyup="countWords()" required></textarea>
      <div class="btn-row">
        <button type="button" onclick="location.reload()" class="btn-cancel">Cancel</button>
        <button type="submit" class="btn-submit-essay">Submit Final Essay ✓</button>
      </div>
    </form>
  </div>
</div>

<script>
var time = 10800; 
var timerInterval;
function openWritingArea(id, title) {
    document.getElementById("listView").style.display = "none";
    document.getElementById("writingInterface").style.display = "block";
    document.getElementById("displayTitle").innerText = title;
    document.getElementById("comp_id_input").value = id;
    timerInterval = setInterval(updateTimer, 1000);
}
function updateTimer() {
    var hours = Math.floor(time / 3600);
    var minutes = Math.floor((time % 3600) / 60);
    var seconds = time % 60;
    document.getElementById("timer").innerHTML = 
        (hours < 10 ? "0" + hours : hours) + ":" + 
        (minutes < 10 ? "0" + minutes : minutes) + ":" + 
        (seconds < 10 ? "0" + seconds : seconds);
    if (time <= 0) { clearInterval(timerInterval); document.getElementById("essayForm").submit(); }
    time--;
}
function countWords() {
    let text = document.getElementById("essayText").value.trim();
    let words = text ? text.split(/\s+/).length : 0;
    document.getElementById("wordCount").innerText = words;
}
</script>
</body>
</html>
