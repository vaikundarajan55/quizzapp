<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Quiz Results</h2>
    <p class="page-sub"><?= count($results) ?> total submissions</p>
  </div>
  <?php if (!empty($results)): ?>
  <form method="post" action="<?= base_url('admin/results/clear') ?>"
        onsubmit="return confirm('Clear ALL results? This cannot be undone.')">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-outline-danger">
      <i class="fas fa-trash mr-1"></i> Clear All
    </button>
  </form>
  <?php endif; ?>
</div>

<?php if (!empty($results)):
  $total   = count($results);
  $passed  = count(array_filter($results, fn($r) => $r['score'] >= ($settings['passing_score'] ?? 70)));
  $avgScore= $total ? round(array_sum(array_column($results, 'score')) / $total, 1) : 0;
?>
<!-- Summary bar -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="mini-stat ms-blue animate-slideUp">
      <i class="fas fa-list-ol"></i>
      <div>
        <div class="ms-value"><?= $total ?></div>
        <div class="ms-label">Total Attempts</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="mini-stat ms-green animate-slideUp" style="animation-delay:.05s">
      <i class="fas fa-check-circle"></i>
      <div>
        <div class="ms-value"><?= $passed ?></div>
        <div class="ms-label">Passed (≥<?= $settings['passing_score'] ?? 70 ?>%)</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="mini-stat ms-purple animate-slideUp" style="animation-delay:.1s">
      <i class="fas fa-chart-line"></i>
      <div>
        <div class="ms-value"><?= $avgScore ?>%</div>
        <div class="ms-label">Average Score</div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="admin-card animate-fadeIn">
  <div class="admin-card-header">
    <h5><i class="fas fa-table mr-2"></i>All Submissions</h5>
    <?php if (!empty($results)): ?>
    <input type="text" id="resultSearch" class="form-control form-control-sm"
           placeholder="Search…" style="width:200px">
    <?php endif; ?>
  </div>
  <div class="admin-card-body p-0">
    <?php if (!empty($results)): ?>
    <div class="table-responsive">
      <table class="table admin-table mb-0" id="resultsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Date & Time</th>
            <th>Score</th>
            <th>Correct / Total</th>
            <th>Time Taken</th>
            <th>Result</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $i => $r): ?>
          <tr>
            <td class="text-muted"><?= $i + 1 ?></td>
            <td>
              <div><?= date('M d, Y', strtotime($r['created_at'])) ?></div>
              <small class="text-muted"><?= date('H:i:s', strtotime($r['created_at'])) ?></small>
            </td>
            <td>
              <div class="score-pill <?= $r['score'] >= ($settings['passing_score'] ?? 70) ? 'score-pass' : 'score-fail' ?>">
                <?= number_format($r['score'], 1) ?>%
              </div>
            </td>
            <td>
              <strong><?= $r['correct'] ?></strong> / <?= $r['total'] ?>
            </td>
            <td><?= gmdate('i:s', $r['elapsed'] ?? 0) ?></td>
            <td>
              <?php if ($r['score'] >= ($settings['passing_score'] ?? 70)): ?>
              <span class="badge badge-success px-3">PASS</span>
              <?php else: ?>
              <span class="badge badge-danger px-3">FAIL</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="<?= base_url('admin/results/' . $r['token']) ?>"
                 class="btn btn-xs btn-outline-info" title="View Detail">
                <i class="fas fa-eye mr-1"></i> View
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="empty-state py-5">
      <i class="fas fa-inbox"></i>
      <p>No quiz submissions yet.</p>
      <a href="<?= base_url('/quiz') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
        Go to Quiz <i class="fas fa-external-link-alt ml-1"></i>
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
const s = document.getElementById('resultSearch');
if (s) {
  s.addEventListener('input', function() {
    const v = this.value.toLowerCase();
    document.querySelectorAll('#resultsTable tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(v) ? '' : 'none';
    });
  });
}
</script>
<?php $this->endSection() ?>
