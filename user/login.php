<?php
session_start();
include("../config/db.php");

$msg = "";
$status = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name']; 
        
        header("Location:index.php");
        exit(); 
    } else {
        $msg = "Invalid Email or Password!";
        $status = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login | E-Book System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0, transparent 50%), 
                              radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.15) 0, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[450px] animate__animated animate__zoomIn">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-indigo-600 text-white shadow-xl shadow-indigo-200 mb-4 transform rotate-6 hover:rotate-12 transition-transform duration-300">
                <i class="fa-solid fa-shield-halved text-3xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="text-slate-500 mt-2 font-medium">Please enter your details to sign in</p>
        </div>

        <div class="glass-card rounded-[3rem] shadow-2xl shadow-indigo-100/50 p-8 md:p-10">
            
            <?php if(isset($_SESSION['success_msg'])): ?>
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-3 animate__animated animate__fadeInDown">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-bold"><?php echo $_SESSION['success_msg']; ?></span>
                </div>
                <?php unset($_SESSION['success_msg']); ?>
            <?php endif; ?>

            <?php if($msg != ""){ ?>
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 text-rose-700 border border-rose-100 flex items-center gap-3 animate__animated animate__shakeX">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span class="text-sm font-bold"><?php echo $msg; ?></span>
                </div>
            <?php } ?>

            <form method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                    <div class="relative group">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="email" name="email" placeholder="example@mail.com" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2 ml-1">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-tighter transition-colors">Forgot?</a>
                    </div>
                    <div class="relative group">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <button type="submit" name="login" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center">
                    Login <i class="fa-solid fa-arrow-right-to-bracket ml-2 text-sm opacity-70"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-slate-500 font-medium">New member? 
                    <a href="register.php" class="text-indigo-600 font-extrabold hover:text-indigo-800 transition-colors ml-1">Create Account</a>
                </p>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 text-xs font-bold uppercase tracking-widest animate__animated animate__fadeIn animate__delay-1s">
            &copy; <?php echo date('Y'); ?> E-Book System &bull; Secure Protocol
        </p>
    </div>

</body>
</html>