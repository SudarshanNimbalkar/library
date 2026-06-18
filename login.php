<!-- LOGIN PAGE -->
<div id="page-login" class="page">
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb3"></div>
    </div>
    <nav class="navbar">
        <div class="logo" onclick="showPage('page-landing')" style="cursor:pointer">
            <span class="logo-icon">📚</span><span class="logo-text">saraswati library</span>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card card-3d-static">
            <div class="auth-header">
                <div class="auth-avatar">📚</div>
                <h2>Welcome Back</h2>
                <p>Sign in to manage your bookings</p>
            </div>

            <div class="auth-body">
                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input type="email" id="login-email" placeholder="you@example.com" class="auth-input" />
                    </div>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="login-password" placeholder="••••••••" class="auth-input" />
                    </div>
                </div>
                <div class="auth-meta">
                    <label class="checkbox-label"><input type="checkbox"> Remember me</label>
                    <a href="#" class="link-text">Forgot password?</a>
                </div>
                <button class="btn-cta full-width" onclick="handleLogin()">
                    <span>Sign In</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="auth-divider"><span>or</span></div>
                <p class="auth-switch">New to saraswati library? <a href="#" class="link-text"
                        onclick="showPage('page-register')">Create an account →</a></p>

                <div class="auth-back">
                    <button class="btn-ghost small" onclick="returnToBooking()">← Back to Table Selection</button>
                </div>
            </div>
        </div>
    </div>
</div>