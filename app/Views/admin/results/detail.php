<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Result Detail</h2>
    <p class="page-sub">Token: <code><?= esc($result['token']) ?></code></p>
  </div>
  <a href="<?= base_url('admin/results') ?>" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left mr-1"></i> Back to Results
  </a>
</div>

<!-- Score summary -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="admin-card animate-fadeIn text-center p-4">
      <div class="result-score-circle <?= $result['score'] >= ($settings['passing_score'] ?? 70) ? 'score-pass-circle' : 'score-fail-circle' ?>">
        <?= number_format($result['score'], 1) ?>%
      </div>
      <div class="mt-2">
        <?php if ($result['score'] >= ($settings['passing_score'] ?? 70)): ?>
        <span class="badge badge-success badge-lg px-4 py-2">PASSED</span>
        <?php else: ?>
        <span class="badge badge-danger badge-lg px-4 py-2">FAILED</span>
        <?php endif; ?>
      </div>
      <p class="text-muted small mt-2">Submitted: <?= date('M d, Y H:i', strtotime($result['created_at'])) ?></p>
    </div>
  </div>
  <div class="col-md-9">
    <div class="row">
      <div class="col-sm-4 mb-3">
        <div class="mini-stat ms-blue animate-slideUp">
          <i class="fas fa-list-ol"></i>
          <div><div class="ms-value"><?= $result['total'] ?></div><div class="ms-label">Total Questions</div></div>
        </div>
      </div>
      <div class="col-sm-4 mb-3">
        <div class="mini-stat ms-green animate-slideUp" style="animation-delay:.05s">
          <i class="fas fa-check"></i>
          <div><div class="ms-value"><?= $result['correct'] ?></div><div class="ms-label">Correct</div></div>
        </div>
      </div>
      <div class="col-sm-4 mb-3">
        <div class="mini-stat ms-red animate-slideUp" style="animation-delay:.1s">
          <i class="fas fa-times"></i>
          <div><div class="ms-value"><?= $result['total'] - $result['correct'] ?></div><div class="ms-label">Incorrect</div></div>
        </div>
      </div>
      <div class="col-sm-4 mb-3">
        <div class="mini-stat ms-purple animate-slideUp" style="animation-delay:.15s">
          <i class="fas fa-clock"></i>
          <div><div class="ms-value"><?= gmdate('i:s', $result['elapsed'] ?? 0) ?></div><div class="ms-label">Time Taken</div></div>
        </div>
      </div>
      <div class="col-sm-8 mb-3">
        <div class="admin-card animate-fadeIn p-3" style="animation-delay:.2s">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Score Progress</small>
            <small class="font-weight-bold"><?= number_format($result['score'], 1) ?>%</small>
          </div>
          <div class="progress" style="height:12px;border-radius:6px">
            <div class="progress-bar <?= $result['score'] >= 70 ? 'bg-success' : 'bg-danger' ?>"
                 style="width:<?= $result['score'] ?>%;border-radius:6px"></div>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">0%</small>
            <small class="text-muted">Pass: <?= $settings['passing_score'] ?? 70 ?>%</small>
            <small class="text-muted">100%</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Per-question breakdown -->
<div class="admin-card animate-fadeIn" style="animation-delay:.25s">
  <div class="admin-card-header">
    <h5><i class="fas fa-clipboard-list mr-2"></i>Question Breakdown</h5>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th width="50">#</th>
            <th>Question Prompt</th>
            <th width="150">Answer Given</th>
            <th width="150">Correct Answer</th>
            <th width="100">Result</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($result['details'] as $d): ?>
          <tr class="<?= $d['correct'] ? 'row-correct' : ($d['answered'] ? 'row-incorrect' : 'row-skipped') ?>">
            <td><?= $d['id'] ?></td>
            <td><?= esc(substr($d['prompt'], 0, 80)) ?>…</td>
            <td>
              <?php if ($d['answered']): ?>
              <span class="text-<?= $d['correct'] ? 'success' : 'danger' ?>">
                <?= esc($d['answerLabel'] ?? 'Option ' . ($d['optionIndex'] + 1)) ?>
              </span>
              <?php else: ?>
              <span class="text-muted"><em>Not answered</em></span>
              <?php endif; ?>
            </td>
            <td><span class="text-success"><?= esc($d['answerLabel']) ?></span></td>
            <td>
              <?php if ($d['correct']): ?>
              <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Correct</span>
              <?php elseif ($d['answered']): ?>
              <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Wrong</span>
              <?php else: ?>
              <span class="badge badge-secondary">Skipped</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php $this->endSection() ?>
