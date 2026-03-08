<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out | E-Book System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0, transparent 50%), 
                              radial-gradient(at 100% 100%, rgba(244, 63, 94, 0.1) 0, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .progress-bar {
            animation: shrink 5s linear forwards;
        }
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[450px] text-center animate__animated animate__zoomIn">
        
        <div class="relative mb-8 inline-block">
            <div class="w-24 h-24 rounded-3xl bg-emerald-500 text-white shadow-2xl shadow-emerald-200 flex items-center justify-center mx-auto transform rotate-12">
                <i class="fa-solid fa-right-from-bracket text-4xl -ml-1"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg text-emerald-500 animate__animated animate__bounceIn animate__delay-1s">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
        </div>

        <div class="glass-card rounded-[3rem] p-10 shadow-2xl shadow-slate-200/60">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Safe & Secure</h2>
            <p class="text-slate-500 mt-3 font-medium px-4">You have been successfully logged out of the E-Book System.</p>

            <div class="mt-10 space-y-4">
                <a href="login.php" class="block w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-100 transition-all transform hover:-translate-y-1 active:scale-[0.98]">
                    <i class="fa-solid fa-lock-open mr-2 text-sm"></i> Sign In Again
                </a>
                
                <a href="index.php" class="block w-full py-4 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-2xl font-bold text-lg transition-all">
                    <i class="fa-solid fa-house mr-2 text-sm text-slate-400"></i> Back to Home
                </a>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Redirecting to Login</p>
                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 progress-bar"></div>
                </div>
            </div>
        </div>

        <p class="mt-8 text-slate-400 text-[11px] font-bold uppercase tracking-widest">
            Session Terminated &bull; <?php echo date('H:i:s'); ?>
        </p>
    </div>

    <script>
        // 5 seconds baad login.php par khud hi chala jayega
        setTimeout(function(){
            window.location.href = 'login.php';
        }, 5000);
    </script>

</body>
</html>