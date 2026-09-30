<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Your Result — <?= esc($settings['quiz_title']) ?></title>
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

<nav class="navbar site-navbar">
  <div class="container">
    <a class="navbar-brand site-brand" href="<?= base_url('/') ?>">⚓ <?= esc($settings['quiz_title']) ?></a>
  </div>
</nav>

<div class="container py-5">
  <?php $pass = $result['score'] >= ($settings['passing_score'] ?? 70); ?>
  <div class="result-page-card animate-fadeIn">
    <!-- Score circle -->
    <div class="text-center mb-4">
      <div class="result-score-big <?= $pass ? 'result-pass' : 'result-fail' ?>">
        <span><?= number_format($result['score'], 1) ?>%</span>
      </div>
      <h2 class="mt-3"><?= $pass ? '🎉 Congratulations!' : '😔 Better Luck Next Time' ?></h2>
      <p class="text-muted">
        <?= $pass ? 'You passed! Well done on completing the IALA Buoyage quiz.' : "You didn't reach the passing score. Keep studying!" ?>
      </p>
    </div>

    <!-- Stats row -->
    <div class="result-stats-grid mb-4">
      <div class="rs-item">
        <div class="rs-val"><?= $result['total'] ?></div>
        <div class="rs-lbl">Questions</div>
      </div>
      <div class="rs-item text-success">
        <div class="rs-val"><?= $result['correct'] ?></div>
        <div class="rs-lbl">Correct</div>
      </div>
      <div class="rs-item text-danger">
        <div class="rs-val"><?= $result['total'] - $result['correct'] ?></div>
        <div class="rs-lbl">Wrong</div>
      </div>
      <div class="rs-item text-info">
        <div class="rs-val"><?= gmdate('i:s', $result['elapsed'] ?? 0) ?></div>
        <div class="rs-lbl">Time</div>
      </div>
    </div>

    <!-- Progress bar -->
    <div class="mb-4">
      <div class="d-flex justify-content-between mb-1">
        <small>Your Score</small>
        <small><?= number_format($result['score'],1) ?>% / <?= $settings['passing_score'] ?? 70 ?>% to pass</small>
      </div>
      <div class="progress" style="height:14px;border-radius:7px">
        <div class="progress-bar <?= $pass ? 'bg-success' : 'bg-danger' ?>"
             style="width:<?= $result['score'] ?>%;border-radius:7px;transition:width 1.2s ease"></div>
      </div>
    </div>

    <!-- Question breakdown -->
    <div class="result-breakdown">
      <h5 class="mb-3"><i class="fas fa-clipboard-list mr-2"></i>Question Breakdown</h5>
      <?php foreach ($result['details'] as $d): ?>
      <div class="breakdown-row <?= $d['correct'] ? 'brow-correct' : ($d['answered'] ? 'brow-wrong' : 'brow-skip') ?>">
        <div class="brow-num">Q<?= $d['id'] ?></div>
        <div class="brow-icon">
          <?php if ($d['correct']): ?>
          <i class="fas fa-check-circle text-success"></i>
          <?php elseif ($d['answered']): ?>
          <i class="fas fa-times-circle text-danger"></i>
          <?php else: ?>
          <i class="fas fa-minus-circle text-muted"></i>
          <?php endif; ?>
        </div>
        <div class="brow-text">
          <div class="brow-prompt"><?= esc(substr($d['prompt'], 0, 70)) ?>…</div>
          <div class="brow-answer text-muted small">
            <i class="fas fa-check text-success mr-1"></i> <?= esc($d['answerLabel']) ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Actions -->
    <div class="text-center mt-4">
      <a href="<?= base_url('/quiz') ?>" class="btn btn-site-primary mr-2">
        <i class="fas fa-redo mr-1"></i> Try Again
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
