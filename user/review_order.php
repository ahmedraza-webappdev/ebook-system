<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../config/db.php");
include("navbar.php");
if(!isset($_SESSION['temp_order'])){ header("Location: index.php"); exit(); }
$order = $_SESSION['temp_order'];
$book_id = $order['book_id'];
$order_type = $order['type'];
$book_res = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");
$book_data = mysqli_fetch_assoc($book_res);
if(isset($_POST['confirm_final'])){
    $u_id = $_SESSION['user_id'];
    $addr = "Name: ".$order['full_name']." | Phone: ".$order['phone']." | Addr: ".$order['address'];
    $total = $order['total'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $sql = "INSERT INTO orders (user_id, book_id, order_type, shipping_address, total_price, payment_status, order_status, created_at) 
            VALUES ('$u_id', '$book_id', '$order_type', '$addr', '$total', '$payment_method', 'Processing', NOW())";
    if(mysqli_query($conn, $sql)){
        $_SESSION['last_order_id'] = mysqli_insert_id($conn);
        unset($_SESSION['temp_order']); 
        header("Location: success.php");
        exit();
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Review Order | E-Library</title>
<style>
.review-page{max-width:720px;margin:0 auto;padding:48px 30px;}
.review-page h1{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:6px;}
.review-page .sub{font-size:0.78rem;color:rgba(255,255,255,0.38);margin-bottom:32px;}
.rcard{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:10px;overflow:hidden;margin-bottom:20px;}
.rcard-head{padding:16px 22px;border-bottom:1px solid rgba(255,255,255,0.07);font-size:0.65rem;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.28);font-weight:700;}
.rcard-body{padding:20px 22px;}
.detail-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);}
.detail-row:last-child{border-bottom:none;}
.detail-label{font-size:0.78rem;color:rgba(255,255,255,0.38);}
.detail-val{font-size:0.84rem;font-weight:500;color:#fff;text-align:right;}
.detail-row.total .detail-label{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:#fff;}
.detail-row.total .detail-val{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--gold);}
.badge-type{background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 10px;border-radius:3px;}
.pay-grid{display:grid;gap:10px;margin-top:4px;}
.pay-option{display:none;}
.pay-label{display:flex;align-items:center;gap:12px;background:#1c2333;border:1px solid rgba(255,255,255,0.07);border-radius:7px;padding:14px 16px;cursor:pointer;transition:all 0.2s;}
.pay-option:checked + .pay-label{background:rgba(201,168,76,0.08);border-color:rgba(201,168,76,0.3);}
.pay-icon{width:36px;height:36px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.pay-name{font-size:0.84rem;font-weight:600;color:#fff;}
.pay-sub{font-size:0.72rem;color:rgba(255,255,255,0.35);}
.radio-dot{width:16px;height:16px;border-radius:50%;border:2px solid rgba(255,255,255,0.2);margin-left:auto;transition:all 0.2s;flex-shrink:0;}
.pay-option:checked + .pay-label .radio-dot{border-color:var(--gold);background:var(--gold);}
.btn-row{display:grid;grid-template-columns:1fr 1.8fr;gap:12px;margin-top:24px;}
.btn-edit{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.5);font-size:0.8rem;font-weight:600;font-family:'DM Sans',sans-serif;padding:13px;border-radius:7px;text-decoration:none;text-align:center;transition:all 0.2s;}
.btn-edit:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.btn-place{background:var(--gold);color:#0d0d0d;border:none;font-size:0.84rem;font-weight:700;font-family:'DM Sans',sans-serif;padding:13px;border-radius:7px;cursor:pointer;transition:background 0.2s;letter-spacing:0.04em;}
.btn-place:hover{background:var(--gold-light);}
</style>
</head>
<body>
<div class="review-page">
  <h1>Review Your Order</h1>
  <p class="sub">Check all details before placing your order</p>

  <div class="rcard">
    <div class="rcard-head">Order Details</div>
    <div class="rcard-body">
      <div class="detail-row"><span class="detail-label">Book</span><span class="detail-val"><?php echo htmlspecialchars($book_data['title']); ?></span></div>
      <div class="detail-row"><span class="detail-label">Customer</span><span class="detail-val"><?php echo htmlspecialchars($order['full_name']); ?></span></div>
      <?php if($order_type == 'Hard Copy'): ?>
      <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-val"><?php echo htmlspecialchars($order['phone']); ?></span></div>
      <div class="detail-row"><span class="detail-label">Ship To</span><span class="detail-val" style="max-width:300px;"><?php echo htmlspecialchars($order['address']); ?></span></div>
      <?php endif; ?>
      <div class="detail-row"><span class="detail-label">Format</span><span class="detail-val"><span class="badge-type"><?php echo $order_type; ?></span></span></div>
      <div class="detail-row total"><span class="detail-label">Total Amount</span><span class="detail-val">Rs. <?php echo number_format($order['total'], 0); ?></span></div>
    </div>
  </div>

  <div class="rcard">
    <div class="rcard-head">Select Payment Method</div>
    <div class="rcard-body">
      <form method="POST">
        <div class="pay-grid">
          <?php if($order_type == 'Hard Copy'): ?>
          <div>
            <input type="radio" class="pay-option" name="payment_method" id="cod" value="Cash on Delivery" checked>
            <label class="pay-label" for="cod">
              <div class="pay-icon" style="background:rgba(74,124,89,0.15);">🚚</div>
              <div><div class="pay-name">Cash on Delivery</div><div class="pay-sub">Pay when you receive</div></div>
              <div class="radio-dot"></div>
            </label>
          </div>
          <?php endif; ?>
          <div>
            <input type="radio" class="pay-option" name="payment_method" id="ep" value="EasyPaisa" <?php echo ($order_type == 'PDF') ? 'checked' : ''; ?>>
            <label class="pay-label" for="ep">
              <div class="pay-icon" style="background:rgba(74,124,89,0.15);">📱</div>
              <div><div class="pay-name">EasyPaisa</div><div class="pay-sub">Mobile payment</div></div>
              <div class="radio-dot"></div>
            </label>
          </div>
          <div>
            <input type="radio" class="pay-option" name="payment_method" id="jc" value="JazzCash">
            <label class="pay-label" for="jc">
              <div class="pay-icon" style="background:rgba(224,92,92,0.12);">💳</div>
              <div><div class="pay-name">JazzCash</div><div class="pay-sub">Mobile wallet</div></div>
              <div class="radio-dot"></div>
            </label>
          </div>
        </div>
        <div class="btn-row">
          <a href="order.php?book_id=<?php echo $book_id; ?>" class="btn-edit">← Edit Details</a>
          <button name="confirm_final" class="btn-place">Place Order Now ✓</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
