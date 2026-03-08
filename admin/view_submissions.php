<?php
session_start();
include_once(__DIR__ . "/../config/db.php"); 

if (!$conn) { die("Database connection failed."); }

// 1. DELETE LOGIC
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    // File delete karne ke liye pehle naam uthayen
    $file_query = mysqli_query($conn, "SELECT file FROM submissions WHERE id = '$id'");
    $file_data = mysqli_fetch_assoc($file_query);
    
    if ($file_data) {
        $file_path = "../uploads/essays/" . $file_data['file'];
        if (file_exists($file_path)) { unlink($file_path); }
        
        $del_query = "DELETE FROM submissions WHERE id = '$id'";
        if (mysqli_query($conn, $del_query)) {
            header("Location: view_submissions.php?msg=deleted");
            exit();
        }
    }
}

// 2. SET WINNER LOGIC
if (isset($_GET['set_winner']) && isset($_GET['comp_id']) && isset($_GET['user_id'])) {
    $comp_id = mysqli_real_escape_string($conn, $_GET['comp_id']);
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    
    $update = "UPDATE competitions SET winner_id = '$user_id' WHERE id = '$comp_id'";
    if (mysqli_query($conn, $update)) {
        header("Location: view_submissions.php?msg=winner_set");
        exit();
    }
}

// 3. RESET WINNER LOGIC
if (isset($_GET['reset_winner']) && isset($_GET['comp_id'])) {
    $comp_id = mysqli_real_escape_string($conn, $_GET['comp_id']);
    $reset = "UPDATE competitions SET winner_id = NULL WHERE id = '$comp_id'";
    if (mysqli_query($conn, $reset)) {
        header("Location: view_submissions.php?msg=reset");
        exit();
    }
}

// 4. FETCH DATA
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
    <title>Admin | Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #f4f7fe; padding: 40px; font-family: 'Segoe UI', sans-serif; }
        .admin-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border:none; }
        .winner-badge { background: #1cc88a; color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-block; }
        .btn-winner { background: #f1c40f; color: #000; font-weight: bold; border-radius: 8px; border: none; font-size: 0.75rem; transition: 0.3s; }
        .btn-winner:hover { background: #d4ac0d; transform: scale(1.05); }
        .comp-topic-tag { background: #eef2ff; color: #4e73df; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Submissions Management</h2>
        <a href="dashboard.php" class="btn btn-sm btn-outline-dark">Back to Dashboard</a>
    </div>

    <div class="card admin-card">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Participant</th>
                    <th>Competition</th>
                    <th>File Details</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($row['user_name']) ?></div>
                            <small class="text-muted">UID: #<?= $row['user_id'] ?></small>
                        </td>
                        <td>
                            <span class="comp-topic-tag"><?= htmlspecialchars($row['comp_title']) ?></span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark"><?= $row['file'] ?></div>
                            <small class="text-muted"><?= date('d M, Y', strtotime($row['submitted_at'])) ?></small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="read_essay.php?file=<?= $row['file'] ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye"></i></a>

                                <?php if(!empty($row['comp_winner_id']) && $row['comp_winner_id'] == $row['user_id']): ?>
                                    <span class="winner-badge shadow-sm"><i class="fa-solid fa-crown text-warning me-1"></i> WINNER</span>
                                    <button onclick="confirmReset(<?= $row['competition_id'] ?>)" class="btn btn-sm text-muted border-0"><i class="fa-solid fa-rotate-left"></i></button>
                                <?php else: ?>
                                    <button onclick="confirmWinner(<?= $row['competition_id'] ?>, <?= $row['user_id'] ?>)" class="btn btn-winner px-3 py-1">
                                        <i class="fa-solid fa-trophy me-1"></i> Set Winner
                                    </button>
                                <?php endif; ?>

                                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm text-danger border-0"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-5">No submissions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// SweetAlert Success Messages
<?php if(isset($_GET['msg'])): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= ($_GET['msg'] == "winner_set") ? "Winner has been updated!" : (($_GET['msg'] == "deleted") ? "Submission deleted successfully!" : "Winner status reset!") ?>',
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

// Delete Confirmation Popup
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'view_submissions.php?delete_id=' + id;
        }
    })
}

// Winner Confirmation Popup
function confirmWinner(compId, userId) {
    Swal.fire({
        title: 'Announce Winner?',
        text: "Do you want to set this participant as the winner?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f1c40f',
        confirmButtonText: 'Yes, Set Winner!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `view_submissions.php?set_winner=1&comp_id=${compId}&user_id=${userId}`;
        }
    })
}

// Reset Confirmation
function confirmReset(compId) {
    Swal.fire({
        title: 'Reset Winner?',
        text: "Remove winner status from this competition?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'view_submissions.php?reset_winner=1&comp_id=' + compId;
        }
    })
}
</script>
</body>
</html>