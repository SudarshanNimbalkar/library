<?php
require_once 'includes/header.php';
?>
<br>
<br>

<div class="booking-container">
    <div class="booking-header">
        <h2 class="section-title">Select Your <span class="gradient-text">Table</span></h2>
        <p class="section-sub">Choose a your preferred table below and then select plan </p>
    </div>
    <!-- map info -->
    <div class="spec-strip">
        <span>❄️ AC Room</span><span>💡 Special Spotlight</span><span>📶 Free WiFi</span><span>🔌 Laptop Charging Point</span><span>📱 PhonePe Payment</span>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="legend-dot available"></div>Available
        </div>
        <div class="legend-item">
            <div class="legend-dot selected"></div>Your Selection
        </div>
        <div class="legend-item">
            <div class="legend-dot booked"></div>Booked
        </div>
        <div class="legend-item">
            <div class="legend-dot maintenance"></div>Maintenance
        </div>
    </div>

    <!-- Library Floor Map -->
    <div class="library-map">
        <div class="zone-section" id="zone-window" data-zone="window">
            <div class="tables-grid">
                <?php for ($i = 1; $i <= 39; $i++): ?>

                    <?php if (($i - 1) % 10 == 0): ?>
                        <div class="table-row">
                            <div class="tables-inline">
                            <?php endif; ?>

                            <div class="table-unit available"
                                data-table="<?= $i ?>"
                                data-zone="window"
                                onclick="selectTable(this)">

                                <div class="table-3d">
                                    <div class="table-top">
                                        <span class="table-num">T<?= $i ?></span>
                                        <div class="seat-dots">
                                            <span class="seat-dot"></span>
                                        </div>
                                    </div>
                                    <div class="table-shadow"></div>
                                </div>
                                <div class="table-tag">1 seat</div>
                            </div>

                            <?php if ($i % 10 == 0 || $i == 39): ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>

            <!-- Booking Summary Panel (slides up) -->
            <div id="booking-summary" class="booking-summary hidden">
                <div class="summary-inner">
                    <div class="summary-info">
                        <span class="summary-icon">🪑</span>
                        <div>
                            <div class="summary-table" id="summary-table-name">Table Selected</div>
                            <div class="summary-meta" id="summary-table-meta">Zone · Seats</div>
                        </div>
                    </div>
                    <label class="plan-picker">Subscription plan <select id="plan-select" class="dt-input">
                            <option value="1" data-price="1000">1 Month — ₹1000</option>
                            <option value="2" data-price="2700">3 Months — ₹2700</option>
                            <option value="3" data-price="10000">1 Year — ₹10000</option>
                        </select></label>
                    <button class="btn-cta" onclick="proceedToAuth();">
                        <span>Proceed to Book</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <?php require_once 'includes/footer.php'; ?>