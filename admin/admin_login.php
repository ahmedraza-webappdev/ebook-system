<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.box{
width:350px;
margin:120px auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 10px #ccc;
}

input{
width:100%;
padding:10px;
margin-top:10px;
}

button{
width:100%;
padding:10px;
background:#2c3e50;
color:white;
margin-top:15px;
}

</style>

</head>

<body>

<div class="box">

<h2>Admin Login</h2>

<form method="POST" action="admin_login_process.php">

<input type="text" name="username" placeholder="Username">

<input type="password" name="password" placeholder="Password">

<button>Login</button>

</form>

</div>

</body>
</html>