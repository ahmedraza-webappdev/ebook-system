<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
$is_in_user = (basename(dirname($_SERVER['PHP_SELF'])) == 'user');
$root = $is_in_user ? '../' : './';
$user_dir = $is_in_user ? '' : 'user/';
?>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
:root{
  --ink:#0d0d0d;--surface:#141920;--surface-2:#1c2333;
  --border:rgba(255,255,255,0.07);--gold:#c9a84c;--gold-light:#e8c96a;
  --sage:#4a7c59;--rose:#e05c5c;--muted:rgba(255,255,255,0.38);
  --text:#f0ece4;--cream:#faf7f2;
}
.e-nav{background:rgba(13,13,13,0.97);border-bottom:1px solid var(--border);padding:0;position:sticky;top:0;z-index:999;backdrop-filter:blur(12px);}
.e-nav .nav-inner{max-width:1200px;margin:0 auto;padding:0 30px;display:flex;align-items:center;justify-content:space-between;height:64px;}
.e-nav .brand{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:#fff;text-decoration:none;letter-spacing:0.03em;display:flex;align-items:center;gap:8px;}
.e-nav .brand span{color:var(--gold);}
.e-nav .nav-links{display:flex;align-items:center;gap:2px;}
.e-nav .nav-links a{color:var(--muted);text-decoration:none;font-size:0.78rem;font-weight:500;padding:8px 14px;border-radius:5px;transition:all 0.2s;letter-spacing:0.05em;}
.e-nav .nav-links a:hover{color:#fff;background:rgba(255,255,255,0.05);}
.e-nav .nav-right{display:flex;align-items:center;gap:10px;}
.e-nav .btn-comp{display:inline-flex;align-items:center;gap:6px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);color:var(--gold);font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:7px 15px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.e-nav .btn-comp:hover{background:rgba(201,168,76,0.2);color:var(--gold-light);}
.e-nav .user-wrap{position:relative;}
.e-nav .user-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.05);border:1px solid var(--border);color:#fff;font-size:0.78rem;font-weight:500;padding:7px 14px;border-radius:5px;cursor:pointer;text-decoration:none;transition:all 0.2s;}
.e-nav .user-btn:hover{background:rgba(255,255,255,0.09);}
.e-nav .dropdown-menu{position:absolute;top:calc(100% + 10px);right:0;background:#1c2333;border:1px solid var(--border);border-radius:8px;min-width:175px;padding:6px;display:none;box-shadow:0 24px 48px rgba(0,0,0,0.5);}
.e-nav .user-wrap:hover .dropdown-menu{display:block;}
.e-nav .dropdown-menu a{display:flex;align-items:center;gap:9px;color:rgba(255,255,255,0.55);text-decoration:none;font-size:0.78rem;padding:9px 11px;border-radius:5px;transition:all 0.2s;}
.e-nav .dropdown-menu a:hover{background:rgba(255,255,255,0.05);color:#fff;}
.e-nav .dropdown-menu a.logout-link{color:rgba(224,92,92,0.7);}
.e-nav .dropdown-menu a.logout-link:hover{color:#e05c5c;background:rgba(224,92,92,0.07);}
.e-nav .menu-divider{height:1px;background:var(--border);margin:4px 0;}
.e-nav .btn-login{color:var(--muted);text-decoration:none;font-size:0.78rem;font-weight:500;padding:8px 13px;transition:color 0.2s;}
.e-nav .btn-login:hover{color:#fff;}
.e-nav .btn-join{background:var(--gold);color:#0d0d0d;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:9px 20px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.e-nav .btn-join:hover{background:var(--gold-light);}
.e-nav .mob-toggle{display:none;background:none;border:1px solid var(--border);color:#fff;padding:7px 10px;border-radius:5px;cursor:pointer;}
.e-nav .mob-menu{display:none;background:#0d0d0d;border-top:1px solid var(--border);padding:16px 20px 20px;}
.e-nav .mob-menu a{display:block;color:var(--muted);text-decoration:none;padding:10px 0;font-size:0.84rem;border-bottom:1px solid rgba(255,255,255,0.04);}
.e-nav .mob-menu a:hover{color:#fff;}
@media(max-width:900px){.e-nav .nav-links{display:none;}.e-nav .btn-comp{display:none;}.e-nav .mob-toggle{display:block;}}
</style>

<nav class="e-nav">
  <div class="nav-inner">
    <a href="index.php" class="brand">📚 <span>E-Library</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="index.php?filter=free">Free Books</a>
      <a href="competition.php">Competitions</a>
      <a href="winners.php">Winners</a>
      <a href="about.php">About Us</a>
    </div>
    <div class="nav-right">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="competition.php" class="btn-comp"><i class="fa-solid fa-trophy"></i> Compete</a>
        <div class="user-wrap">
          <div class="user-btn">
            <i class="fa-solid fa-circle-user" style="color:var(--gold);"></i>
            Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?>
            <i class="fa-solid fa-chevron-down" style="font-size:0.5rem;opacity:0.4;"></i>
          </div>
          <div class="dropdown-menu">
            <a href="books.php"><i class="fa-solid fa-book-open"></i> My Books</a>
            <div class="menu-divider"></div>
            <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-join">Join Now</a>
      <?php endif; ?>
      <button class="mob-toggle" onclick="this.closest('.e-nav').querySelector('.mob-menu').classList.toggle('open')">
        <i class="fa-solid fa-bars" style="font-size:0.8rem;"></i>
      </button>
    </div>
  </div>
  <div class="mob-menu" id="mobMenu">
    <a href="index.php">Home</a>
    <a href="index.php?filter=free">Free Books</a>
    <a href="competition.php">Competitions</a>
    <a href="winners.php">Winners</a>
    <a href="about.php">About Us</a>
    <?php if(isset($_SESSION['user_id'])): ?>
    <a href="books.php">My Books</a>
    <a href="logout.php" style="color:#e05c5c;">Logout</a>
    <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Join Now</a>
    <?php endif; ?>
  </div>
</nav>
