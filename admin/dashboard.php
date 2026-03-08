<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
        }

        .container {
            width: 90%;
            margin: auto;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap; /* Isse cards zyada hone par niche line mein aa jayenge */
            gap: 20px;
            justify-content: center;
        }

        .card {
            width: 220px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
            transition: 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h3 { color: #2c3e50; font-size: 1.2rem; }

        a {
            display: block;
            margin-top: 10px;
            background: #3498db;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        a:hover { background: #2980b9; }

        /* Logout button color different rakha hai safety ke liye */
        .logout-link { background: #e74c3c; }
        .logout-link:hover { background: #c0392b; }
        
        /* Submission card ka special touch */
        .submission-card { border-top: 4px solid #f1c40f; }
    </style>
</head>

<body>

<h2 style="text-align:center; margin-top: 30px;">Admin Management Panel</h2>

<div class="container">

    <div class="card">
        <h3>Upload Book</h3>
        <a href="upload_book.php">Open</a>
    </div>

    <div class="card">
        <h3>View Books</h3>
        <a href="view_books.php">Open</a>
    </div>

    <div class="card submission-card">
        <h3>Essay Submissions</h3>
        <p style="font-size: 0.9rem; color: #666;">Read student essays</p>
        <a href="view_submissions.php" style="background: #f1c40f; color: #2c3e50;">View All</a>
    </div>

    <div class="card" style="border-top: 4px solid #9b59b6;">
    <h3 style="color: #8e44ad;">🏆 Manage Competitions</h3>
    <p style="font-size: 0.9rem; color: #666;">Add new events & winners</p>
    <a href="manage_competitions.php" style="background: #9b59b6;">Open Manager</a>
</div>

    <div class="card">
        <h3>Users</h3>
        <a href="view_users.php">Open</a>
    </div>

    <div class="card">
        <h3>Edit Books</h3>
        <p style="font-size: 0.9rem; color: #666;">Update existing books</p>
        <a href="books_list.php">Go to Books</a>
    </div>

    <div class="card">
        <h3>Logout</h3>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

</div>

</body>
</html>