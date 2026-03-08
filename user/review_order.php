<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../config/db.php");
include("navbar.php");

if(!isset($_SESSION['temp_order'])){ 
    header("Location: index.php"); 
    exit(); 
}

$order = $_SESSION['temp_order'];
$book_id = $order['book_id'];
$order_type = $order['type']; // PDF or Hard Copy

// Book details fetch karna
$book_res = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");
$book_data = mysqli_fetch_assoc($book_res);

// Final Confirmation Logic
if(isset($_POST['confirm_final'])){
    $u_id = $_SESSION['user_id'];
    $addr = "Name: ".$order['full_name']." | Phone: ".$order['phone']." | Addr: ".$order['address'];
    $total = $order['total'];
    
    // User ne jo method select kiya wo database mein jayega
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

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 750px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-0 text-center">Review Your Order Details</h4>
        </div>
        <div class="card-body p-5">
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle">
                    <tr>
                        <th class="text-muted fw-normal" width="30%">Book Title:</th>
                        <td class="fw-bold text-primary"><?php echo $book_data['title']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Customer:</th>
                        <td><?php echo $order['full_name']; ?></td>
                    </tr>
                    
                    <?php if($order_type == 'Hard Copy'): ?>
                    <tr>
                        <th class="text-muted fw-normal">Phone:</th>
                        <td><?php echo $order['phone']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Shipping To:</th>
                        <td><?php echo $order['address']; ?></td>
                    </tr>
                    <?php endif; ?>

                    <tr>
                        <th class="text-muted fw-normal">Format:</th>
                        <td><span class="badge bg-info text-dark"><?php echo $order_type; ?></span></td>
                    </tr>
                    <tr class="table-light">
                        <th class="h5">Total Amount:</th>
                        <td class="h4 fw-bold text-success">₹<?php echo $order['total']; ?></td>
                    </tr>
                </table>
            </div>

            <form method="POST">
                <h5 class="fw-bold mb-3 mt-4">Select Payment Method:</h5>
                <div class="row g-3 mb-4">
                    
                    <?php if($order_type == 'Hard Copy'): ?>
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="payment_method" id="cod" value="Cash on Delivery" checked>
                        <label class="btn btn-outline-dark w-100 py-3 rounded-4" for="cod">
                            <i class="fa-solid fa-truck d-block mb-1"></i> Cash on Delivery
                        </label>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="payment_method" id="ep" value="EasyPaisa" <?php echo ($order_type == 'PDF') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary w-100 py-3 rounded-4" for="ep">
                            <i class="fa-solid fa-mobile-screen d-block mb-1"></i> EasyPaisa
                        </label>
                    </div>
                    
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="payment_method" id="jc" value="JazzCash">
                        <label class="btn btn-outline-danger w-100 py-3 rounded-4" for="jc">
                            <i class="fa-solid fa-wallet d-block mb-1"></i> JazzCash
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-5">
                    <a href="order.php?book_id=<?php echo $book_id; ?>" class="btn btn-outline-secondary btn-lg flex-fill rounded-pill">
                        <i class="fa-solid fa-arrow-left me-1"></i> Edit Details
                    </a>
                    <button name="confirm_final" class="btn btn-success btn-lg flex-fill rounded-pill shadow">
                        Place Order Now <i class="fa-solid fa-check-double ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>