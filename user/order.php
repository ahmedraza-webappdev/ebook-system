<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 

$validation_error = false;

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['book_id'])){
    header("Location: index.php");
    exit();
}

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
        if(empty($full_name) || empty($phone) || empty($address) || $qty < 1){
            $validation_error = true;
        }
    } else {
        if(empty($full_name)){
            $validation_error = true;
        }
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
    <title>Checkout | <?php echo $book_data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        .checkout-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 20px; display: flex; flex-wrap: wrap; }
        .book-sidebar { background: #1e293b; color: white; padding: 40px; text-align: center; flex: 1 1 350px; }
        .order-form-area { padding: 40px; flex: 2 1 500px; }
        .book-img { width: 180px; height: 260px; object-fit: cover; border-radius: 10px; border: 4px solid rgba(255,255,255,0.1); }
        
        /* NEW BACK BUTTON STYLE */
        .back-nav { margin-top: 30px; margin-bottom: -10px; }
        .btn-back { 
            background: white; color: #1e293b; border: none; 
            padding: 10px 20px; border-radius: 12px; font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease;
            text-decoration: none; display: inline-flex; align-items: center;
        }
        .btn-back:hover { background: #1e293b; color: white; transform: translateX(-5px); }

        @media (max-width: 768px) { .checkout-card { margin: 15px; } .book-sidebar { padding: 20px; } }
    </style>
</head>
<body>

<div class="container">
    <div class="back-nav">
        <a href="javascript:history.back()" class="btn-back">
            <i class="fa-solid fa-arrow-left-long me-2"></i> Go Back
        </a>
    </div>

    <div class="checkout-card mx-auto" style="max-width: 1000px;">
        <div class="book-sidebar">
            <img src="<?php echo $image_path; ?>" class="book-img mb-3 shadow-lg" alt="Cover">
            <h4 class="fw-bold"><?php echo htmlspecialchars($book_data['title']); ?></h4>
            <p class="text-white-50">By <?php echo htmlspecialchars($book_data['author']); ?></p>
            <div class="h3 fw-bold mt-4 text-warning">₹<?php echo $book_data['price']; ?></div>
        </div>

        <div class="order-form-area">
            <h2 class="fw-bold mb-4">Order Details</h2>
            
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label class="form-label fw-bold d-block">Choose Format</label>
                    <div class="btn-group w-100">
                        <input type="radio" class="btn-check" name="type" id="pdf" value="PDF" checked onclick="toggleFields()">
                        <label class="btn btn-outline-primary" for="pdf">Digital PDF</label>
                        <input type="radio" class="btn-check" name="type" id="hard" value="Hard Copy" onclick="toggleFields()">
                        <label class="btn btn-outline-primary" for="hard">Hard Copy</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="full_name" class="form-control px-3 py-2" placeholder="Enter your full name" required>
                </div>

                <div class="row" id="qty_section" style="display: none;">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Quantity</label>
                        <input type="number" name="qty" id="qty_input" class="form-control" value="1" min="1">
                    </div>
                </div>

                <div id="shipping_info" style="display: none;">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="phone" id="phone_field" class="form-control" placeholder="Active mobile number">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Shipping Address</label>
                        <textarea name="address" id="address_field" class="form-control" rows="3" placeholder="Full delivery address"></textarea>
                    </div>
                </div>

                <button name="go_to_review" type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow fw-bold mt-3">
                     REVIEW ORDER <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
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

(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

<?php if($validation_error): ?>
<script>Swal.fire('Error!', 'Please fill all required fields correctly.', 'error');</script>
<?php endif; ?>

</body>
</html>