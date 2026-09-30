<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Text Questions</h2>
    <p class="page-sub"><?= count($questions) ?> questions with 4 text options</p>
  </div>
  <a href="<?= base_url('admin/text-questions/create') ?>" class="btn btn-admin-primary">
    <i class="fas fa-plus mr-1"></i> Add Text Question
  </a>
</div>

<div class="admin-card animate-fadeIn">
  <div class="admin-card-header">
    <h5><i class="fas fa-list-ol mr-2"></i>All Text Questions</h5>
    <div class="d-flex align-items-center">
      <input type="text" id="qSearch" class="form-control form-control-sm mr-2"
             placeholder="Search questions..." style="width:220px">
      <span class="badge badge-primary"><?= count($questions) ?> Questions</span>
    </div>
  </div>
  <div class="admin-card-body p-0">
    <?php if (!empty($questions)): ?>
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th width="50">#</th>
            <th width="80">Image</th>
            <th>Question</th>
            <th>Options</th>
            <th width="100">Status</th>
            <th width="110">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $q): ?>
          <tr class="q-row">
            <td><span class="q-num"><?= $q['id'] ?></span></td>
            <td>
              <?php if ($q['questionImage'] !== ''): ?>
              <img src="<?= esc(image_url($q['questionImage'])) ?>" alt="Q<?= $q['id'] ?>" class="q-thumb" loading="lazy" />
              <?php else: ?>
              <span class="text-muted small">—</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="q-prompt-text"><?= esc(mb_strimwidth($q['prompt'], 0, 110, '…')) ?></div>
            </td>
            <td>
              <div class="text-opts">
                <?php foreach ($q['options'] as $i => $opt): ?>
                <span class="text-opt <?= $opt['correct'] ? 'text-opt-correct' : '' ?>"
                      title="<?= $opt['correct'] ? 'Correct answer' : '' ?>">
                  <b><?= chr(65 + $i) ?></b> <?= esc($opt['label']) ?>
                </span>
                <?php endforeach; ?>
              </div>
            </td>
            <td>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input toggle-active"
                       id="toggle-<?= $q['id'] ?>" data-id="<?= $q['id'] ?>"
                       <?= $q['active'] ? 'checked' : '' ?>>
                <label class="custom-control-label" for="toggle-<?= $q['id'] ?>">
                  <span class="toggle-label <?= $q['active'] ? 'text-success' : 'text-muted' ?>">
                    <?= $q['active'] ? 'Active' : 'Hidden' ?>
                  </span>
                </label>
              </div>
            </td>
            <td>
              <div class="action-btns">
                <a href="<?= base_url('admin/text-questions/edit/' . $q['id']) ?>"
                   class="btn btn-xs btn-outline-warning" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-xs btn-outline-danger btn-delete"
                        data-url="<?= base_url('admin/text-questions/delete/' . $q['id']) ?>" title="Delete">
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
      <i class="fas fa-list-ol"></i>
      <p>No text questions yet. Add your first one!</p>
      <a href="<?= base_url('admin/text-questions/create') ?>" class="btn btn-admin-primary">
        <i class="fas fa-plus mr-1"></i> Add Text Question
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
          <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
// Search
document.getElementById('qSearch').addEventListener('input', function () {
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
  cb.addEventListener('change', function () {
    const label = this.closest('td').querySelector('.toggle-label');
    fetch(`<?= base_url('admin/api/toggle-text-question/') ?>` + this.dataset.id, {
      method: 'POST',
      headers: {'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json'},
      body: JSON.stringify({<?= csrf_token() ?>: '<?= csrf_hash() ?>'})
    })
    .then(r => r.json())
    .then(data => {
      label.textContent = data.active ? 'Active' : 'Hidden';
      label.className = 'toggle-label ' + (data.active ? 'text-success' : 'text-muted');
      showToast(data.active ? 'Question activated' : 'Question hidden');
    });
  });
});
</script>
<?php $this->endSection() ?>
