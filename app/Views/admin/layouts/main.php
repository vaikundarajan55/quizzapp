<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= esc($pageTitle ?? 'Admin Panel') ?> — Quiz Admin</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Admin CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/css/admin.css') ?>" />

  <?= $this->renderSection('head') ?>
</head>
<body class="admin-body">

<!-- ===== SIDEBAR ===== -->
<div class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">⚓</div>
    <div class="brand-text">
      <span class="brand-name">Quiz Admin</span>
      <span class="brand-sub">IALA Buoyage</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= (uri_string() === 'admin' || uri_string() === 'admin/dashboard') ? 'active' : '' ?>">
      <i class="fas fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>

    <div class="nav-section-label">Content</div>
    <a href="<?= base_url('admin/questions') ?>" class="nav-link <?= strpos(uri_string(), 'admin/questions') === 0 ? 'active' : '' ?>">
      <i class="fas fa-question-circle"></i>
      <span>Questions</span>
      <span class="nav-badge"><?= $stats['total_questions'] ?? '' ?></span>
    </a>
    <a href="<?= base_url('admin/text-questions') ?>" class="nav-link <?= strpos(uri_string(), 'admin/text-questions') === 0 ? 'active' : '' ?>">
      <i class="fas fa-list-ol"></i>
      <span>Text Questions</span>
    </a>
    <a href="<?= base_url('admin/results') ?>" class="nav-link <?= strpos(uri_string(), 'admin/results') === 0 ? 'active' : '' ?>">
      <i class="fas fa-chart-bar"></i>
      <span>Results</span>
      <span class="nav-badge badge-green"><?= $stats['total_attempts'] ?? '' ?></span>
    </a>

    <div class="nav-section-label">System</div>
    <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= strpos(uri_string(), 'admin/settings') === 0 ? 'active' : '' ?>">
      <i class="fas fa-cog"></i>
      <span>Settings</span>
    </a>
    <a href="<?= base_url('/') ?>" class="nav-link" target="_blank">
      <i class="fas fa-external-link-alt"></i>
      <span>View Website</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar"><i class="fas fa-user-shield"></i></div>
      <div class="user-info">
        <span class="user-name"><?= esc(session('admin_name') ?? 'Administrator') ?></span>
        <span class="user-role">@<?= esc(session('admin_username') ?? '') ?></span>
      </div>
    </div>
  </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="admin-main" id="adminMain">

  <!-- Top Navbar -->
  <div class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title"><?= esc($pageTitle ?? 'Dashboard') ?></div>
    <div class="topbar-right">
      <div class="topbar-clock" id="topbarClock"></div>
      <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-light ml-2" target="_blank">
        <i class="fas fa-globe mr-1"></i> Website
      </a>
      <a href="<?= base_url('admin/logout') ?>" class="btn btn-sm btn-outline-danger ml-2">
        <i class="fas fa-sign-out-alt mr-1"></i> Log out
      </a>
    </div>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
  <div class="alert-floating alert-success-floating animate__fadeInDown">
    <i class="fas fa-check-circle mr-2"></i>
    <?= esc(session()->getFlashdata('success')) ?>
    <button type="button" class="close-alert"><i class="fas fa-times"></i></button>
  </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
  <div class="alert-floating alert-error-floating animate__fadeInDown">
    <i class="fas fa-exclamation-circle mr-2"></i>
    <?= esc(session()->getFlashdata('error')) ?>
    <button type="button" class="close-alert"><i class="fas fa-times"></i></button>
  </div>
  <?php endif; ?>

  <!-- Page Content -->
  <div class="admin-content">
    <?= $this->renderSection('content') ?>
  </div>

</div><!-- /admin-main -->

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<!-- Admin JS -->
<script src="<?= base_url('assets/admin/js/admin.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
