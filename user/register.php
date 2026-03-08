<?php
session_start(); // Session start karna zaroori hai message pass karne ke liye
include("../config/db.php");

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "INSERT INTO users(name, email, password, phone) 
            VALUES('$name', '$email', '$password', '$phone')";

    if(mysqli_query($conn, $sql)){
        // Session mein message set karein
        $_SESSION['success_msg'] = "Registration Successful! Please login.";
        // Login page par redirect kar dein
        header("Location: login.php");
        exit(); 
    } else {
        $msg = "Registration failed. Email might already exist.";
        $status = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join E-Book System | Register</title>
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
        }
        .input-group:focus-within label {
            color: #6366f1;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[480px] animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-200 mb-4 transform hover:rotate-12 transition-transform duration-300">
                <i class="fa-solid fa-book-open text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Account</h2>
            <p class="text-slate-500 mt-2 font-medium">Start your digital reading journey</p>
        </div>

        <div class="glass-card rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 p-8 md:p-10">
            
           

            <form method="POST" class="space-y-5">
                
                <div class="input-group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                    <div class="relative">
                        <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="name" placeholder="Ahmed Raza" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" placeholder="example@mail.com" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone Number</label>
                    <div class="relative">
                        <i class="fa-solid fa-mobile-screen absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="phone" placeholder="0300 1234567" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Security Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full pl-12 pr-4 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium placeholder:text-slate-300">
                    </div>
                </div>

                <button type="submit" name="register" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] mt-4">
                    Create My Account
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-slate-500 font-medium">Already have an account? 
                    <a href="login.php" class="text-indigo-600 font-extrabold hover:text-indigo-800 transition-colors ml-1">Login</a>
                </p>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 text-xs font-bold uppercase tracking-widest">
            &copy; <?php echo date('Y'); ?> E-Book System &bull; Secure Protocol
        </p>
    </div>

</body>
</html>