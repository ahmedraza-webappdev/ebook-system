<?php
session_start();
include_once(__DIR__ . "/../config/db.php");
if (!$conn) { die("Database connection failed."); }

if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $file_query = mysqli_query($conn, "SELECT file FROM submissions WHERE id = '$id'");
    $file_data = mysqli_fetch_assoc($file_query);
    if ($file_data) {
        $file_path = "../uploads/essays/" . $file_data['file'];
        if (file_exists($file_path)) { unlink($file_path); }
        $del_query = "DELETE FROM submissions WHERE id = '$id'";
        if (mysqli_query($conn, $del_query)) { header("Location: view_submissions.php?msg=deleted"); exit(); }
    }
}
if (isset($_GET['set_winner']) && isset($_GET['comp_id']) && isset($_GET['user_id'])) {
    $comp_id = mysqli_real_escape_string($conn, $_GET['comp_id']);
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $update = "UPDATE competitions SET winner_id = '$user_id' WHERE id = '$comp_id'";
    if (mysqli_query($conn, $update)) { header("Location: view_submissions.php?msg=winner_set"); exit(); }
}
if (isset($_GET['reset_winner']) && isset($_GET['comp_id'])) {
    $comp_id = mysqli_real_escape_string($conn, $_GET['comp_id']);
    $reset = "UPDATE competitions SET winner_id = NULL WHERE id = '$comp_id'";
    if (mysqli_query($conn, $reset)) { header("Location: view_submissions.php?msg=reset"); exit(); }
}

$query = "SELECT s.id, s.user_id, s.competition_id, s.file, s.submitted_at, 
          u.name as user_name, c.title as comp_title, c.winner_id as comp_winner_id 
          FROM submissions s 
          INNER JOIN competitions c ON s.competition_id = c.id 
          LEFT JOIN users u ON s.user_id = u.id 
          ORDER BY s.submitted_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submissions | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_theme.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include("admin_sidebar.php"); ?>

<main class="admin-main">
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Competitions</div>
            <h1 class="page-title">Submissions Management</h1>
        </div>
        <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="card">
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Competition</th>
                        <th>File Details</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="avatar"><?php echo strtoupper(substr($row['user_name']??'?',0,1)); ?></div>
                                    <div>
                                        <div style="font-weight:600;color:#fff;font-size:0.84rem;"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                        <div style="font-size:0.68rem;color:var(--muted);">UID: #<?php echo $row['user_id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-gold"><?php echo htmlspecialchars($row['comp_title']); ?></span></td>
                            <td>
                                <div style="font-size:0.8rem;font-weight:500;color:var(--text-soft);"><?php echo $row['file']; ?></div>
                                <div style="font-size:0.68rem;color:var(--muted);margin-top:2px;"><?php echo date('d M, Y', strtotime($row['submitted_at'])); ?></div>
                            </td>
                            <td>
                                <div style="display:flex;justify-content:center;align-items:center;gap:6px;flex-wrap:wrap;">
                                    <a href="read_essay.php?file=<?php echo $row['file']; ?>" class="btn btn-ghost btn-sm"><i class="fa-solid fa-eye"></i></a>

                                    <?php if(!empty($row['comp_winner_id']) && $row['comp_winner_id'] == $row['user_id']): ?>
                                        <span class="badge badge-gold"><i class="fa-solid fa-crown" style="margin-right:4px;"></i>WINNER</span>
                                        <button onclick="confirmReset(<?php echo $row['competition_id']; ?>)" class="btn btn-ghost btn-icon btn-sm"><i class="fa-solid fa-rotate-left" style="font-size:0.6rem;"></i></button>
                                    <?php else: ?>
                                        <button onclick="confirmWinner(<?php echo $row['competition_id']; ?>, <?php echo $row['user_id']; ?>)" class="btn btn-success btn-sm"><i class="fa-solid fa-trophy"></i> Set Winner</button>
                                    <?php endif; ?>

                                    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-icon btn-sm"><i class="fa-solid fa-trash-can" style="font-size:0.6rem;"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;padding:60px;color:var(--muted);">No submissions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
<?php if(isset($_GET['msg'])): ?>
Swal.fire({ icon:'success', title:'Done!', text:'<?php echo ($_GET['msg']=="winner_set") ? "Winner has been updated!" : (($_GET['msg']=="deleted") ? "Submission deleted successfully!" : "Winner status reset!"); ?>', timer:2000, showConfirmButton:false, background:'#1c2333', color:'#f0ece4' });
<?php endif; ?>
function confirmDelete(id){ Swal.fire({title:'Are you sure?',text:"You won't be able to revert this!",icon:'warning',showCancelButton:true,confirmButtonColor:'#e05c5c',cancelButtonColor:'#c9a84c',confirmButtonText:'Yes, delete it!',background:'#1c2333',color:'#f0ece4'}).then(r=>{if(r.isConfirmed)window.location.href='view_submissions.php?delete_id='+id;}); }
function confirmWinner(compId,userId){ Swal.fire({title:'Announce Winner?',text:"Set this participant as the winner?",icon:'question',showCancelButton:true,confirmButtonColor:'#c9a84c',confirmButtonText:'Yes, Set Winner!',background:'#1c2333',color:'#f0ece4'}).then(r=>{if(r.isConfirmed)window.location.href=`view_submissions.php?set_winner=1&comp_id=${compId}&user_id=${userId}`;}); }
function confirmReset(compId){ Swal.fire({title:'Reset Winner?',text:"Remove winner status?",icon:'warning',showCancelButton:true,confirmButtonText:'Yes, reset',background:'#1c2333',color:'#f0ece4'}).then(r=>{if(r.isConfirmed)window.location.href='view_submissions.php?reset_winner=1&comp_id='+compId;}); }
</script>
</body>
</html>
