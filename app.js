/* ===== STATE ===== */
const state = {
  selectedTable: null,
  selectedZone: null,
  selectedDate: null,
  selectedTime: null,
  isLoggedIn: false,
  user: JSON.parse(localStorage.getItem('saraswati_user') || 'null'),
  fromBooking: false,
};

/* ===== PAGE NAVIGATION ===== */
function showPage(pageId) {
  document.querySelectorAll('.page').forEach(p => {
    p.classList.remove('active');
    p.style.display = 'none';
  });
  const page = document.getElementById(pageId);
  page.style.display = 'block';
  // Trigger reflow for animation
  page.offsetHeight;
  page.classList.add('active');
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function proceedToAuth() {
  const date = document.getElementById('booking-date').value;
  const time = document.getElementById('booking-time').value;

  if (!date || !time) {
    showToast('⚠️ Please select a date and time slot first!', 'warn');
    return;
  }

  state.selectedDate = date;
  state.selectedTime = time;
  state.fromBooking = true;

  if (state.isLoggedIn) {
    confirmBooking();
  } else {
    // Show login with a note to register if not yet
    showPage('page-login');
    showToast('🔐 Sign in to complete your booking', 'info');
  }
}

function returnToBooking() {
  showPage('page-booking');
}

/* ===== TABLE SELECTION ===== */
function selectTable(el) {
  if (el.classList.contains('booked') || el.classList.contains('maintenance')) return;

  // Deselect previous
  if (state.selectedTable) {
    const prev = document.querySelector(`[data-table="${state.selectedTable}"]`);
    if (prev) prev.classList.remove('selected');
  }

  // Select new
  el.classList.add('selected');
  state.selectedTable = el.dataset.table;
  state.selectedZone = el.dataset.zone;

  // Ripple effect
  ripple(el);

  // Update summary panel
  const zoneLabels = {
    window: '🪟 Window Zone',
    silent: '🤫 Silent Zone',
    group: '👥 Group Zone',
    power: '⚡ Power Zone',
  };
  const seatsMap = {
    window: '2 seats',
    silent: '1 seat',
    group: '4 seats',
    power: '2 seats · Power outlet',
  };

  document.getElementById('summary-table-name').textContent = `Table ${state.selectedTable}`;
  document.getElementById('summary-table-meta').textContent = `${zoneLabels[state.selectedZone]} · ${seatsMap[state.selectedZone]}`;

  const summary = document.getElementById('booking-summary');
  summary.classList.remove('hidden');

  showToast(`✅ Table ${state.selectedTable} selected!`);
}

function ripple(el) {
  const r = document.createElement('div');
  r.style.cssText = `
    position:absolute;width:60px;height:60px;border-radius:50%;
    background:rgba(124,58,237,0.3);transform:scale(0);
    animation:rippleAnim 0.6s ease-out forwards;pointer-events:none;
    left:50%;top:50%;margin:-30px 0 0 -30px;z-index:10;
  `;
  el.style.position = 'relative';
  el.appendChild(r);
  setTimeout(() => r.remove(), 700);
}

if (!document.getElementById('ripple-style')) {
  const style = document.createElement('style');
  style.id = 'ripple-style';
  style.textContent = `@keyframes rippleAnim { to { transform:scale(3); opacity:0; } }`;
  document.head.appendChild(style);
}

/* ===== ZONE FILTER ===== */
function filterZone() {
  const val = document.getElementById('zone-filter').value;
  document.querySelectorAll('.zone-section').forEach(section => {
    if (val === 'all' || section.dataset.zone === val) {
      section.classList.remove('hidden');
    } else {
      section.classList.add('hidden');
    }
  });
}

/* ===== AUTH HANDLERS ===== */
function handleLogin() {
  const email = document.getElementById('login-email').value.trim();
  const pass = document.getElementById('login-password').value;

  if (!email || !pass) {
    showToast('⚠️ Please fill in all fields', 'warn');
    return;
  }
  if (!isValidEmail(email)) {
    showToast('⚠️ Enter a valid email address', 'warn');
    return;
  }

  showToast('🔄 Signing you in…', 'info');
  apiPost({ action: 'login', email, password: pass }).then(data => {
    if (!data.ok) { showPage('page-register'); showToast('📝 Account not found. Please register to continue.', 'warn'); return; }
    state.isLoggedIn = true; state.user = data.user; localStorage.setItem('saraswati_user', JSON.stringify(data.user));
    if (state.fromBooking && state.selectedTable) confirmBooking();
    else { showPage('page-booking'); showToast('👋 Welcome back!'); }
  });
}

function handleRegister() {
  const terms = document.getElementById('terms-check').checked;
  if (!terms) {
    showToast('⚠️ Please accept the Library Rules to continue', 'warn');
    return;
  }
  const first = document.getElementById('reg-first').value.trim();
  const last = document.getElementById('reg-last').value.trim();
  const email = document.getElementById('reg-email').value.trim();
  const phone = document.getElementById('reg-phone').value.trim();
  const password = document.getElementById('reg-password').value;
  if (!first || !email || !password) { showToast('⚠️ Name, email and password are required', 'warn'); return; }
  showToast('🔄 Creating your account…', 'info');
  apiPost({ action: 'register', name: `${first} ${last}`.trim(), email, phone, password }).then(data => {
    if (!data.ok) { showToast(data.message || 'Registration failed', 'warn'); showPage('page-login'); return; }
    state.isLoggedIn = true; state.user = data.user; localStorage.setItem('saraswati_user', JSON.stringify(data.user));
    if (state.fromBooking && state.selectedTable) confirmBooking();
    else { showPage('page-booking'); showToast('🎉 Account created! Start booking your table.'); }
  });
}

/* ===== BOOKING CONFIRMATION ===== */
async function confirmBooking() {
  const zoneLabels = {
    window: 'Window Zone',
    silent: 'Silent Zone',
    group: 'Group Zone',
    power: 'Power Zone',
  };

  const dateStr = state.selectedDate
    ? new Date(state.selectedDate).toLocaleDateString('en-IN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
    : 'Today';

  document.getElementById('confirm-table').textContent =
    `Table ${state.selectedTable} — ${zoneLabels[state.selectedZone] || ''}`;
  document.getElementById('confirm-date').textContent = dateStr;
  document.getElementById('confirm-time').textContent = state.selectedTime || '10:00 AM – 12:00 PM';
  const plan = document.getElementById('plan-select');
  const planText = plan ? plan.options[plan.selectedIndex].textContent : '1 Month — ₹1000';
  const payment = await apiPost({ action: 'book', user_id: state.user?.id || 0, table_id: state.selectedTable, date: state.selectedDate, slot: state.selectedTime, plan_id: plan?.value || 1 });
  if (!payment.ok) { showToast(payment.message || 'Booking failed', 'warn'); return; }
  document.getElementById('confirm-payment').textContent = `${planText} · PhonePe ₹${payment.amount} pending`;
  document.getElementById('confirm-student').textContent = state.user?.name || 'Member';

  showPage('page-confirm');

  // Mark that table as booked in the UI
  const tableEl = document.querySelector(`[data-table="${state.selectedTable}"]`);
  if (tableEl) {
    tableEl.classList.remove('available', 'selected');
    tableEl.classList.add('booked');
    tableEl.removeAttribute('onclick');
    tableEl.querySelector('.table-tag').textContent = 'Booked';
  }

  // Reset state
  state.selectedTable = null;
  state.selectedZone = null;
  state.fromBooking = false;

  // Hide summary panel
  const summary = document.getElementById('booking-summary');
  if (summary) summary.classList.add('hidden');
}

/* ===== TOAST NOTIFICATIONS ===== */
function showToast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  const colors = {
    success: 'rgba(16,185,129,0.15)',
    warn: 'rgba(245,158,11,0.15)',
    info: 'rgba(124,58,237,0.15)',
  };
  toast.style.background = `linear-gradient(135deg, var(--surface2), ${colors[type]})`;
  toast.style.borderColor = type === 'success' ? 'rgba(16,185,129,0.3)' : type === 'warn' ? 'rgba(245,158,11,0.3)' : 'var(--border-strong)';
  toast.textContent = msg;
  toast.classList.remove('hidden');
  toast.offsetHeight; // reflow
  toast.classList.add('show');

  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.classList.add('hidden'), 400);
  }, 2800);
}

