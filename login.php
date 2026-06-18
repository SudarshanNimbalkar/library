<?php require_once __DIR__ . '/header.php'; ?>
<div id="page-login" class="page active">
    <div class="bg-orbs"><div class="orb orb1"></div><div class="orb orb3"></div></div>
    <nav class="navbar">
        <div class="logo" onclick="window.location.href='index.php'" style="cursor:pointer">
            <span class="logo-icon">📚</span><span class="logo-text">saraswati library</span>
        </div>
        <div class="nav-actions"><button class="btn-ghost" onclick="window.location.href='register.php'">Register</button></div>
    </nav>
    <div class="auth-container">
        <div class="auth-card card-3d-static">
            <div class="auth-header"><div class="auth-avatar">📚</div><h2>Welcome Back</h2><p>Sign in to manage your bookings</p></div>
            <div class="auth-body">
                <div class="input-group"><label>Email Address</label><div class="input-wrap"><span class="input-icon">✉️</span><input type="email" id="login-email" placeholder="you@example.com" class="auth-input" /></div></div>
                <div class="input-group"><label>Password</label><div class="input-wrap"><span class="input-icon">🔒</span><input type="password" id="login-password" placeholder="••••••••" class="auth-input" /></div></div>
                <button class="btn-cta full-width" onclick="handleLogin()"><span>Sign In</span></button>
                <p class="auth-switch">New to Saraswati Library? <a href="register.php" class="link-text">Create an account →</a></p>
                <div class="auth-back"><button class="btn-ghost small" onclick="window.location.href='tables.php'">← Back to Table Selection</button></div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
