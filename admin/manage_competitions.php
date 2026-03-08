<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');

// 1. Naya Competition Add Karna
if (isset($_POST['add_comp'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $s_date = $_POST['start_date'];
    $e_date = $_POST['end_date'];
    $prize = mysqli_real_escape_string($conn, $_POST['prize']);

    $query = "INSERT INTO competitions (title, description, start_date, end_date, prize) 
              VALUES ('$title', '$desc', '$s_date', '$e_date', '$prize')";
    
    if(mysqli_query($conn, $query)) {
        echo "<script>window.location.href='manage_competitions.php?msg=added';</script>";
        exit();
    }
}

// 2. Competition Delete Karna
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $del_query = "DELETE FROM competitions WHERE id = '$id'";
    if (mysqli_query($conn, $del_query)) {
        echo "<script>window.location.href='manage_competitions.php?msg=deleted';</script>";
        exit();
    }
}

$comps = mysqli_query($conn, "SELECT * FROM competitions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Competitions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background: #f4f6f9; padding: 30px; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-primary { background: #4e73df; border: none; border-radius: 10px; padding: 10px; }
        .btn-back { border-radius: 10px; font-weight: 500; }
        .table thead { background: #4e73df; color: white; }
        
        /* Validation Styling (Red/Green System) */
        .form-control:valid { border-color: #198754; padding-right: calc(1.5em + 0.75rem); background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem); }
        .form-control:invalid:not(:placeholder-shown) { border-color: #dc3545; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem); }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Manage Competitions</h2>
        <a href="dashboard.php" class="btn btn-outline-dark btn-back">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Admin
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 mb-4">
                <h4 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Add New</h4>
                <form method="POST" class="needs-validation">
                    <div class="mb-2">
                        <label class="small fw-bold">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Competition Title" required>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">Description</label>
                        <textarea name="description" class="form-control" placeholder="Short details..." required></textarea>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label class="small fw-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="small fw-bold">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Prize</label>
                        <input type="text" name="prize" class="form-control" placeholder="e.g. 5000 PKR" required>
                    </div>
                    <button name="add_comp" class="btn btn-primary w-100 fw-bold">Create Competition</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-3"><i class="fa-solid fa-list-ul text-primary me-2"></i>Active List</h4>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Prize</th>
                                <th>Deadline</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($comps) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($comps)): ?>
                                <tr>
                                    <td><div class="fw-bold"><?= htmlspecialchars($row['title']) ?></div></td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success">PKR <?= htmlspecialchars($row['prize']) ?></span></td>
                                    <td class="small text-muted"><?= date('d M, Y', strtotime($row['end_date'])) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No competitions found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// --- PROFESSIONAL DELETE POPUP ---
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All submissions for this competition will be affected!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'manage_competitions.php?delete_id=' + id;
        }
    })
}

// --- SHOW SUCCESS/DELETE MESSAGES ---
<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?>
        Swal.fire('Created!', 'New competition has been added.', 'success');
    <?php elseif($_GET['msg'] == 'deleted'): ?>
        Swal.fire('Deleted!', 'Competition has been removed.', 'error');
    <?php endif; ?>
<?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>