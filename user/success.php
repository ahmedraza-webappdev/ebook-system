<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("navbar.php");
?>
<div class="container text-center py-5">
    <div class="py-5">
        <div class="mb-4">
            <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
        </div>
        <h1 class="display-4 fw-bold">Thank You!</h1>
        <p class="lead text-muted mb-5">Your order has been placed successfully. We'll process it soon.</p>
        
        <div class="card border-0 bg-light p-4 rounded-4 mb-5 mx-auto" style="max-width: 400px;">
            <p class="mb-1">Order ID: <strong>#ORD-<?php echo $_SESSION['last_order_id']; ?></strong></p>
            <p class="small text-muted">A confirmation email has been sent to your registered ID.</p>
        </div>

        <a href="index.php" class="btn btn-primary btn-lg px-5 rounded-pill shadow">Back to Explore</a>
    </div>
</div>