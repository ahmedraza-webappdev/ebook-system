<?php
session_start();
include("../config/db.php");

// 1. Timezone set karein
date_default_timezone_set('Asia/Karachi');
$today = date('Y-m-d');

// 2. Query: Active competitions
$query = "SELECT * FROM competitions WHERE end_date >= '$today' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Competitions | Elite Essay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --accent: #6366f1; --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        body { background: var(--bg-gradient); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: #f8fafc; padding: 40px 20px; }
        
        /* Back Button Style for List View */
        .list-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            margin-bottom: 20px;
            background: rgba(255,255,255,0.05);
            padding: 10px 20px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .list-back-btn:hover { color: white; background: rgba(255,255,255,0.1); border-color: var(--accent); }

        .comp-list-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            transition: 0.3s;
            height: 100%;
        }
        .comp-list-card:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.07); border-color: var(--accent); }
        #writingInterface { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: var(--bg-gradient); z-index: 1000; overflow-y: auto; padding: 20px; }
        .writing-container { max-width: 850px; margin: 40px auto; background: rgba(255,255,255,0.03); padding: 40px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); }
        #timer { font-size: 60px; font-weight: 800; text-align: center; color: #818cf8; font-family: monospace; margin: 20px 0; }
        .form-control { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); color: white; border-radius: 15px; padding: 15px; }
        .btn-participate { background: var(--accent); border: none; font-weight: 600; width: 100%; padding: 12px; border-radius: 12px; color: white; transition: 0.3s; }
        .btn-participate:hover { background: #4f46e5; box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
    </style>
</head>
<body>

<div class="container" id="listView">
    <div class="animate__animated animate__fadeIn">
        <a href="index.php" class="list-back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <h1 class="text-center fw-bold mb-5 animate__animated animate__fadeIn">🏆 Available Competitions</h1>
    
    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 animate__animated animate__fadeInUp">
                    <div class="comp-list-card d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-primary mb-2">Live Now</span>
                            <h4 class="fw-bold text-white"><?= htmlspecialchars($row['title']) ?></h4>
                            <p class="text-white-50 small">
                                <?= !empty($row['description']) ? substr(htmlspecialchars($row['description']), 0, 120) . '...' : 'Details coming soon.' ?>
                            </p>
                            <div class="mb-4 bg-white bg-opacity-5 p-3 rounded-3">
                                <div class="small mb-1"><i class="fas fa-trophy text-warning me-2"></i>Prize: <b class="text-white"><?= htmlspecialchars($row['prize']) ?></b></div>
                                <div class="small text-info"><i class="fas fa-calendar-times me-2"></i>Ends: <?= date('d M, Y', strtotime($row['end_date'])) ?></div>
                            </div>
                        </div>
                        <button onclick="openWritingArea(<?= $row['id'] ?>, '<?= addslashes($row['title']) ?>')" class="btn btn-participate">
                            Participate Now
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-hourglass-half fa-3x text-white-50 mb-3"></i>
                <h3 class="text-white-50">No active competitions right now.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="writingInterface">
    <div class="writing-container animate__animated animate__zoomIn">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 id="displayTitle" class="fw-bold m-0 text-white"></h3>
            <span class="badge bg-secondary p-2"><span id="wordCount">0</span> Words</span>
        </div>
        <div id="timer">03:00:00</div>
        
        <form id="essayForm" method="POST" action="submit_essay.php">
            <input type="hidden" name="comp_id" id="comp_id_input">
            
            <textarea name="essay" id="essayText" class="form-control mb-4" style="height: 350px;" 
                placeholder="Start writing your essay here..." onkeyup="countWords()" required></textarea>
            
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" onclick="location.reload()" class="btn btn-outline-light w-100">Cancel</button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-success w-100 fw-bold">Submit Final Essay</button>
                </div>
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
        
        if (time <= 0) { 
            clearInterval(timerInterval); 
            document.getElementById("essayForm").submit(); 
        }
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