<?php $isTextQuiz = ($type ?? 'image') === 'text'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title><?= esc($settings['quiz_title']) ?> — <?= $isTextQuiz ? 'Text Quiz' : 'Quiz' ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= base_url('css/website.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('css/quiz.css') ?>" />
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

<!-- Navbar -->
<nav class="navbar navbar-expand-md site-navbar">
  <div class="container-fluid px-3">
    <a class="navbar-brand site-brand" href="<?= base_url('/') ?>">⚓ <?= esc($settings['quiz_title']) ?></a>
    <div class="ml-auto d-flex align-items-center">
      <?php if ($isTextQuiz): ?>
      <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-light mr-2 quiz-switch">
        <i class="fas fa-hand-pointer mr-1"></i> Drag &amp; Drop Quiz
      </a>
      <?php else: ?>
      <a href="<?= base_url('text-quiz') ?>" class="btn btn-sm btn-outline-light mr-2 quiz-switch">
        <i class="fas fa-list-ol mr-1"></i> Text Quiz
      </a>
      <?php endif; ?>
      <div class="quiz-timer-badge" id="quizTimer">00:00</div>
      <?php if (!empty($settings['voice_enabled'])): ?>
      <button id="voiceToggleBtn" class="btn btn-sm btn-outline-light ml-2">
        <i class="fas fa-volume-up mr-1"></i> Voice: On
      </button>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Quiz container (populated by JS) -->
<main id="quizContainer" class="quiz-container container-fluid px-3">
  <?php if (empty($questions)): ?>
  <div class="quiz-empty">
    <i class="fas fa-inbox"></i>
    <p>No questions have been added to this quiz yet.</p>
  </div>
  <?php endif; ?>
</main>

<!-- Submit footer -->
<footer class="quiz-footer">
  <div class="container-fluid px-3 d-flex justify-content-between align-items-center">
    <button id="resetBtn2" class="btn btn-outline-light">
      <i class="fas fa-redo mr-1"></i> Reset All
    </button>
    <button id="submitBtn" class="btn btn-quiz-submit">
      <i class="fas fa-paper-plane mr-2"></i> Submit Quiz
    </button>
  </div>
</footer>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content quiz-modal">
      <div class="modal-body text-center p-4">
        <div id="modalScoreCircle" class="modal-score-circle">
          <span id="modalScore">0%</span>
        </div>
        <h4 id="modalVerdict" class="mt-3 mb-1"></h4>
        <div class="modal-stats-grid mt-3">
          <div class="mstat"><strong id="modalTotal">0</strong><span>Total</span></div>
          <div class="mstat"><strong id="modalFilled">0</strong><span>Answered</span></div>
          <div class="mstat text-success"><strong id="modalCorrect">0</strong><span>Correct</span></div>
          <div class="mstat text-danger"><strong id="modalIncorrect">0</strong><span>Wrong</span></div>
        </div>
        <div id="modalActions" class="mt-4 d-flex gap-2 justify-content-center flex-wrap">
          <button id="resetBtn" class="btn btn-outline-secondary">
            <i class="fas fa-redo mr-1"></i> Try Again
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Pass quiz data to JS -->
<script>
const QUIZ_DATA      = <?= json_encode($questions, JSON_UNESCAPED_UNICODE) ?>;
const QUIZ_SETTINGS  = <?= json_encode($settings,  JSON_UNESCAPED_UNICODE) ?>;
const SUBMIT_URL     = '<?= base_url('quiz/submit') ?>';
const QUIZ_TYPE      = '<?= $isTextQuiz ? 'text' : 'image' ?>';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/quiz.js') ?>"></script>
</body>
</html>
