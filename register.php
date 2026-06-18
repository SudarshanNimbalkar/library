<?php require_once __DIR__ . '/header.php'; ?>
<div id="page-register" class="page active">
    <div class="bg-orbs"><div class="orb orb2"></div><div class="orb orb3"></div></div>
    <nav class="navbar">
        <div class="logo" onclick="window.location.href='index.php'" style="cursor:pointer">
            <span class="logo-icon">📚</span><span class="logo-text">saraswati library</span>
        </div>
        <div class="nav-actions"><button class="btn-ghost" onclick="window.location.href='login.php'">Sign In</button></div>
    </nav>
    <div class="auth-container">
        <div class="auth-card card-3d-static">
            <div class="auth-header"><div class="auth-avatar">✨</div><h2>Create Account</h2><p>Join Saraswati Library and start booking</p></div>
            <div class="auth-body">
                <div class="form-row">
                    <div class="input-group"><label>First Name</label><div class="input-wrap"><span class="input-icon">👤</span><input type="text" id="reg-first" placeholder="Aarav" class="auth-input" /></div></div>
                    <div class="input-group"><label>Last Name</label><div class="input-wrap"><span class="input-icon">👤</span><input type="text" id="reg-last" placeholder="Shah" class="auth-input" /></div></div>
                </div>
                <div class="input-group"><label>Email Address</label><div class="input-wrap"><span class="input-icon">✉️</span><input type="email" id="reg-email" placeholder="you@college.edu" class="auth-input" /></div></div>
                <div class="input-group"><label>Phone Number</label><div class="input-wrap"><span class="input-icon">📱</span><input type="tel" id="reg-phone" placeholder="+91 98765 43210" class="auth-input" /></div></div>
                <div class="input-group"><label>Password</label><div class="input-wrap"><span class="input-icon">🔒</span><input type="password" id="reg-password" placeholder="Min. 8 characters" class="auth-input" /></div></div>
                <div class="password-strength"><div class="strength-bar"><div class="strength-fill" style="width:0%"></div></div><span class="strength-label">Enter a password</span></div>
                <label class="checkbox-label terms"><input type="checkbox" id="terms-check"> I agree to the Library Rules and Terms of Use</label>
                <button class="btn-cta full-width" onclick="handleRegister()"><span>Create Account</span></button>
                <p class="auth-switch">Already registered? <a href="login.php" class="link-text">Sign in →</a></p>
                <div class="auth-back"><button class="btn-ghost small" onclick="window.location.href='tables.php'">← Back to Table Selection</button></div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
