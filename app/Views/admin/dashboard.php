<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Dashboard</h2>
    <p class="page-sub">Welcome back, Administrator</p>
  </div>
  <a href="<?= base_url('admin/questions/create') ?>" class="btn btn-admin-primary">
    <i class="fas fa-plus mr-1"></i> New Question
  </a>
</div>

<!-- Stats Cards -->
<div class="row stats-row">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card stat-blue animate-slideUp" style="animation-delay:.05s">
      <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
      <div class="stat-body">
        <div class="stat-value"><?= $stats['total_questions'] ?></div>
        <div class="stat-label">Total Questions</div>
      </div>
      <div class="stat-wave"></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card stat-green animate-slideUp" style="animation-delay:.1s">
      <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
      <div class="stat-body">
        <div class="stat-value"><?= $stats['active_questions'] ?></div>
        <div class="stat-label">Active Questions</div>
      </div>
      <div class="stat-wave"></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card stat-purple animate-slideUp" style="animation-delay:.15s">
      <div class="stat-icon"><i class="fas fa-users"></i></div>
      <div class="stat-body">
        <div class="stat-value"><?= $stats['total_attempts'] ?></div>
        <div class="stat-label">Quiz Attempts</div>
      </div>
      <div class="stat-wave"></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card stat-orange animate-slideUp" style="animation-delay:.2s">
      <div class="stat-icon"><i class="fas fa-trophy"></i></div>
      <div class="stat-body">
        <div class="stat-value"><?= $stats['avg_score'] ?>%</div>
        <div class="stat-label">Average Score</div>
      </div>
      <div class="stat-wave"></div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Results -->
  <div class="col-lg-8 mb-4">
    <div class="admin-card animate-fadeIn" style="animation-delay:.25s">
      <div class="admin-card-header">
        <h5><i class="fas fa-history mr-2"></i>Recent Attempts</h5>
        <a href="<?= base_url('admin/results') ?>" class="btn btn-sm btn-outline-secondary">View All</a>
      </div>
      <div class="admin-card-body p-0">
        <?php if (!empty($stats['recent_results'])): ?>
        <div class="table-responsive">
          <table class="table admin-table mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Correct</th>
                <th>Time</th>
                <th>Result</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($stats['recent_results'] as $r): ?>
              <tr>
                <td><?= date('M d, H:i', strtotime($r['created_at'])) ?></td>
                <td>
                  <div class="score-pill <?= $r['score'] >= 70 ? 'score-pass' : 'score-fail' ?>">
                    <?= $r['score'] ?>%
                  </div>
                </td>
                <td><?= $r['correct'] ?>/<?= $r['total'] ?></td>
                <td><?= gmdate('i:s', $r['elapsed'] ?? 0) ?></td>
                <td>
                  <?php if ($r['score'] >= 70): ?>
                  <span class="badge badge-success">PASS</span>
                  <?php else: ?>
                  <span class="badge badge-danger">FAIL</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= base_url('admin/results/' . $r['token']) ?>" class="btn btn-xs btn-outline-info">
                    <i class="fas fa-eye"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state py-5">
          <i class="fas fa-chart-bar"></i>
          <p>No quiz attempts yet. Share the quiz link!</p>
          <a href="<?= base_url('/quiz') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            Open Quiz <i class="fas fa-external-link-alt ml-1"></i>
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="col-lg-4 mb-4">
    <div class="admin-card animate-fadeIn" style="animation-delay:.3s">
      <div class="admin-card-header">
        <h5><i class="fas fa-bolt mr-2"></i>Quick Actions</h5>
      </div>
      <div class="admin-card-body">
        <div class="quick-actions">
          <a href="<?= base_url('admin/questions/create') ?>" class="quick-action-btn qa-blue">
            <i class="fas fa-plus-circle"></i>
            <span>Add Question</span>
          </a>
          <a href="<?= base_url('/quiz') ?>" class="quick-action-btn qa-green" target="_blank">
            <i class="fas fa-play-circle"></i>
            <span>Take Quiz</span>
          </a>
          <a href="<?= base_url('admin/results') ?>" class="quick-action-btn qa-purple">
            <i class="fas fa-chart-pie"></i>
            <span>View Results</span>
          </a>
          <a href="<?= base_url('admin/settings') ?>" class="quick-action-btn qa-orange">
            <i class="fas fa-sliders-h"></i>
            <span>Settings</span>
          </a>
        </div>

        <hr class="divider-subtle mt-4 mb-3">
        <div class="info-block">
          <div class="info-row">
            <span class="info-label"><i class="fas fa-link mr-1"></i>Quiz URL</span>
          </div>
          <div class="url-copy-box">
            <code id="quizUrl"><?= base_url('/quiz') ?></code>
            <button class="copy-btn" onclick="copyUrl()"><i class="fas fa-copy"></i></button>
          </div>
        </div>

        <div class="score-meter mt-3">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Avg Score</small>
            <small class="text-muted"><?= $stats['avg_score'] ?>%</small>
          </div>
          <div class="progress" style="height:8px;border-radius:4px">
            <div class="progress-bar bg-gradient-primary"
                 role="progressbar"
                 style="width:<?= $stats['avg_score'] ?>%"
                 aria-valuenow="<?= $stats['avg_score'] ?>"
                 aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">0%</small>
            <small class="text-success">High: <?= $stats['high_score'] ?>%</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
function copyUrl() {
  const url = document.getElementById('quizUrl').textContent;
  navigator.clipboard.writeText(url).then(() => {
    showToast('Quiz URL copied!');
  });
}
</script>
<?php $this->endSection() ?>
