<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= esc($settings['quiz_title']) ?> — Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= base_url('css/website.css') ?>" />
</head>
<body class="site-body bg-<?= esc($settings['bg_type'] ?? 'ocean') ?>">

<?php if (($settings['bg_type'] ?? 'ocean') === 'ocean'): ?>
<!-- Animated ocean background -->
<div class="ocean-bg" aria-hidden="true">
  <?php foreach (['wave-group-top','wave-group-center','wave-group-bottom'] as $wg): ?>
  <div class="wave-group <?= $wg ?>">
    <svg class="wave wave3" viewBox="0 0 2400 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path fill="#0d3c66" d="M0,70 C200,20 400,120 600,70 C800,20 1000,120 1200,70 C1400,20 1600,120 1800,70 C2000,20 2200,120 2400,70 L2400,120 L0,120 Z"/></svg>
    <svg class="wave wave2" viewBox="0 0 2400 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path fill="#12578f" d="M0,60 C200,110 400,10 600,60 C800,110 1000,10 1200,60 C1400,110 1600,10 1800,60 C2000,110 2200,10 2400,60 L2400,120 L0,120 Z"/></svg>
    <svg class="wave wave1" viewBox="0 0 2400 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path fill="#1c7ab8" d="M0,50 C200,95 400,5 600,50 C800,95 1000,5 1200,50 C1400,95 1600,5 1800,50 C2000,95 2200,5 2400,50 L2400,120 L0,120 Z"/></svg>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-md site-navbar">
  <div class="container">
    <a class="navbar-brand site-brand" href="<?= base_url('/') ?>">
      ⚓ <span><?= esc($settings['quiz_title']) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#siteNav">
      <i class="fas fa-bars text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="siteNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('home') ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('/quiz') ?>">Take Quiz</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('/about') ?>">About</a></li>
        <li class="nav-item"><a class="nav-link nav-admin-link" href="<?= base_url('admin/dashboard') ?>">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero-section">
  <div class="container text-center">
    <div class="hero-icon animate-bounce-in">⚓</div>
    <h1 class="hero-title animate-fadeUp"><?= esc($settings['quiz_title']) ?></h1>
    <p class="hero-subtitle animate-fadeUp" style="animation-delay:.1s"><?= esc($settings['quiz_subtitle']) ?></p>
    <div class="hero-cta animate-fadeUp" style="animation-delay:.2s">
      <a href="<?= base_url('/quiz') ?>" class="btn btn-site-primary btn-lg">
        <i class="fas fa-play mr-2"></i> Start Quiz
      </a>
      <a href="<?= base_url('/about') ?>" class="btn btn-site-outline btn-lg ml-3">
        <i class="fas fa-info-circle mr-2"></i> Learn More
      </a>
    </div>

    <!-- Stats pills -->
    <div class="hero-stats animate-fadeUp" style="animation-delay:.3s">
      <div class="hstat-pill">
        <i class="fas fa-question-circle"></i>
        <strong><?= $stats['active_questions'] ?></strong> Questions
      </div>
      <div class="hstat-pill">
        <i class="fas fa-users"></i>
        <strong><?= $stats['total_attempts'] ?></strong> Attempts
      </div>
      <?php if ($stats['total_attempts'] > 0): ?>
      <div class="hstat-pill">
        <i class="fas fa-chart-line"></i>
        Avg Score <strong><?= $stats['avg_score'] ?>%</strong>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Features -->
<section class="features-section">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="feature-card animate-slideUp">
          <div class="feature-icon">🎯</div>
          <h5>Drag & Drop</h5>
          <p>Intuitive drag-and-drop interface on desktop. Tap-to-select on touch devices.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="feature-card animate-slideUp" style="animation-delay:.1s">
          <div class="feature-icon">🔊</div>
          <h5>Voice Feedback</h5>
          <p>Correct answers are read aloud automatically using your browser's text-to-speech.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="feature-card animate-slideUp" style="animation-delay:.2s">
          <div class="feature-icon">📊</div>
          <h5>Instant Scoring</h5>
          <p>Get immediate feedback on each answer and a full score summary at the end.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="site-footer">
  <div class="container text-center">
    <p class="mb-0 text-muted">⚓ <?= esc($settings['quiz_title']) ?> &mdash; IALA Buoyage Quiz System</p>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
