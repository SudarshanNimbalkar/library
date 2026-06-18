<?php require_once __DIR__ . '/header.php'; ?>
<div id="page-booking" class="page active">
    <div class="bg-orbs"><div class="orb orb1"></div><div class="orb orb2"></div></div>
    <nav class="navbar">
        <div class="logo" onclick="window.location.href='index.php'" style="cursor:pointer"><span class="logo-icon">📚</span><span class="logo-text">saraswati library</span></div>
        <div class="nav-actions"><button class="btn-ghost" onclick="window.location.href='login.php'">Sign In</button><button class="btn-primary" onclick="window.location.href='register.php'">Register</button></div>
    </nav>
    <div class="booking-container">
        <div class="booking-header"><h2 class="section-title">Select Your <span class="gradient-text">Table</span></h2><p class="section-sub">Choose your preferred table, date, time slot and subscription plan.</p></div>
        <div class="date-time-bar">
            <div class="dt-group"><label>Booking date</label><input type="date" id="booking-date" class="dt-input"></div>
            <div class="dt-group"><label>Time slot</label><select id="booking-time" class="dt-input"><option value="Full Day">Full Day</option><option value="8AM-2PM">8AM - 2PM</option><option value="2PM-10PM">2PM - 10PM</option></select></div>
        </div>
        <div class="spec-strip"><span>❄️ AC Room</span><span>💡 Special Spotlight</span><span>📶 Free WiFi</span><span>🔌 Charging Point</span><span>📱 PhonePe Payment</span></div>
        <div class="legend"><div class="legend-item"><div class="legend-dot available"></div>Available</div><div class="legend-item"><div class="legend-dot selected"></div>Your Selection</div><div class="legend-item"><div class="legend-dot booked"></div>Booked</div><div class="legend-item"><div class="legend-dot maintenance"></div>Maintenance</div></div>
        <div class="library-map"><div class="zone-section" id="zone-window" data-zone="window"><div class="tables-grid">
            <?php for ($i = 1; $i <= 39; $i++): ?>
                <?php if (($i - 1) % 10 === 0): ?><div class="table-row"><div class="tables-inline"><?php endif; ?>
                <div class="table-unit available" data-table="<?= $i ?>" data-zone="window" onclick="selectTable(this)"><div class="table-3d"><div class="table-top"><span class="table-num">T<?= $i ?></span><div class="seat-dots"><span class="seat-dot"></span></div></div><div class="table-shadow"></div></div><div class="table-tag">1 seat</div></div>
                <?php if ($i % 10 === 0 || $i === 39): ?></div></div><?php endif; ?>
            <?php endfor; ?>
        </div></div></div>
        <div id="booking-summary" class="booking-summary hidden"><div class="summary-inner"><div class="summary-info"><span class="summary-icon">🪑</span><div><div class="summary-table" id="summary-table-name">Table Selected</div><div class="summary-meta" id="summary-table-meta">Zone · Seats</div></div></div><label class="plan-picker">Subscription plan <select id="plan-select" class="dt-input"><option value="1" data-price="1000">1 Month — ₹1000</option><option value="2" data-price="2700">3 Months — ₹2700</option><option value="3" data-price="10000">1 Year — ₹10000</option></select></label><button class="btn-cta" onclick="proceedToAuth();"><span>Proceed to Book</span></button></div></div>
    </div>
</div>
<div id="page-confirm" class="page"><div class="auth-container"><div class="auth-card"><div class="auth-header"><div class="auth-avatar">✅</div><h2>Booking Created</h2><p id="confirm-table"></p></div><div class="auth-body"><p><strong>Date:</strong> <span id="confirm-date"></span></p><p><strong>Time:</strong> <span id="confirm-time"></span></p><p><strong>Plan:</strong> <span id="confirm-payment"></span></p><p><strong>Member:</strong> <span id="confirm-student"></span></p><button class="btn-cta full-width" onclick="window.location.href='tables.php'">Book another table</button></div></div></div></div>
<?php require_once __DIR__ . '/footer.php'; ?>