async function apiPost(payload) {
  const body = new URLSearchParams(payload);
  const res = await fetch('api.php', { method: 'POST', body });
  return res.json();
}

async function refreshTables() {
  const date = document.getElementById('booking-date')?.value || '';
  const slot = document.getElementById('booking-time')?.value || '';
  if (!date) return;
  const res = await fetch(`api.php?action=tables&date=${encodeURIComponent(date)}&slot=${encodeURIComponent(slot)}`);
  const data = await res.json();
  if (!data.ok) return;
  data.tables.forEach(t => {
    const el = document.querySelector(`[data-table="${t.id}"]`);
    if (!el) return;
    el.classList.remove('available', 'booked', 'maintenance', 'selected');
    el.classList.add(t.live_status);
    if (t.live_status === 'available') el.setAttribute('onclick', 'selectTable(this)'); else el.removeAttribute('onclick');
    const tag = el.querySelector('.table-tag');
    if (tag) tag.textContent = t.live_status === 'available' ? `${t.seats} seat${t.seats > 1 ? 's' : ''}${t.zone === 'power' ? ' ⚡' : ''}` : (t.live_status === 'maintenance' ? '🔧 Maint.' : 'Booked');
  });
}

/* ===== UTILS ===== */
function isValidEmail(e) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);
}

