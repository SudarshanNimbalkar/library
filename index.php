<?php
require __DIR__ . '/includes/header.php'; ?>



<div id="page-landing" class="page active">
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
    </div>
    <nav class="navbar">
        <div class="logo">
            <span class="logo-icon">📚</span>
            <span class="logo-text">saraswati library</span>
        </div>
        <div class="nav-actions">
            <button class="btn-ghost" onclick="window.location.href='login.php'">Sign In</button>
            <button class="btn-primary" onclick="window.location.href='register.php'">Register</button>
        </div>
    </nav>

    <div class="hero">
        <div class="hero-badge">✦ 39 Premium Study Spaces</div>
        <h1 class="hero-title">Reserve Your <span class="gradient-text">Perfect Spot</span> in the Library</h1>
        <p class="hero-subtitle">Choose your table like seat map, subscribe, pay with PhonePe, and walk in ready to focus. AC room, special spotlight, free WiFi, and laptop charging points included.</p>
        <div class="hero-actions">
            <button class="btn-cta" onclick="window.location.href='tables.php'">
                <span>Browse Tables</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
            <div class="hero-stats">
                <div class="stat"><span class="stat-num">39</span><span class="stat-label">Tables</span></div>
                <div class="stat-div"></div>
                <div class="stat"><span class="stat-num">8AM</span><span class="stat-label">Opens</span></div>
                <div class="stat-div"></div>
                <div class="stat"><span class="stat-num">10PM</span><span class="stat-label">Closes</span></div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>