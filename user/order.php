<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 
$validation_error = false;
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }
if(!isset($_GET['book_id'])){ header("Location: index.php"); exit(); }
$book_id = mysqli_real_escape_string($conn, $_GET['book_id']);
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");
$book_data = mysqli_fetch_assoc($book_query);
$image_path = "../uploads/covers/" . $book_data['book_image'];
if(isset($_POST['go_to_review'])){
    $type = $_POST['type'];
    $full_name = trim($_POST['full_name']);
    $qty = ($type == 'PDF') ? 1 : (int)$_POST['qty'];
    if($type == 'Hard Copy'){
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        if(empty($full_name) || empty($phone) || empty($address) || $qty < 1){ $validation_error = true; }
    } else {
        if(empty($full_name)){ $validation_error = true; }
        $phone = "N/A";
        $address = "Digital Delivery";
    }
    if(!$validation_error){
        $_SESSION['temp_order'] = [
            'book_id' => $book_id,
            'full_name' => $full_name,
            'phone' => $phone,
            'qty' => $qty,
            'address' => $address,
            'type' => $type,
            'total' => $book_data['price'] * $qty
        ];
        echo "<script>window.location.href='review_order.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order | <?php echo htmlspecialchars($book_data['title']); ?></title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.order-page{max-width:1000px;margin:0 auto;padding:40px 30px;}
.back-link{display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.38);text-decoration:none;font-size:0.78rem;margin-bottom:28px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);padding:8px 16px;border-radius:5px;transition:all 0.2s;}
.back-link:hover{color:#fff;border-color:rgba(255,255,255,0.18);}
.order-wrap{display:grid;grid-template-columns:320px 1fr;gap:0;background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:10px;overflow:hidden;}
.book-sidebar{background:#0d0d0d;padding:40px;text-align:center;border-right:1px solid rgba(255,255,255,0.07);}
.book-sidebar img{width:160px;height:230px;object-fit:cover;border-radius:6px;margin-bottom:20px;}
.book-sidebar h4{font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;color:#fff;margin-bottom:5px;}
.book-sidebar .auth{font-size:0.76rem;color:rgba(255,255,255,0.38);}
.price-tag{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:var(--gold);margin-top:14px;}
.form-area{padding:40px;}
.form-area h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:#fff;margin-bottom:28px;}
.format-toggle{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px;}
.format-toggle input[type=radio]{display:none;}
.format-toggle label{background:#1c2333;border:1px solid rgba(255,255,255,0.08);border-radius:7px;padding:14px;text-align:center;cursor:pointer;transition:all 0.2s;font-size:0.8rem;color:rgba(255,255,255,0.5);font-weight:500;}
.format-toggle label i{display:block;font-size:1.2rem;margin-bottom:7px;color:rgba(255,255,255,0.25);}
.format-toggle input:checked + label{background:rgba(201,168,76,0.1);border-color:rgba(201,168,76,0.35);color:#fff;}
.format-toggle input:checked + label i{color:var(--gold);}
.fgroup{margin-bottom:18px;}
.fgroup label{display:block;font-size:0.65rem;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.38);font-weight:700;margin-bottom:8px;}
.fgroup input,.fgroup textarea{width:100%;background:#1c2333;border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:11px 14px;color:#fff;font-size:0.84rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;}
.fgroup input:focus,.fgroup textarea:focus{border-color:rgba(201,168,76,0.35);}
.fgroup input::placeholder,.fgroup textarea::placeholder{color:rgba(255,255,255,0.18);}
.fgroup textarea{resize:vertical;min-height:80px;}
.btn-review{width:100%;background:var(--gold);color:#0d0d0d;border:none;border-radius:7px;padding:14px;font-size:0.84rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background 0.2s;margin-top:8px;letter-spacing:0.05em;}
.btn-review:hover{background:var(--gold-light);}
@media(max-width:700px){.order-wrap{grid-template-columns:1fr;}.book-sidebar{border-right:none;border-bottom:1px solid rgba(255,255,255,0.07);}}
</style>
</head>
<body>
<div class="order-page">
  <a href="javascript:history.back()" class="back-link"><i class="fa-solid fa-arrow-left"></i> Go Back</a>
  <div class="order-wrap">
    <div class="book-sidebar">
      <img src="<?php echo $image_path; ?>" alt="Cover" onerror="this.src='../assets/img/default-cover.jpg'">
      <h4><?php echo htmlspecialchars($book_data['title']); ?></h4>
      <div class="auth">By <?php echo htmlspecialchars($book_data['author']); ?></div>
      <div class="price-tag">Rs. <?php echo $book_data['price']; ?></div>
    </div>
    <div class="form-area">
      <h2>Order Details</h2>
      <form method="POST">
        <div class="fgroup">
          <label>Choose Format</label>
          <div class="format-toggle">
            <input type="radio" name="type" id="pdf" value="PDF" checked onclick="toggleFields()">
            <label for="pdf"><i class="fa-regular fa-file-pdf"></i>Digital PDF</label>
            <input type="radio" name="type" id="hard" value="Hard Copy" onclick="toggleFields()">
            <label for="hard"><i class="fa-solid fa-book"></i>Hard Copy</label>
          </div>
        </div>
        <div class="fgroup">
          <label>Full Name</label>
          <input type="text" name="full_name" placeholder="Your full name" required>
        </div>
        <div id="qty_section" style="display:none;">
          <div class="fgroup">
            <label>Quantity</label>
            <input type="number" name="qty" id="qty_input" value="1" min="1">
          </div>
        </div>
        <div id="shipping_info" style="display:none;">
          <div class="fgroup">
            <label>Phone Number</label>
            <input type="tel" name="phone" id="phone_field" placeholder="Active mobile number">
          </div>
          <div class="fgroup">
            <label>Shipping Address</label>
            <textarea name="address" id="address_field" placeholder="Full delivery address"></textarea>
          </div>
        </div>
        <button name="go_to_review" type="submit" class="btn-review">Review Order →</button>
      </form>
    </div>
  </div>
</div>
<script>
function toggleFields() {
    var isHardCopy = document.getElementById('hard').checked;
    var shippingSection = document.getElementById('shipping_info');
    var qtySection = document.getElementById('qty_section'); 
    var qtyInput = document.getElementById('qty_input');
    var phone = document.getElementById('phone_field');
    var address = document.getElementById('address_field');
    if (isHardCopy) {
        shippingSection.style.display = 'block';
        qtySection.style.display = 'block'; 
        phone.setAttribute('required', '');
        address.setAttribute('required', '');
        qtyInput.setAttribute('required', '');
    } else {
        shippingSection.style.display = 'none';
        qtySection.style.display = 'none'; 
        phone.removeAttribute('required');
        address.removeAttribute('required');
        qtyInput.removeAttribute('required');
        phone.value = "";
        address.value = "";
        qtyInput.value = "1"; 
    }
}
window.onload = toggleFields;
</script>
<?php if($validation_error): ?>
<script>Swal.fire({title:'Error!',text:'Please fill all required fields correctly.',icon:'error',background:'#141920',color:'#f0ece4',confirmButtonColor:'#c9a84c'});</script>
<?php endif; ?>
</body>
</html>
