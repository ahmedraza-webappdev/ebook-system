<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');

// ADD
if (isset($_POST['add_comp'])) {
    $title  = mysqli_real_escape_string($conn, $_POST['title']);
    $desc   = mysqli_real_escape_string($conn, $_POST['description']);
    $s_date = $_POST['start_date'];
    $e_date = $_POST['end_date'];
    $prize  = mysqli_real_escape_string($conn, $_POST['prize']);
    $query  = "INSERT INTO competitions (title, description, start_date, end_date, prize) VALUES ('$title', '$desc', '$s_date', '$e_date', '$prize')";
    if(mysqli_query($conn, $query)) { echo "<script>window.location.href='manage_competitions.php?msg=added';</script>"; exit(); }
}

// EDIT
if (isset($_POST['edit_comp'])) {
    $id     = (int)$_POST['edit_id'];
    $title  = mysqli_real_escape_string($conn, $_POST['title']);
    $desc   = mysqli_real_escape_string($conn, $_POST['description']);
    $s_date = $_POST['start_date'];
    $e_date = $_POST['end_date'];
    $prize  = mysqli_real_escape_string($conn, $_POST['prize']);
    $query  = "UPDATE competitions SET title='$title', description='$desc', start_date='$s_date', end_date='$e_date', prize='$prize' WHERE id=$id";
    if(mysqli_query($conn, $query)) { echo "<script>window.location.href='manage_competitions.php?msg=updated';</script>"; exit(); }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM competitions WHERE id = '$id'")) { echo "<script>window.location.href='manage_competitions.php?msg=deleted';</script>"; exit(); }
}

// FETCH for edit
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competitions WHERE id=$eid"));
}

$comps = mysqli_query($conn, "SELECT * FROM competitions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Competitions | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Events</div>
            <h1 class="page-title">Manage Competitions</h1>
        </div>
        <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Back to Admin</a>
    </div>

    <div style="display:grid;grid-template-columns:300px 1fr;gap:22px;align-items:start;">

        <!-- ADD / EDIT FORM -->
        <div class="card" style="position:sticky;top:20px;">
            <div class="card-header">
                <span style="font-weight:600;font-size:0.85rem;">
                    <i class="fa-solid fa-<?php echo $edit_data ? 'pen' : 'plus-circle'; ?>" style="color:var(--gold);margin-right:7px;"></i>
                    <?php echo $edit_data ? 'Edit Competition' : 'Add New'; ?>
                </span>
                <?php if($edit_data): ?>
                <a href="manage_competitions.php" class="btn btn-ghost btn-sm" style="font-size:0.65rem;">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if($edit_data): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-input" placeholder="Competition Title" required
                               value="<?php echo $edit_data ? htmlspecialchars($edit_data['title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" placeholder="Short details..." required style="min-height:75px;"><?php echo $edit_data ? htmlspecialchars($edit_data['description']) : ''; ?></textarea>
                    </div>
                    <?php $today = date('Y-m-d'); $tomorrow = date('Y-m-d', strtotime('+1 day')); ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-input" required
                                   min="<?php echo $today; ?>"
                                   value="<?php echo $edit_data ? $edit_data['start_date'] : $today; ?>"
                                   onchange="updateEndMin()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-input" required
                                   min="<?php echo $tomorrow; ?>"
                                   value="<?php echo $edit_data ? $edit_data['end_date'] : $tomorrow; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prize</label>
                        <input type="text" name="prize" class="form-input" placeholder="e.g. 5000 PKR" required
                               value="<?php echo $edit_data ? htmlspecialchars($edit_data['prize']) : ''; ?>">
                    </div>

                    <?php if($edit_data): ?>
                    <button name="edit_comp" class="btn btn-gold" style="width:100%;justify-content:center;padding:11px;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                    <?php else: ?>
                    <button name="add_comp" class="btn btn-gold" style="width:100%;justify-content:center;padding:11px;">
                        <i class="fa-solid fa-trophy"></i> Create Competition
                    </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- LIST -->
        <div class="card">
            <div class="card-header">
                <span style="font-weight:600;font-size:0.85rem;">
                    <i class="fa-solid fa-list-ul" style="color:var(--gold);margin-right:7px;"></i>Active List
                </span>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Prize</th>
                            <th>Deadline</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($comps) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($comps)): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#fff;font-size:0.84rem;"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div style="font-size:0.7rem;color:var(--muted);margin-top:2px;"><?php echo htmlspecialchars(substr($row['description'],0,55)); ?>...</div>
                                </td>
                                <td><span class="badge badge-gold">PKR <?php echo htmlspecialchars($row['prize']); ?></span></td>
                                <td><span style="font-size:0.78rem;color:var(--text-soft);"><?php echo date('d M, Y', strtotime($row['end_date'])); ?></span></td>
                                <td style="text-align:center;">
                                    <div style="display:flex;justify-content:center;gap:6px;">
                                        <a href="manage_competitions.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit">
                                            <i class="fa-solid fa-pen" style="font-size:0.6rem;"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon btn-sm" title="Delete">
                                            <i class="fa-solid fa-trash-can" style="font-size:0.6rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted);">No competitions found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
function updateEndMin() {
    var startVal = document.getElementById('start_date').value;
    if (startVal) {
        var next = new Date(startVal);
        next.setDate(next.getDate() + 1);
        var minEnd = next.toISOString().split('T')[0];
        var endField = document.getElementById('end_date');
        endField.min = minEnd;
        if (endField.value && endField.value <= startVal) {
            endField.value = minEnd;
        }
    }
}
function confirmDelete(id) {
    Swal.fire({ title:'Are you sure?', text:"All submissions for this competition will be affected!", icon:'warning', showCancelButton:true, confirmButtonColor:'#c9a84c', cancelButtonColor:'#e05c5c', confirmButtonText:'Yes, delete it!', cancelButtonText:'Cancel', background:'#1c2333', color:'#f0ece4' })
    .then((result) => { if(result.isConfirmed) window.location.href='manage_competitions.php?delete_id='+id; });
}
<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?>
        Swal.fire({ icon:'success', title:'Created!', text:'New competition has been added.', timer:2000, showConfirmButton:false, background:'#1c2333', color:'#f0ece4' });
    <?php elseif($_GET['msg'] == 'updated'): ?>
        Swal.fire({ icon:'success', title:'Updated!', text:'Competition has been updated.', timer:2000, showConfirmButton:false, background:'#1c2333', color:'#f0ece4' });
    <?php elseif($_GET['msg'] == 'deleted'): ?>
        Swal.fire({ icon:'error', title:'Deleted!', text:'Competition has been removed.', timer:2000, showConfirmButton:false, background:'#1c2333', color:'#f0ece4' });
    <?php endif; ?>
<?php endif; ?>
</script>
</body>
</html>