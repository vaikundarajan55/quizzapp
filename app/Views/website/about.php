<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>About — <?= esc($settings['quiz_title']) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= base_url('css/website.css') ?>" />
</head>
<body class="site-body bg-<?= esc($settings['bg_type'] ?? 'ocean') ?>">
<?php if (($settings['bg_type'] ?? 'ocean') === 'ocean'): ?>
<div class="ocean-bg" aria-hidden="true">
  <?php foreach (['wave-group-top','wave-group-center','wave-group-bottom'] as $wg): ?>
  <div class="wave-group <?= $wg ?>">
    <svg class="wave wave3" viewBox="0 0 2400 120" preserveAspectRatio="none"><path fill="#0d3c66" d="M0,70 C200,20 400,120 600,70 C800,20 1000,120 1200,70 C1400,20 1600,120 1800,70 C2000,20 2200,120 2400,70 L2400,120 L0,120 Z"/></svg>
    <svg class="wave wave2" viewBox="0 0 2400 120" preserveAspectRatio="none"><path fill="#12578f" d="M0,60 C200,110 400,10 600,60 C800,110 1000,10 1200,60 C1400,110 1600,10 1800,60 C2000,110 2200,10 2400,60 L2400,120 L0,120 Z"/></svg>
    <svg class="wave wave1" viewBox="0 0 2400 120" preserveAspectRatio="none"><path fill="#1c7ab8" d="M0,50 C200,95 400,5 600,50 C800,95 1000,5 1200,50 C1400,95 1600,5 1800,50 C2000,95 2200,5 2400,50 L2400,120 L0,120 Z"/></svg>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<nav class="navbar navbar-expand-md site-navbar">
  <div class="container">
    <a class="navbar-brand site-brand" href="<?= base_url('/') ?>">⚓ <?= esc($settings['quiz_title']) ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#siteNav">
      <i class="fas fa-bars text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="siteNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('home') ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('/quiz') ?>">Take Quiz</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('/about') ?>">About</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-5">
  <div class="about-card animate-fadeIn">
    <h2 class="mb-3">⚓ About This Quiz</h2>
    <p class="lead">This application tests your knowledge of the <strong>IALA Maritime Buoyage System</strong> — the international standard for lateral, cardinal, isolated danger, safe water, and special marks.</p>
    <hr class="border-muted">
    <h5>How to Play</h5>
    <ol>
      <li>Each question shows a buoy image with an empty top-mark zone.</li>
      <li><strong>Desktop:</strong> Drag the correct top-mark option onto the buoy.</li>
      <li><strong>Touch:</strong> Tap an option to select it, then tap the drop zone on the buoy.</li>
      <li>Green ✓ = correct. Red ✕ = incorrect. You can clear and retry.</li>
      <li>Click <strong>Submit Quiz</strong> when you're done to see your score.</li>
    </ol>
    <hr class="border-muted">
    <h5>Built With</h5>
    <p>CodeIgniter 4 · Bootstrap 4 · Vanilla JS · CSS Animations · Web Speech API</p>
    <div class="mt-4">
      <a href="<?= base_url('/quiz') ?>" class="btn btn-site-primary mr-2">
        <i class="fas fa-play mr-1"></i> Start Quiz
      </a>
      <a href="<?= base_url('home') ?>" class="btn btn-site-outline">
        <i class="fas fa-home mr-1"></i> Home
      </a>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
