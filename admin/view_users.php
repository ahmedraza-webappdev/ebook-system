<?php
include("../config/db.php");
$count_result = mysqli_query($conn, "SELECT id FROM users");
$total_users  = mysqli_num_rows($count_result);
$result       = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$total_rows   = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Community</div>
            <h1 class="page-title">Community Directory</h1>
        </div>
        <span class="badge badge-gold"><?php echo $total_users; ?> Members</span>
    </div>

    <div class="card">
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">ID</th>
                        <th>User Details</th>
                        <th>Contact & Auth</th>
                        <th>Address</th>
                        <th style="text-align:center;">Joined Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_rows > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td style="text-align:center;"><span style="font-size:0.7rem;color:var(--muted);font-weight:700;">#<?php echo htmlspecialchars($row['id']); ?></span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="avatar"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></div>
                                    <div>
                                        <div style="font-weight:600;color:#fff;font-size:0.84rem;"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <span class="badge badge-green" style="margin-top:3px;">Active</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:0.78rem;color:var(--text-soft);line-height:1.9;">
                                    <div><i class="fa-regular fa-envelope" style="color:var(--gold);width:14px;margin-right:5px;"></i><?php echo htmlspecialchars($row['email']); ?></div>
                                    <div><i class="fa-solid fa-phone" style="color:#6ee7a0;width:14px;margin-right:5px;"></i><?php echo htmlspecialchars($row['phone']); ?></div>
                                    <div style="color:var(--muted);"><i class="fa-solid fa-key" style="width:14px;margin-right:5px;"></i><span style="letter-spacing:0.1em;">••••••••</span></div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:0.78rem;color:var(--muted);display:block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    <i class="fa-solid fa-location-dot" style="color:var(--rose);margin-right:5px;"></i><?php echo htmlspecialchars($row['address']); ?>
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div style="font-size:0.78rem;font-weight:600;color:var(--text-soft);"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                                <div style="font-size:0.68rem;color:var(--muted);"><?php echo date('h:i A', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;justify-content:flex-end;gap:6px;">
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="fa-solid fa-pen" style="font-size:0.6rem;"></i></a>
                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Pakka delete karna hai?')" class="btn btn-danger btn-icon btn-sm" title="Delete"><i class="fa-solid fa-trash" style="font-size:0.6rem;"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;padding:60px;color:var(--muted);">
                            <i class="fa-solid fa-users-slash" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.15;"></i>
                            No community members found.
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:14px 18px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:0.65rem;color:var(--muted);letter-spacing:0.1em;text-transform:uppercase;">Database: <span style="color:var(--gold);">E-Book System</span></span>
            <span style="font-size:0.65rem;color:var(--muted);">
                <span style="display:inline-block;width:6px;height:6px;background:var(--gold);border-radius:50%;margin-right:5px;"></span>
                Users: <?php echo $total_rows; ?>
            </span>
        </div>
    </div>
</main>

</body>
</html>
