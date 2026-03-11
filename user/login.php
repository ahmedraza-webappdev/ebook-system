<?php
session_start();
include("../config/db.php");
$msg = "";
if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $sql      = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result   = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location:index.php");
        exit();
    } else {
        $msg = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --gold:#c9a84c;--gold-light:#e8c96a;
  --ink:#0d0d0d;--surface:#141920;--surface2:#1c2333;
  --border:rgba(255,255,255,0.08);--muted:rgba(255,255,255,0.38);
}
html,body{height:100%;}
body{
  background:var(--ink);color:#f0ece4;
  font-family:'DM Sans',sans-serif;
  display:flex;min-height:100vh;overflow:hidden;
}

/* ═══════════ LEFT PANEL ═══════════ */
.left-panel{
  width:46%;flex-shrink:0;position:relative;
  background:#06080b;overflow:hidden;
  display:flex;align-items:center;justify-content:center;
}

/* deep blue-gold glow — different from register's pure gold */
.left-panel::after{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 60% 50% at 20% 30%, rgba(99,102,241,0.09) 0%,transparent 60%),
    radial-gradient(ellipse 55% 45% at 85% 70%, rgba(201,168,76,0.1)  0%,transparent 60%),
    radial-gradient(ellipse 40% 30% at 50% 10%, rgba(201,168,76,0.06) 0%,transparent 50%);
  pointer-events:none;z-index:1;
}
/* grain */
.left-panel::before{
  content:'';position:absolute;inset:0;z-index:2;pointer-events:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
  background-size:200px;opacity:0.55;
}

/* big watermark */
.watermark{
  position:absolute;z-index:1;
  font-family:'Cormorant Garamond',serif;
  font-size:30vw;font-weight:700;
  color:rgba(255,255,255,0.016);
  line-height:1;top:50%;left:50%;
  transform:translate(-50%,-50%);
  pointer-events:none;user-select:none;
}

/* floating reading stats — different content vs register */
.stat-float{
  position:absolute;z-index:3;
  background:rgba(20,25,32,0.75);
  backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,0.07);
  border-radius:8px;padding:12px 16px;
  pointer-events:none;
  animation:floatStat var(--dur,16s) ease-in-out infinite;
  animation-delay:var(--dly,0s);
}
@keyframes floatStat{
  0%,100%{transform:translateY(0);opacity:0.7;}
  50%{transform:translateY(-10px);opacity:1;}
}
.stat-float .sf-num{
  font-family:'Cormorant Garamond',serif;
  font-size:1.5rem;font-weight:700;color:var(--gold);line-height:1;
}
.stat-float .sf-lbl{font-size:0.62rem;color:rgba(255,255,255,0.35);margin-top:2px;letter-spacing:0.06em;}

/* horizontal scrolling book titles */
.titles-ticker{
  position:absolute;z-index:3;width:100%;overflow:hidden;
  opacity:0.12;pointer-events:none;
}
.titles-ticker.top{top:18%;}
.titles-ticker.bottom{bottom:22%;}
.ticker-inner{
  display:flex;gap:40px;white-space:nowrap;
  font-family:'Cormorant Garamond',serif;
  font-style:italic;color:#fff;
  animation:tickerScroll 30s linear infinite;
}
.titles-ticker.bottom .ticker-inner{animation-direction:reverse;animation-duration:25s;}
@keyframes tickerScroll{from{transform:translateX(0);}to{transform:translateX(-50%);}}
.ticker-sep{color:var(--gold);margin:0 10px;}

/* panel inner */
.panel-inner{
  position:relative;z-index:4;
  padding:52px 48px;max-width:410px;width:100%;
}
.p-eyebrow{
  font-size:0.58rem;letter-spacing:0.28em;text-transform:uppercase;
  color:var(--gold);font-weight:700;margin-bottom:18px;
  display:flex;align-items:center;gap:10px;
}
.p-eyebrow::before{content:'';width:24px;height:1px;background:var(--gold);}
.p-title{
  font-family:'Cormorant Garamond',serif;
  font-size:clamp(2.1rem,3.2vw,2.9rem);
  font-weight:700;color:#fff;line-height:1.12;margin-bottom:14px;
}
.p-title em{color:var(--gold);font-style:italic;}
.p-desc{font-size:0.79rem;color:rgba(255,255,255,0.36);line-height:1.85;margin-bottom:34px;}

