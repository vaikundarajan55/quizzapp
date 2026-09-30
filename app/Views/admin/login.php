<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Login — Quiz Admin</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Admin CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/css/admin.css') ?>" />

  <style>
    /* ---------- Scene ---------- */
    .login-scene {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px 16px 140px;
    }

    /* ---------- Card ---------- */
    .login-card {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 390px;
      padding: 36px 30px 28px;
      border-radius: 20px;
      background: rgba(28, 35, 51, .72);
      border: 1px solid rgba(255,255,255,.1);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      box-shadow: 0 30px 60px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.06);
      animation: cardIn .9s var(--ease-spring) both;
      transform-style: preserve-3d;
      transition: transform .2s ease-out;
    }
    /* Animated gradient border */
    .login-card::before {
      content: "";
      position: absolute;
      inset: -1px;
      border-radius: inherit;
      padding: 1px;
      background: linear-gradient(120deg, transparent 20%, var(--adm-accent), var(--adm-accent-2), transparent 80%);
      background-size: 300% 300%;
      -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite: xor;
              mask-composite: exclude;
      animation: borderFlow 6s linear infinite;
      pointer-events: none;
    }
    @keyframes cardIn {
      from { opacity: 0; transform: translateY(40px) scale(.94); }
      to   { opacity: 1; transform: none; }
    }

    .login-card.shake { animation: cardIn .9s var(--ease-spring) both, shake .5s .5s ease both; }
    @keyframes shake {
      10%, 90% { transform: translateX(-2px); }
      20%, 80% { transform: translateX(4px); }
      30%, 50%, 70% { transform: translateX(-8px); }
      40%, 60% { transform: translateX(8px); }
    }

    /* Staggered content entrance */
    .stagger > * { animation: fadeUp .6s var(--ease-out) both; }
    .stagger > *:nth-child(1) { animation-delay: .25s; }
    .stagger > *:nth-child(2) { animation-delay: .35s; }
    .stagger > *:nth-child(3) { animation-delay: .45s; }
    .stagger > *:nth-child(4) { animation-delay: .55s; }
    .stagger > *:nth-child(5) { animation-delay: .65s; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: none; }
    }

    /* ---------- Brand ---------- */
    .login-brand { text-align: center; margin-bottom: 26px; }
    .login-logo {
      position: relative;
      width: 72px; height: 72px;
      margin: 0 auto 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 34px;
      border-radius: 20px;
      background: linear-gradient(135deg, var(--adm-accent), var(--adm-accent-2));
      box-shadow: 0 10px 30px rgba(108,92,231,.45);
      animation: bob 3.2s ease-in-out infinite;
    }
    .login-logo::after {
      content: "";
      position: absolute;
      inset: -6px;
      border-radius: 24px;
      border: 2px solid rgba(108,92,231,.5);
      animation: ping 2.4s ease-out infinite;
    }
    @keyframes bob {
      0%, 100% { transform: translateY(0) rotate(-4deg); }
      50%      { transform: translateY(-8px) rotate(4deg); }
    }
    @keyframes ping {
      0%   { transform: scale(.9); opacity: .9; }
      100% { transform: scale(1.35); opacity: 0; }
    }
    @keyframes textShine  { to { background-position: -300% 0; } }
    @keyframes borderFlow { to { background-position: 300% 0; } }
    @keyframes btnShine {
      0%        { transform: translateX(-150%) skewX(-20deg); }
      40%, 100% { transform: translateX(350%) skewX(-20deg); }
    }
    .login-brand h1 {
      font-size: 22px; font-weight: 800; margin: 0;
      background: linear-gradient(90deg, #fff, #c9c3ff, #7ff3e1, #fff);
      background-size: 300% 100%;
      -webkit-background-clip: text; background-clip: text;
      color: transparent;
      animation: textShine 6s linear infinite;
    }
    .login-brand p { font-size: 13px; color: var(--adm-muted); margin: 6px 0 0; }

    /* ---------- Floating-label fields ---------- */
    .field {
      position: relative;
      margin-bottom: 18px;
    }
    .field input {
      width: 100%;
      height: 52px;
      padding: 18px 44px 6px 44px;
      background: rgba(15,17,23,.6);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      color: var(--adm-text);
      font-size: 15px;
      outline: none;
      transition: border-color .25s, box-shadow .25s, background .25s;
    }
    .field input:focus {
      border-color: var(--adm-accent);
      background: rgba(15,17,23,.85);
      box-shadow: 0 0 0 4px rgba(108,92,231,.2);
    }
    .field label {
      position: absolute;
      left: 44px; top: 16px;
      margin: 0;
      font-size: 14px;
      color: var(--adm-muted);
      pointer-events: none;
      transition: transform .25s var(--ease-out), font-size .25s, color .25s;
      transform-origin: left top;
    }
    .field input:focus + label,
    .field input:not(:placeholder-shown) + label {
      transform: translateY(-10px);
      font-size: 11px;
      color: var(--adm-accent-2);
      letter-spacing: .04em;
      text-transform: uppercase;
      font-weight: 700;
    }
    .field .field-icon {
      position: absolute;
      left: 16px; top: 50%;
      transform: translateY(-50%);
      color: var(--adm-muted);
      transition: color .25s, transform .3s var(--ease-spring);
    }
    .field:focus-within .field-icon { color: var(--adm-accent); transform: translateY(-50%) scale(1.15); }
    .toggle-pass {
      position: absolute;
      right: 8px; top: 50%;
      transform: translateY(-50%);
      width: 34px; height: 34px;
      border: 0; border-radius: 8px;
      background: transparent;
      color: var(--adm-muted);
      cursor: pointer;
      transition: color .2s, background .2s;
    }
    .toggle-pass:hover { color: var(--adm-text); background: rgba(255,255,255,.06); }
    .caps-warning {
      display: none;
      font-size: 12px;
      color: var(--adm-yellow);
      margin: -10px 0 12px 4px;
    }
    .caps-warning.show { display: block; animation: fadeUp .3s var(--ease-out); }

    /* ---------- Button ---------- */
    .btn-login {
      position: relative;
      width: 100%;
      height: 50px;
      margin-top: 6px;
      border: 0;
      border-radius: 12px;
      font-weight: 700;
      font-size: 15px;
      letter-spacing: .02em;
      color: #fff;
      background: linear-gradient(135deg, var(--adm-accent), #8b72ff 55%, var(--adm-accent-2));
      background-size: 200% 200%;
      box-shadow: 0 10px 24px rgba(108,92,231,.4);
      overflow: hidden;
      transition: transform .2s var(--ease-out), box-shadow .2s, background-position .6s;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(108,92,231,.55); background-position: 100% 0; color: #fff; }
    .btn-login:active { transform: translateY(0) scale(.98); }
    .btn-login::after {
      content: "";
      position: absolute;
      top: 0; bottom: 0; left: 0;
      width: 40%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
      transform: translateX(-150%) skewX(-20deg);
      animation: btnShine 3.5s 1.2s ease-in-out infinite;
    }
    .btn-login .spinner { display: none; }
    .btn-login.loading .label { visibility: hidden; }
    .btn-login.loading .spinner {
      display: block;
      position: absolute;
      top: 50%; left: 50%;
      width: 22px; height: 22px;
      margin: -11px 0 0 -11px;
      border: 3px solid rgba(255,255,255,.35);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ---------- Error + footer ---------- */
    .login-error {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.4);
      color: #fca5a5;
      border-radius: 10px;
      padding: 10px 12px;
      font-size: 13px;
      margin-bottom: 18px;
    }
    .login-footer { text-align: center; margin-top: 22px; font-size: 13px; }
    .login-footer a { color: var(--adm-muted); transition: color .2s; }
    .login-footer a i { transition: transform .3s var(--ease-spring); }
    .login-footer a:hover { color: var(--adm-text); text-decoration: none; }
    .login-footer a:hover i { transform: translateX(-4px); }

    @media (max-width: 420px) {
      .login-card { padding: 28px 20px 22px; }
    }
  </style>
</head>
<body class="admin-body login-page">

<?= view('admin/partials/ocean_scene') ?>

<div class="login-scene">
  <div class="login-card <?= session()->getFlashdata('error') ? 'shake' : '' ?>" id="loginCard">
    <div class="stagger">
      <div class="login-brand">
        <div class="login-logo">⚓</div>
        <h1>Quiz Admin</h1>
        <p>Sign in to manage the IALA Buoyage quiz</p>
      </div>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="login-error">
        <i class="fas fa-exclamation-circle"></i>
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('admin/login') ?>" autocomplete="on" id="loginForm">
        <div class="field">
          <i class="fas fa-user field-icon"></i>
          <input type="text" id="username" name="username" placeholder=" "
                 value="<?= esc(old('username') ?? '') ?>" autocomplete="username" required autofocus />
          <label for="username">Username</label>
        </div>

        <div class="field">
          <i class="fas fa-lock field-icon"></i>
          <input type="password" id="password" name="password" placeholder=" "
                 autocomplete="current-password" required />
          <label for="password">Password</label>
          <button type="button" class="toggle-pass" id="togglePass" aria-label="Show password">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        <div class="caps-warning" id="capsWarning"><i class="fas fa-exclamation-triangle mr-1"></i>Caps Lock is on</div>

        <button type="submit" class="btn-login" id="loginBtn">
          <span class="label"><i class="fas fa-sign-in-alt mr-2"></i>Log in</span>
          <span class="spinner"></span>
        </button>
      </form>

      <div class="login-footer">
        <a href="<?= base_url('/') ?>"><i class="fas fa-arrow-left mr-1"></i> Back to website</a>
      </div>
    </div>
  </div>

</div>

<script src="<?= base_url('assets/admin/js/admin.js') ?>"></script>
<script>
(function () {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Subtle 3D tilt following the pointer (desktop only)
  const card = document.getElementById('loginCard');
  if (!reduced && window.matchMedia('(hover: hover)').matches) {
    const lastAnim = card.classList.contains('shake') ? 'shake' : 'cardIn';
    card.addEventListener('animationend', function enableTilt(e) {
      if (e.target !== card || e.animationName !== lastAnim) return;
      card.removeEventListener('animationend', enableTilt);
      card.style.animation = 'none';
      document.addEventListener('pointermove', ev => {
        const x = ev.clientX / window.innerWidth  - .5;
        const y = ev.clientY / window.innerHeight - .5;
        card.style.transform = `perspective(900px) rotateY(${x * 6}deg) rotateX(${-y * 6}deg)`;
      });
    });
  }

  // Show / hide password
  const pass   = document.getElementById('password');
  const toggle = document.getElementById('togglePass');
  toggle.addEventListener('click', () => {
    const show = pass.type === 'password';
    pass.type = show ? 'text' : 'password';
    toggle.innerHTML = `<i class="fas fa-eye${show ? '-slash' : ''}"></i>`;
    toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    pass.focus();
  });

  // Caps Lock warning
  const caps = document.getElementById('capsWarning');
  pass.addEventListener('keyup', e => caps.classList.toggle('show', !!(e.getModifierState && e.getModifierState('CapsLock'))));
  pass.addEventListener('blur', () => caps.classList.remove('show'));

  // Loading state on submit
  document.getElementById('loginForm').addEventListener('submit', () => {
    document.getElementById('loginBtn').classList.add('loading');
  });

  // Restore the button if the page comes back from the back/forward cache
  window.addEventListener('pageshow', e => {
    if (e.persisted) document.getElementById('loginBtn').classList.remove('loading');
  });
})();
</script>

</body>
</html>
