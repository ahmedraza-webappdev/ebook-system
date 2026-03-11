<?php
session_start();
include("../config/db.php");

// Update payment status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $oid    = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET payment_status='$status' WHERE id=$oid");
    header("Location: orders.php?msg=updated"); exit;
}

$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
$search = isset($_GET['q'])      ? mysqli_real_escape_string($conn, $_GET['q'])      : '';

$where = ['1=1'];
if ($filter !== 'all') $where[] = "o.payment_status='$filter'";
if ($search !== '')    $where[] = "(u.name LIKE '%$search%' OR b.title LIKE '%$search%')";
$wstr = implode(' AND ', $where);

$per_page = 20;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

$count_res  = mysqli_query($conn, "SELECT COUNT(*) FROM orders o JOIN users u ON o.user_id=u.id JOIN books b ON o.book_id=b.id WHERE $wstr");
$total      = mysqli_fetch_row($count_res)[0];

$orders_res = mysqli_query($conn, "SELECT o.*, u.name as full_name, u.email, b.title as book_title FROM orders o JOIN users u ON o.user_id=u.id JOIN books b ON o.book_id=b.id WHERE $wstr ORDER BY o.id DESC LIMIT $per_page OFFSET $offset");
$orders = [];
while($row = mysqli_fetch_assoc($orders_res)) { $orders[] = $row; }

$total_pages = ceil($total / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_theme.css">
    <style>
        .s-badge { display:inline-flex;align-items:center;gap:5px;font-size:0.6rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;padding:3px 8px;border-radius:3px; }
        .s-badge::before { content:'';width:5px;height:5px;border-radius:50%;display:inline-block; }
        .s-pending  { background:rgba(234,179,8,0.12);  color:#facc15; } .s-pending::before  { background:#facc15; }
        .s-paid     { background:rgba(74,124,89,0.18);  color:#6ee7a0; } .s-paid::before     { background:#6ee7a0; }
        .s-unpaid   { background:rgba(224,92,92,0.12);  color:#e05c5c; } .s-unpaid::before   { background:#e05c5c; }
        .s-cancelled{ background:rgba(255,255,255,0.06);color:#9ca3af; } .s-cancelled::before{ background:#9ca3af; }

        .filter-bar { background:var(--surface);border:1px solid var(--border);border-radius:7px;padding:16px 20px;margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;align-items:center; }
        .search-wrap { position:relative;flex:1;min-width:220px; }
        .search-wrap i { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:0.8rem; }
        .search-inp { width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:5px;padding:9px 12px 9px 34px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:0.84rem;outline:none;transition:border-color 0.2s; }
        .search-inp:focus { border-color:var(--gold); }
        .search-inp::placeholder { color:rgba(255,255,255,0.18); }
        .filter-sel { background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:5px;padding:9px 12px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:0.84rem;outline:none;min-width:160px; }
        .filter-sel option { background:var(--surface); }
        .update-sel { background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:4px;padding:5px 8px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:0.72rem;outline:none;width:115px; }
        .update-sel option { background:var(--surface); }
        .pagi { display:flex;gap:6px;flex-wrap:wrap;margin-top:20px; }
        .pagi a { display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:5px;font-size:0.78rem;font-weight:600;text-decoration:none;background:var(--surface);border:1px solid var(--border);color:var(--muted);transition:all 0.2s; }
        .pagi a:hover { border-color:var(--gold);color:var(--gold); }
        .pagi a.active { background:var(--gold);border-color:var(--gold);color:var(--ink); }
    </style>
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">

    <div class="page-header">
        <div>
            <div class="page-eyebrow">Commerce</div>
            <h1 class="page-title">Manage Orders</h1>
        </div>
        <span class="badge badge-gold"><?php echo $total; ?> Total Orders</span>
    </div>

    <?php if (isset($_GET['msg'])): ?>
    <div style="background:rgba(74,124,89,0.13);border:1px solid rgba(74,124,89,0.22);color:#6ee7a0;border-radius:5px;padding:11px 16px;display:flex;align-items:center;justify-content:space-between;font-size:0.84rem;margin-bottom:18px;">
        <span><i class="fa-solid fa-circle-check" style="margin-right:8px;"></i>Payment status updated successfully!</span>
        <button style="background:none;border:none;color:#6ee7a0;cursor:pointer;font-size:1rem;" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <!-- Filter Bar -->
    <form method="GET">
        <div class="filter-bar">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" class="search-inp" placeholder="Search user or book..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <select name="filter" class="filter-sel">
                <option value="all" <?php echo $filter==='all'?'selected':''; ?>>All Statuses</option>
                <?php foreach (['pending','paid','unpaid','cancelled'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $filter===$s?'selected':''; ?>><?php echo ucfirst($s); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-gold btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="orders.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <span style="font-weight:600;font-size:0.85rem;">
                <i class="fa-solid fa-bag-shopping" style="color:var(--gold);margin-right:7px;"></i>All Orders
            </span>
            <span class="badge badge-muted"><?php echo $total; ?> records</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Book</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th style="text-align:center;">Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:60px;color:var(--muted);">
                            <i class="fa-solid fa-bag-shopping" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.15;"></i>
                            No orders found.
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>
                            <span style="font-family:'Cormorant Garamond',serif;font-size:0.98rem;font-weight:700;color:var(--gold-light);">
                                #<?php echo htmlspecialchars($o['id']); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div class="avatar"><?php echo strtoupper(substr($o['full_name'],0,1)); ?></div>
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.82rem;"><?php echo htmlspecialchars($o['full_name']); ?></div>
                                    <div style="font-size:0.68rem;color:var(--muted);"><?php echo htmlspecialchars($o['email']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:0.8rem;color:var(--text-soft);">
                                <?php $t = $o['book_title']; echo htmlspecialchars(substr($t,0,28)).(strlen($t)>28?'…':''); ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1rem;color:var(--gold-light);">
                                <?php
                                $amount = $o['total_amount'] ?? $o['amount'] ?? $o['price'] ?? 0;
                                echo 'Rs. ' . number_format($amount, 2);
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php $st = $o['payment_status'] ?? 'pending'; ?>
                            <span class="s-badge s-<?php echo $st; ?>"><?php echo ucfirst($st); ?></span>
                        </td>
                        <td>
                            <span style="font-size:0.75rem;color:var(--muted);">
                                <?php echo isset($o['created_at']) ? date('d M Y', strtotime($o['created_at'])) : '—'; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display:flex;align-items:center;gap:5px;justify-content:center;">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <select name="status" class="update-sel">
                                    <?php foreach (['pending','paid','unpaid','cancelled'] as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo ($o['payment_status']===$s)?'selected':''; ?>><?php echo ucfirst($s); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-gold btn-icon btn-sm" title="Update">
                                    <i class="fa-solid fa-check" style="font-size:0.65rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <?php $base = 'orders.php?filter='.$filter.'&q='.urlencode($search).'&page='; ?>
    <div class="pagi">
        <?php if($page > 1): ?>
            <a href="<?php echo $base.($page-1); ?>"><i class="fa-solid fa-chevron-left" style="font-size:0.65rem;"></i></a>
        <?php endif; ?>
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <a href="<?php echo $base.$i; ?>" class="<?php echo $i==$page?'active':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if($page < $total_pages): ?>
            <a href="<?php echo $base.($page+1); ?>"><i class="fa-solid fa-chevron-right" style="font-size:0.65rem;"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</main>
</body>
</html>