/* =====================================================
   QUIZ APP — ADMIN JS
   ===================================================== */

document.addEventListener('DOMContentLoaded', function () {

  /* ---- Sidebar toggle ---- */
  const sidebar  = document.getElementById('adminSidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const toggle   = document.getElementById('sidebarToggle');

  function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('show');    toggle?.classList.add('is-open'); }
  function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); toggle?.classList.remove('is-open'); }

  if (toggle)  toggle.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
  if (overlay) overlay.addEventListener('click', closeSidebar);

  /* ---- Live clock ---- */
  const clock = document.getElementById('topbarClock');
  if (clock) {
    function tick() {
      const now = new Date();
      clock.textContent = now.toLocaleTimeString('en-GB', { hour12: false });
    }
    tick();
    setInterval(tick, 1000);
  }

  /* ---- Auto-dismiss flash alerts ---- */
  document.querySelectorAll('.alert-floating').forEach(el => {
    const hide = () => { el.classList.add('is-hiding'); setTimeout(() => el.remove(), 350); };
    setTimeout(hide, 4000);
    el.querySelector('.close-alert')?.addEventListener('click', hide);
  });

  initOceanScene();
  initMotion();

  /* ---- Tooltip init ---- */
  if (typeof $ !== 'undefined' && $.fn.tooltip) {
    $('[title]').tooltip({ trigger: 'hover', delay: { show: 300, hide: 0 } });
  }
});

/* ---- Ocean background: fill stars and bubbles (partials/ocean_scene.php) ---- */
function initOceanScene() {
  const scene = document.querySelector('.ocean-scene');
  if (!scene) return;
  const rand = (min, max) => Math.random() * (max - min) + min;

  const stars = scene.querySelector('.stars');
  for (let i = 0; i < 40; i++) {
    const s = document.createElement('span');
    s.style.left = rand(0, 100) + '%';
    s.style.top  = rand(0, 55) + '%';
    s.style.animationDelay = rand(0, 3) + 's';
    stars.appendChild(s);
  }

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const bubbles = scene.querySelector('.bubbles');
  for (let i = 0; i < 16; i++) {
    const b = document.createElement('span');
    const size = rand(6, 18);
    b.style.width = b.style.height = size + 'px';
    b.style.left = rand(0, 100) + '%';
    b.style.animationDuration = rand(8, 16) + 's';
    b.style.animationDelay = rand(0, 10) + 's';
    b.style.setProperty('--sway', rand(-40, 40) + 'px');
    bubbles.appendChild(b);
  }
}

/* ---- Motion: stagger indexes, count-up stats, ripples, page transitions ---- */
function initMotion() {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Stagger index consumed by CSS animation-delay
  document.querySelectorAll('.sidebar-nav > *').forEach((el, i) => el.style.setProperty('--i', i));
  document.querySelectorAll('.admin-table tbody').forEach(tbody => {
    [...tbody.rows].forEach((row, i) => row.style.setProperty('--i', Math.min(i, 15)));
  });

  // Count up numeric stat values ("12", "85.5%")
  if (!reduced) {
    document.querySelectorAll('.stat-value, .ms-value').forEach(el => {
      const m = el.textContent.trim().match(/^(\d+(?:\.\d+)?)(.*)$/);
      if (!m) return;
      const target = parseFloat(m[1]), suffix = m[2];
      const decimals = (m[1].split('.')[1] || '').length;
      const start = performance.now(), duration = 1100;
      const step = now => {
        const t = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - t, 4);
        el.textContent = (target * eased).toFixed(decimals) + suffix;
        if (t < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    });
  }

  // Ripple on buttons
  document.addEventListener('pointerdown', e => {
    const btn = e.target.closest('.btn, .quick-action-btn');
    if (!btn || reduced) return;
    const r = btn.getBoundingClientRect();
    const dot = document.createElement('span');
    dot.className = 'ripple';
    dot.style.left = (e.clientX - r.left) + 'px';
    dot.style.top  = (e.clientY - r.top) + 'px';
    btn.appendChild(dot);
    setTimeout(() => dot.remove(), 600);
  });

  // Loading bar + fade-out when leaving the page
  const bar = document.createElement('div');
  bar.className = 'page-progress';
  document.body.appendChild(bar);

  const leave = () => document.body.classList.add('page-leaving');
  document.addEventListener('click', e => {
    const a = e.target.closest('a[href]');
    if (!a || e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) return;
    if (a.target === '_blank' || a.hasAttribute('download') || a.getAttribute('href').startsWith('#')) return;
    if (a.origin !== location.origin) return;
    leave();
  });
  // Runs after other submit handlers, so skip if one cancelled the submit
  document.addEventListener('submit', e => { setTimeout(() => { if (!e.defaultPrevented) leave(); }); });
  // Restore when coming back via the browser's back/forward cache
  window.addEventListener('pageshow', e => { if (e.persisted) document.body.classList.remove('page-leaving'); });
}

/* ---- Toast helper (usable from inline scripts) ---- */
function showToast(msg, type = 'success') {
  const colors = {
    success: 'linear-gradient(135deg, #059669, #10b981)',
    error:   'linear-gradient(135deg, #dc2626, #ef4444)',
    info:    'linear-gradient(135deg, #1d4ed8, #3b82f6)',
  };
  const t = document.createElement('div');
  t.className = 'alert-floating';
  t.style.cssText = `background:${colors[type] || colors.success};animation:slideInRight .3s ease`;
  t.innerHTML = `<i class="fas fa-${type === 'error' ? 'times' : 'check'}-circle"></i> ${msg}
    <button class="close-alert" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
  document.body.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 3500);
}

/* ---- copyUrl (used by dashboard) ---- */
function copyUrl() {
  const el = document.getElementById('quizUrl');
  if (!el) return;
  navigator.clipboard.writeText(el.textContent.trim()).then(() => showToast('Quiz URL copied to clipboard!'));
}