.p-sep{display:flex;align-items:center;gap:10px;margin-bottom:28px;}
.p-sep::before,.p-sep::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.06);}
.p-sep-dot{width:5px;height:5px;border-radius:50%;background:var(--gold);box-shadow:0 0 10px rgba(201,168,76,0.6);}

/* recent activity list */
.activity-list{list-style:none;}
.activity-list li{
  display:flex;align-items:center;gap:12px;
  padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);
  font-size:0.76rem;color:rgba(255,255,255,0.42);
}
.activity-list li:last-child{border-bottom:none;}
.av{
  width:28px;height:28px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:0.7rem;font-weight:700;color:#0d0d0d;
  background:linear-gradient(135deg,var(--gold),var(--gold-light));
}
.a-meta{flex:1;}
.a-name{font-size:0.74rem;color:rgba(255,255,255,0.6);font-weight:500;}
.a-action{font-size:0.66rem;color:rgba(255,255,255,0.28);}
.a-time{font-size:0.63rem;color:rgba(255,255,255,0.2);}

/* book shelf */
.spine-shelf{
  position:absolute;bottom:0;left:0;right:0;z-index:3;
  display:flex;align-items:flex-end;gap:3px;padding:0 20px;height:80px;overflow:hidden;
}
.sp{
  border-radius:2px 2px 0 0;flex-shrink:0;
  animation:spineRise 0.9s ease-out both;position:relative;overflow:hidden;
}
.sp::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,0.06) 0%,transparent 50%);}
.sp:nth-child(1){width:14px;height:48px;background:#0d1520;animation-delay:0.05s;}
.sp:nth-child(2){width:20px;height:65px;background:#1a1508;animation-delay:0.10s;}
.sp:nth-child(3){width:16px;height:55px;background:#08101a;animation-delay:0.15s;}
.sp:nth-child(4){width:24px;height:72px;background:linear-gradient(180deg,rgba(201,168,76,0.22),rgba(201,168,76,0.07));border:1px solid rgba(201,168,76,0.16);animation-delay:0.20s;}
.sp:nth-child(5){width:12px;height:42px;background:#150d20;animation-delay:0.25s;}
.sp:nth-child(6){width:18px;height:68px;background:#0d1a10;animation-delay:0.30s;}
.sp:nth-child(7){width:22px;height:58px;background:#1a0d0d;animation-delay:0.35s;}
.sp:nth-child(8){width:16px;height:80px;background:#0a0a15;animation-delay:0.40s;}
.sp:nth-child(9){width:20px;height:50px;background:#150a0a;animation-delay:0.45s;}
.sp:nth-child(10){width:14px;height:62px;background:#0a150a;animation-delay:0.50s;}
.sp:nth-child(11){width:26px;height:88px;background:linear-gradient(180deg,rgba(201,168,76,0.18),rgba(201,168,76,0.05));border:1px solid rgba(201,168,76,0.12);animation-delay:0.55s;}
.sp:nth-child(12){width:18px;height:46px;background:#1a1a0a;animation-delay:0.60s;}
.sp:nth-child(13){width:16px;height:70px;background:#0a1a1a;animation-delay:0.65s;}
.sp:nth-child(14){width:22px;height:55px;background:#1a0a1a;animation-delay:0.70s;}
.sp:nth-child(15){width:14px;height:75px;background:#08100d;animation-delay:0.75s;}
.sp:nth-child(16){width:20px;height:45px;background:#150f08;animation-delay:0.80s;}
@keyframes spineRise{from{transform:translateY(100%);}to{transform:translateY(0);}}

/* ═══════════ RIGHT PANEL ═══════════ */
.right-panel{
  flex:1;background:var(--ink);
  display:flex;align-items:center;justify-content:center;
  padding:40px 52px;overflow-y:auto;position:relative;
}
.right-panel::before{
  content:'';position:absolute;left:0;top:15%;bottom:15%;width:1px;
  background:linear-gradient(to bottom,transparent,rgba(201,168,76,0.18) 25%,rgba(201,168,76,0.18) 75%,transparent);
}

.form-wrap{width:100%;max-width:370px;}

/* topbar */
.r-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:40px;}
.r-brand{font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:700;color:#fff;text-decoration:none;}
.r-brand span{color:var(--gold);}
.r-register{
  font-size:0.72rem;color:var(--muted);text-decoration:none;
  display:inline-flex;align-items:center;gap:6px;
  border:1px solid var(--border);padding:6px 13px;border-radius:5px;
  transition:all 0.2s;
}
.r-register:hover{color:#fff;border-color:rgba(255,255,255,0.2);}

/* greeting area */
.r-eyebrow{font-size:0.58rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:9px;}
.r-title{font-family:'Cormorant Garamond',serif;font-size:2.1rem;font-weight:700;color:#fff;margin-bottom:5px;}
.r-sub{font-size:0.76rem;color:var(--muted);margin-bottom:28px;}

/* alerts */
.alert-ok{
  display:flex;align-items:center;gap:9px;
  background:rgba(74,124,89,0.07);border:1px solid rgba(74,124,89,0.18);
  color:#6abd7c;font-size:0.77rem;padding:11px 13px;border-radius:7px;margin-bottom:20px;
}
.alert-err{
  display:flex;align-items:center;gap:9px;
  background:rgba(224,92,92,0.07);border:1px solid rgba(224,92,92,0.18);
  color:#e05c5c;font-size:0.77rem;padding:11px 13px;border-radius:7px;margin-bottom:20px;
  animation:shake 0.4s ease;
}
@keyframes shake{0%,100%{transform:translateX(0);}20%,60%{transform:translateX(-6px);}40%,80%{transform:translateX(6px);}}

/* floating label inputs */
.fg{position:relative;margin-bottom:18px;}
.fg .fi{
  width:100%;background:var(--surface);
  border:1px solid var(--border);border-radius:8px;
  padding:21px 16px 8px 44px;
  color:#fff;font-size:0.85rem;font-family:'DM Sans',sans-serif;
  outline:none;transition:border-color 0.25s,box-shadow 0.25s;
}
.fg .fi:focus{
  border-color:rgba(201,168,76,0.42);
  box-shadow:0 0 0 3px rgba(201,168,76,0.06);
}
.fg .fi::placeholder{color:transparent;}
.fg .fl{
  position:absolute;left:44px;top:50%;transform:translateY(-50%);
  font-size:0.82rem;color:rgba(255,255,255,0.28);
  pointer-events:none;transition:all 0.22s ease;font-family:'DM Sans',sans-serif;
}
.fg .fi:focus ~ .fl,
.fg .fi:not(:placeholder-shown) ~ .fl{
  top:9px;transform:none;
  font-size:0.6rem;letter-spacing:0.12em;text-transform:uppercase;
  color:var(--gold);font-weight:700;
}
.fg .fic{
  position:absolute;left:15px;top:50%;transform:translateY(-50%);
  color:rgba(255,255,255,0.2);font-size:0.78rem;
  pointer-events:none;transition:color 0.22s;
}
.fg:focus-within .fic{color:var(--gold);}

/* password row extras */
.pass-row{display:flex;justify-content:flex-end;margin-bottom:8px;}
.forgot-link{font-size:0.68rem;color:rgba(255,255,255,0.3);text-decoration:none;transition:color 0.2s;letter-spacing:0.04em;}
.forgot-link:hover{color:var(--gold);}
.pwd-btn{
  position:absolute;right:14px;top:50%;transform:translateY(-50%);
  background:none;border:none;color:rgba(255,255,255,0.22);
  cursor:pointer;font-size:0.78rem;padding:4px;transition:color 0.2s;
}
.pwd-btn:hover{color:var(--gold);}

/* submit button */
.btn-go{
  width:100%;background:var(--gold);color:#0d0d0d;
  border:none;border-radius:8px;padding:14px;margin-top:6px;
  font-size:0.84rem;font-weight:700;font-family:'DM Sans',sans-serif;
  cursor:pointer;letter-spacing:0.05em;
  position:relative;overflow:hidden;
  transition:background 0.2s,transform 0.15s;
}
.btn-go::after{
  content:'';position:absolute;top:0;left:-110%;
  width:55%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,0.28),transparent);
  transition:left 0.55s;
}
.btn-go:hover{background:var(--gold-light);transform:translateY(-1px);}
.btn-go:hover::after{left:150%;}
.btn-go:active{transform:translateY(0);}

/* loading state */
.btn-go .btn-text{transition:opacity 0.2s;}
.btn-go .btn-loader{display:none;position:absolute;inset:0;align-items:center;justify-content:center;}
.btn-go.loading .btn-text{opacity:0;}
.btn-go.loading .btn-loader{display:flex;}
.spin{width:18px;height:18px;border:2px solid rgba(0,0,0,0.2);border-top-color:#0d0d0d;border-radius:50%;animation:spin 0.7s linear infinite;}
@keyframes spin{to{transform:rotate(360deg);}}

/* divider */
.divider{display:flex;align-items:center;gap:12px;margin:20px 0;font-size:0.68rem;color:rgba(255,255,255,0.18);}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}

/* quick links row */
.quick-links{display:flex;gap:8px;margin-bottom:0;}
.ql{
  flex:1;display:flex;align-items:center;justify-content:center;gap:7px;
  background:var(--surface);border:1px solid var(--border);border-radius:7px;
  padding:10px;font-size:0.72rem;color:var(--muted);text-decoration:none;
  transition:all 0.2s;
}
.ql i{font-size:0.8rem;}
.ql:hover{border-color:rgba(201,168,76,0.25);color:#fff;}

.r-foot{text-align:center;margin-top:20px;font-size:0.76rem;color:var(--muted);}
.r-foot a{color:var(--gold);font-weight:600;text-decoration:none;margin-left:4px;}
.r-foot a:hover{color:var(--gold-light);}

/* mobile */
@media(max-width:860px){
  body{flex-direction:column;overflow:auto;}
  .left-panel{width:100%;min-height:200px;}
  .panel-inner{padding:28px 24px;}
  .right-panel{padding:28px 24px;}
  .right-panel::before{display:none;}
  .spine-shelf{height:55px;}
  .watermark{font-size:18vw;}
  .titles-ticker{display:none;}
  .stat-float{display:none;}
}
</style>
</head>
<body>

<!-- ═══ LEFT PANEL ═══ -->
<div class="left-panel">

  <div class="watermark">L</div>

  <!-- scrolling book title tickers -->
  <div class="titles-ticker top">
    <div class="ticker-inner">
      <span>The Great Gatsby</span><span class="ticker-sep">✦</span>
      <span>Sapiens</span><span class="ticker-sep">✦</span>
      <span>Atomic Habits</span><span class="ticker-sep">✦</span>
      <span>1984</span><span class="ticker-sep">✦</span>
      <span>Think and Grow Rich</span><span class="ticker-sep">✦</span>
      <span>Ikigai</span><span class="ticker-sep">✦</span>
      <span>Rich Dad Poor Dad</span><span class="ticker-sep">✦</span>
      <span>The Alchemist</span><span class="ticker-sep">✦</span>
      <span>The Great Gatsby</span><span class="ticker-sep">✦</span>
      <span>Sapiens</span><span class="ticker-sep">✦</span>
      <span>Atomic Habits</span><span class="ticker-sep">✦</span>
      <span>1984</span><span class="ticker-sep">✦</span>
      <span>Think and Grow Rich</span><span class="ticker-sep">✦</span>
      <span>Ikigai</span><span class="ticker-sep">✦</span>
      <span>Rich Dad Poor Dad</span><span class="ticker-sep">✦</span>
      <span>The Alchemist</span><span class="ticker-sep">✦</span>
    </div>
  </div>
  <div class="titles-ticker bottom">
    <div class="ticker-inner">
      <span>Brave New World</span><span class="ticker-sep">✦</span>
      <span>Deep Work</span><span class="ticker-sep">✦</span>
      <span>Zero to One</span><span class="ticker-sep">✦</span>
      <span>The 48 Laws of Power</span><span class="ticker-sep">✦</span>
      <span>Meditations</span><span class="ticker-sep">✦</span>
      <span>Pride and Prejudice</span><span class="ticker-sep">✦</span>
      <span>Brave New World</span><span class="ticker-sep">✦</span>
      <span>Deep Work</span><span class="ticker-sep">✦</span>
      <span>Zero to One</span><span class="ticker-sep">✦</span>
      <span>The 48 Laws of Power</span><span class="ticker-sep">✦</span>
      <span>Meditations</span><span class="ticker-sep">✦</span>
      <span>Pride and Prejudice</span><span class="ticker-sep">✦</span>
    </div>
  </div>

  <!-- floating stat cards -->
  <div class="stat-float" style="top:12%;left:8%;--dur:18s;--dly:0s;">
    <div class="sf-num">12k+</div>
    <div class="sf-lbl">Books Available</div>
  </div>
  <div class="stat-float" style="top:14%;right:6%;--dur:15s;--dly:4s;">
    <div class="sf-num">45k+</div>
    <div class="sf-lbl">Happy Readers</div>
  </div>
  <div class="stat-float" style="bottom:28%;right:7%;--dur:20s;--dly:8s;">
    <div class="sf-num">4.9 ★</div>
    <div class="sf-lbl">Average Rating</div>
  </div>

  <div class="panel-inner">
    <div class="p-eyebrow">Welcome Back</div>
    <h1 class="p-title">Continue your<br><em>reading</em><br>journey.</h1>
    <p class="p-desc">Sign back in and pick up right where you left off. Your library is waiting.</p>

    <div class="p-sep"><div class="p-sep-dot"></div></div>

    <!-- recent readers activity -->
    <ul class="activity-list">
      <li>
        <div class="av">A</div>
        <div class="a-meta">
          <div class="a-name">Ahmed R.</div>
          <div class="a-action">Just finished <em style="color:rgba(255,255,255,0.4);">Atomic Habits</em></div>
        </div>
        <div class="a-time">2m ago</div>
      </li>
      <li>
        <div class="av" style="background:linear-gradient(135deg,#4a7c59,#6abd7c);">S</div>
        <div class="a-meta">
          <div class="a-name">Sara K.</div>
          <div class="a-action">Won the essay competition</div>
        </div>
        <div class="a-time">1h ago</div>
      </li>
      <li>
        <div class="av" style="background:linear-gradient(135deg,#6366f1,#818cf8);">M</div>
        <div class="a-meta">
          <div class="a-name">Maria T.</div>
          <div class="a-action">Started reading <em style="color:rgba(255,255,255,0.4);">Sapiens</em></div>
        </div>
        <div class="a-time">3h ago</div>
      </li>
    </ul>
  </div>

  <!-- spine shelf -->
  <div class="spine-shelf">
    <div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div>
  </div>
</div>

<!-- ═══ RIGHT PANEL ═══ -->
<div class="right-panel">
  <div class="form-wrap">

    <div class="r-topbar">
      <a href="index.php" class="r-brand">📚 <span>E-Library</span></a>
      <a href="register.php" class="r-register"><i class="fa-solid fa-user-plus"></i> Register</a>
    </div>

    <div class="r-eyebrow">✦ Member Access</div>
    <h2 class="r-title">Welcome Back</h2>
    <p class="r-sub">Sign in to your account to continue reading</p>

    <?php if(isset($_SESSION['success_msg'])): ?>
    <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
    <?php endif; ?>
    <?php if($msg != ""): ?>
    <div class="alert-err"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm">

      <!-- Email -->
      <div class="fg">
        <i class="fic fa-regular fa-envelope"></i>
        <input type="email" name="email" id="fe" class="fi" placeholder="Email Address" required autocomplete="email">
        <label class="fl" for="fe">Email Address</label>
      </div>

      <!-- Password -->
      <div class="fg">
        <i class="fic fa-solid fa-lock"></i>
        <input type="password" name="password" id="fp" class="fi" placeholder="Password" required autocomplete="current-password">
        <label class="fl" for="fp">Password</label>
        <button type="button" class="pwd-btn" onclick="toggleP()">
          <i class="fa-regular fa-eye" id="eyeIco"></i>
        </button>
      </div>

      <div class="pass-row">
        <a href="#" class="forgot-link">Forgot password?</a>
      </div>

      <button type="submit" name="login" class="btn-go" id="loginBtn">
        <span class="btn-text">Sign In →</span>
        <span class="btn-loader"><div class="spin"></div></span>
      </button>

    </form>

    <div class="divider">or explore first</div>

    <div class="quick-links">
      <a href="index.php" class="ql"><i class="fa-solid fa-house"></i> Home</a>
      <a href="index.php?filter=free" class="ql"><i class="fa-solid fa-book-open"></i> Free Books</a>
      <a href="competition.php" class="ql"><i class="fa-solid fa-trophy"></i> Compete</a>
    </div>

    <div class="r-foot">New to E-Library?<a href="register.php">Create Account</a></div>
  </div>
</div>

<script>
/* password show/hide */
function toggleP(){
  var i=document.getElementById('fp');
  var ic=document.getElementById('eyeIco');
  i.type=i.type==='password'?'text':'password';
  ic.classList.toggle('fa-eye');
  ic.classList.toggle('fa-eye-slash');
}

/* loading state on submit */
document.getElementById('loginForm').addEventListener('submit',function(){
  var btn=document.getElementById('loginBtn');
  btn.classList.add('loading');
  btn.disabled=true;
  setTimeout(function(){
    btn.classList.remove('loading');
    btn.disabled=false;
  }, 4000);
});
</script>
</body>
</html>