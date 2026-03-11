<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("navbar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Placed | E-Library</title>
<style>
.success-page{min-height:70vh;display:flex;align-items:center;justify-content:center;padding:60px 30px;}
.success-wrap{max-width:480px;text-align:center;}
.check-icon{width:80px;height:80px;background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.25);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 28px;font-size:2rem;}
.success-wrap h1{font-family:'Cormorant Garamond',serif;font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:10px;}
.success-wrap p{font-size:0.82rem;color:rgba(255,255,255,0.38);margin-bottom:28px;line-height:1.7;}
.order-box{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:20px;margin-bottom:28px;}
.order-id{font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;color:var(--gold);}
.order-box p{font-size:0.76rem;color:rgba(255,255,255,0.3);margin:6px 0 0;}
.btn-explore{display:inline-block;background:var(--gold);color:#0d0d0d;font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:13px 32px;border-radius:6px;text-decoration:none;transition:background 0.2s;}
.btn-explore:hover{background:var(--gold-light);}
</style>
</head>
<body>
<div class="success-page">
  <div class="success-wrap">
    <div class="check-icon">✓</div>
    <h1>Thank You!</h1>
    <p>Your order has been placed successfully.<br>We'll process it soon and keep you updated.</p>
    <div class="order-box">
      <div class="order-id">#ORD-<?php echo $_SESSION['last_order_id'] ?? '---'; ?></div>
      <p>A confirmation has been saved to your account.</p>
    </div>
    <a href="index.php" class="btn-explore">Back to Explore</a>
  </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
