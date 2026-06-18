<?php
require_once __DIR__ . '/includes/header.php';
?>
<!-- REGISTER PAGE -->
<!-- <div id="page-register" class="page"> -->
<div class="bg-orbs">
    <div class="orb orb2"></div>
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
            <div class="auth-avatar">✨</div>
            <h2>Create Account</h2>
            <p>Join saraswati library and start booking</p>
        </div>

        <div class="auth-body">
            <div class="form-row">
                <div class="input-group">
                    <label>First Name</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" id="reg-first" placeholder="Aarav" class="auth-input" />
                    </div>
                </div>
                <div class="input-group">
                    <label>Last Name</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" id="reg-last" placeholder="Shah" class="auth-input" />
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label>Student / Staff ID</label>
                <div class="input-wrap">
                    <span class="input-icon">🎓</span>
                    <input type="text" placeholder="LIB-2024-XXXX" class="auth-input" />
                </div>
            </div>
            <div class="input-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input type="email" id="reg-email" placeholder="you@college.edu" class="auth-input" />
                </div>
            </div>
            <div class="input-group">
                <label>Phone Number</label>
                <div class="input-wrap">
                    <span class="input-icon">📱</span>
                    <input type="tel" id="reg-phone" placeholder="+91 98765 43210" class="auth-input" />
                </div>
            </div>
            <div class="input-group">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="reg-password" placeholder="Min. 8 characters" class="auth-input" />
                </div>
            </div>
            <div class="password-strength">
                <div class="strength-bar">
                    <div class="strength-fill" style="width:0%"></div>
                </div>
                <span class="strength-label">Enter a password</span>
            </div>

            <label class="checkbox-label terms">
                <input type="checkbox" id="terms-check">
                I agree to the <a href="#" class="link-text">Library Rules</a> and <a href="#" class="link-text">Terms of
                    Use</a>
            </label>

            <button class="btn-cta full-width" onclick="handleRegister()">
                <span>Create Account</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>

            <div class="auth-divider"><span>or</span></div>
            <p class="auth-switch">Already registered? <a href="#" class="link-text" onclick="showPage('page-login')">Sign
                    in →</a></p>

            <div class="auth-back">
                <button class="btn-ghost small" onclick="returnToBooking()">← Back to Table Selection</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast hidden"></div>
</div>
</div>
<script src="assets/js/app.js"></script>