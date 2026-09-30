<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Questions</h2>
    <p class="page-sub"><?= count($questions) ?> total questions</p>
  </div>
  <a href="<?= base_url('admin/questions/create') ?>" class="btn btn-admin-primary">
    <i class="fas fa-plus mr-1"></i> Add Question
  </a>
</div>

<div class="admin-card animate-fadeIn">
  <div class="admin-card-header">
    <h5><i class="fas fa-list mr-2"></i>All Questions</h5>
    <div class="d-flex align-items-center">
      <input type="text" id="qSearch" class="form-control form-control-sm mr-2"
             placeholder="Search questions..." style="width:220px">
      <span class="badge badge-primary"><?= count($questions) ?> Questions</span>
    </div>
  </div>
  <div class="admin-card-body p-0">
    <?php if (!empty($questions)): ?>
    <div class="table-responsive">
      <table class="table admin-table mb-0" id="questionsTable">
        <thead>
          <tr>
            <th width="50">#</th>
            <th width="80">Image</th>
            <th>Prompt</th>
            <th width="120">Options</th>
            <th width="100">Status</th>
            <th width="140">Actions</th>
          </tr>
        </thead>
        <tbody id="sortable-questions">
          <?php foreach ($questions as $q): ?>
          <tr class="q-row" data-id="<?= $q['id'] ?>">
            <td>
              <span class="drag-handle" title="Drag to reorder">
                <i class="fas fa-grip-vertical text-muted"></i>
              </span>
              <span class="q-num"><?= $q['sort'] ?? $q['id'] ?></span>
            </td>
            <td>
              <img src="<?= esc(image_url($q['questionImage'])) ?>"
                   alt="Q<?= $q['id'] ?>"
                   class="q-thumb"
                   loading="lazy" />
            </td>
            <td>
              <div class="q-prompt-text"><?= esc(substr($q['prompt'], 0, 90)) ?><?= strlen($q['prompt']) > 90 ? '…' : '' ?></div>
              <div class="q-answer-label text-muted small mt-1">
                <i class="fas fa-check-circle text-success mr-1"></i>
                <?= esc($q['answerLabel']) ?>
              </div>
            </td>
            <td>
              <div class="options-thumbs">
                <?php foreach ($q['options'] as $opt): ?>
                <img src="<?= esc(image_url($opt['file'])) ?>"
                     class="opt-thumb <?= $opt['correct'] ? 'opt-correct' : '' ?>"
                     title="<?= esc($opt['label']) ?><?= $opt['correct'] ? ' ✓ (correct)' : '' ?>"
                     loading="lazy" />
                <?php endforeach; ?>
              </div>
            </td>
            <td>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input toggle-active"
                       id="toggle-<?= $q['id'] ?>"
                       data-id="<?= $q['id'] ?>"
                       <?= ($q['active'] ?? true) ? 'checked' : '' ?>>
                <label class="custom-control-label" for="toggle-<?= $q['id'] ?>">
                  <span class="toggle-label <?= ($q['active'] ?? true) ? 'text-success' : 'text-muted' ?>">
                    <?= ($q['active'] ?? true) ? 'Active' : 'Hidden' ?>
                  </span>
                </label>
              </div>
            </td>
            <td>
              <div class="action-btns">
                <a href="<?= base_url('admin/questions/edit/' . $q['id']) ?>"
                   class="btn btn-xs btn-outline-warning" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-xs btn-outline-danger btn-delete"
                        data-id="<?= $q['id'] ?>"
                        data-url="<?= base_url('admin/questions/delete/' . $q['id']) ?>"
                        title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="empty-state py-5">
      <i class="fas fa-question-circle"></i>
      <p>No questions yet. Add your first question!</p>
      <a href="<?= base_url('admin/questions/create') ?>" class="btn btn-admin-primary">
        <i class="fas fa-plus mr-1"></i> Add Question
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content admin-modal">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger mr-2"></i>Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">Are you sure you want to delete this question? This cannot be undone.</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="post">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash mr-1"></i> Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
// Search
document.getElementById('qSearch').addEventListener('input', function() {
  const v = this.value.toLowerCase();
  document.querySelectorAll('.q-row').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(v) ? '' : 'none';
  });
});

// Delete modal
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('deleteForm').action = btn.dataset.url;
    $('#deleteModal').modal('show');
  });
});

// Toggle active via AJAX
document.querySelectorAll('.toggle-active').forEach(cb => {
  cb.addEventListener('change', function() {
    const id = this.dataset.id;
    const label = document.querySelector('.toggle-label[for="toggle-' + id + '"]') ||
                  this.closest('td').querySelector('.toggle-label');
    fetch(`<?= base_url('admin/api/toggle-question/') ?>` + id, {
      method: 'POST',
      headers: {'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json'},
      body: JSON.stringify({<?= csrf_token() ?>: '<?= csrf_hash() ?>'})
    })
    .then(r => r.json())
    .then(data => {
      if (label) {
        label.textContent = data.active ? 'Active' : 'Hidden';
        label.className = 'toggle-label ' + (data.active ? 'text-success' : 'text-muted');
      }
      showToast(data.active ? 'Question activated' : 'Question hidden');
    });
  });
});
</script>
<?php $this->endSection() ?>