/* ===== INIT ===== */
document.addEventListener('DOMContentLoaded', () => {
  state.isLoggedIn = Boolean(state.user);
  // Set default date to today
  const today = new Date().toISOString().split('T')[0];
  const dateInput = document.getElementById('booking-date');
  if (dateInput) {
    dateInput.value = today;
    dateInput.min = today;
  }

  document.getElementById('booking-date')?.addEventListener('change', refreshTables);
  document.getElementById('booking-time')?.addEventListener('change', refreshTables);
  setTimeout(refreshTables, 400);

  // Subtle 3D tilt on zone cards (landing page)
  document.querySelectorAll('.zone-card').forEach(card => {
    card.addEventListener('mousemove', e => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      card.style.transform = `translateY(-8px) rotateX(${-y * 14}deg) rotateY(${x * 14}deg)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
    card.addEventListener('click', () => {
      showPage('page-booking');
      const zone = card.querySelector('.zone-name').textContent.split(' ')[0].toLowerCase();
      setTimeout(() => {
        document.getElementById('zone-filter').value = zone;
        filterZone();
      }, 300);
    });
  });

  // Animate tables on booking page load
  document.querySelectorAll('.table-unit').forEach((t, i) => {
    t.style.animationDelay = `${i * 0.03}s`;
    t.style.animation = `cardIn 0.5s ease ${i * 0.03}s both`;
  });

  // Password strength meter
  const pwdInput = document.querySelector('#page-register .auth-input[type="password"]');
  if (pwdInput) {
    pwdInput.addEventListener('input', () => {
      const val = pwdInput.value;
      const fill = document.querySelector('.strength-fill');
      const label = document.querySelector('.strength-label');
      let strength = 0;
      if (val.length >= 8) strength++;
      if (/[A-Z]/.test(val)) strength++;
      if (/[0-9]/.test(val)) strength++;
      if (/[^A-Za-z0-9]/.test(val)) strength++;
      const map = [
        { w: '0%', color: '#f43f5e', text: 'Enter a password' },
        { w: '25%', color: '#f43f5e', text: 'Too weak' },
        { w: '50%', color: '#f59e0b', text: 'Fair' },
        { w: '75%', color: '#60a5fa', text: 'Good' },
        { w: '100%', color: '#10b981', text: 'Strong ✓' },
      ];
      const s = map[strength] || map[0];
      fill.style.width = s.w;
      fill.style.background = s.color;
      label.textContent = s.text;
      label.style.color = s.color;
    });
  }

  // Start on landing page
  showPage('page-landing');
});
