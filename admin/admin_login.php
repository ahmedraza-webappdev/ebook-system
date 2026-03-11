<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --ink:#0d0d0d; --ink-soft:#111827; --surface:#1c2333; --border:rgba(255,255,255,0.07); --gold:#c9a84c; --gold-light:#e8c96a; --muted:rgba(255,255,255,0.35); --text:#f0ece4; }
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'DM Sans',sans-serif;background:var(--ink-soft);min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
body::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 20% 50%,rgba(201,168,76,0.05) 0%,transparent 70%),radial-gradient(ellipse 50% 70% at 80% 20%,rgba(255,255,255,0.02) 0%,transparent 60%);}
.box{position:relative;z-index:5;width:100%;max-width:400px;padding:20px;}
.brand{text-align:center;margin-bottom:32px;}
.brand-name{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;}
.brand-name span{color:var(--gold);}
.brand-sub{font-size:0.62rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--muted);margin-top:3px;}
.login-card{background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:34px;}
.login-title{font-family:'Cormorant Garamond',serif;font-size:1.45rem;font-weight:600;color:#fff;margin-bottom:4px;}
.login-sub{font-size:0.78rem;color:var(--muted);margin-bottom:26px;}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:0.67rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;}
.input-wrap{position:relative;}
.input-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.18);font-size:0.8rem;}
input{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:5px;padding:10px 12px 10px 36px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:0.86rem;outline:none;transition:border-color 0.2s,box-shadow 0.2s;}
input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,0.09);}
input::placeholder{color:rgba(255,255,255,0.15);}
button{width:100%;padding:11px;background:var(--gold);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:0.78rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;border:none;border-radius:5px;cursor:pointer;margin-top:6px;transition:background 0.2s;}
button:hover{background:var(--gold-light);}
.footer{text-align:center;margin-top:18px;font-size:0.68rem;color:var(--muted);}
</style>
</head>
<body>
<div class="box">
    <div class="brand">
        <div class="brand-name">📚 <span>E-Library</span></div>
        <div class="brand-sub">Admin Portal</div>
    </div>
    <div class="login-card">
        <div class="login-title">Welcome back</div>
        <div class="login-sub">Sign in to access the management panel</div>
        <form method="POST" action="admin_login_process.php">
            <div class="form-group">
                <label class="form-label">Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Enter username">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="••••••••">
                </div>
            </div>
            <button type="submit"><i class="fa-solid fa-right-to-bracket" style="margin-right:6px;"></i>Login</button>
        </form>
    </div>
    <div class="footer">E-Library System © <?php echo date('Y'); ?></div>
</div>
</body>
</html>
