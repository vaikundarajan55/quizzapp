<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>
<?php
$isEdit = !empty($question);

// Re-fill from the failed submit first, then the saved question
$prompt      = old('prompt') ?? ($question['prompt'] ?? '');
$explanation = old('answerCardText') ?? ($question['answerCardText'] ?? '');
$oldLabels   = old('opt_label');
$labels      = [];
$correctIdx  = old('opt_correct');
foreach (range(0, 3) as $i) {
    $labels[$i] = $oldLabels[$i] ?? ($question['options'][$i]['label'] ?? '');
    if ($correctIdx === null && !empty($question['options'][$i]['correct'])) {
        $correctIdx = $i;
    }
}
$correctIdx = $correctIdx === null ? -1 : (int) $correctIdx;
$active     = $isEdit ? !empty($question['active']) : true;
?>

<div class="page-header">
  <div>
    <h2 class="page-title"><?= $isEdit ? 'Edit Text Question #' . $question['id'] : 'New Text Question' ?></h2>
    <p class="page-sub">Add the question image and text, 4 text answers, and mark the correct one</p>
  </div>
  <a href="<?= base_url('admin/text-questions') ?>" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left mr-1"></i> Back
  </a>
</div>

<form method="post"
      action="<?= $isEdit ? base_url('admin/text-questions/update/' . $question['id']) : base_url('admin/text-questions/store') ?>"
      enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="row">
    <div class="col-lg-7">
      <div class="admin-card animate-fadeIn mb-4">
        <div class="admin-card-header">
          <h5><i class="fas fa-question mr-2"></i>Question</h5>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label-admin">Question Image <small class="text-muted">(path / URL, upload or canvas code)</small></label>
            <?= view('admin/questions/_image_source', [
              'slot'        => 'q',
              'names'       => [
                'mode'   => 'questionImageMode',   'path'   => 'questionImage',
                'upload' => 'questionImageUpload', 'code'   => 'questionImageCode',
                'canvas' => 'questionImageCanvas', 'format' => 'questionImageFormat',
              ],
              'path'        => $question['questionImage'] ?? '',
              'code'        => $question['questionImageCode'] ?? '',
              'w'           => 400,
              'h'           => 300,
              'placeholder' => 'assets/questions/q1.png or https://…',
              'compact'     => false,
            ], ['saveData' => false]) ?>
          </div>

          <div class="form-group">
            <label class="form-label-admin" for="prompt">Question <span class="text-danger">*</span></label>
            <textarea name="prompt" id="prompt" class="form-control admin-input" rows="3" required
                      placeholder="e.g. What colour is the IALA Region A port hand lateral mark?"><?= esc($prompt) ?></textarea>
          </div>

          <div class="form-group">
            <label class="form-label-admin" for="answerCardText">Explanation <small class="text-muted">(optional)</small></label>
            <textarea name="answerCardText" id="answerCardText" class="form-control admin-input" rows="2"
                      placeholder="Shown / spoken after answering"><?= esc($explanation) ?></textarea>
          </div>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" name="active" id="activeCheck" value="1" <?= $active ? 'checked' : '' ?>>
            <label class="form-check-label" for="activeCheck">Active (visible to quiz takers)</label>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="admin-card animate-fadeIn mb-4" style="animation-delay:.1s">
        <div class="admin-card-header">
          <h5><i class="fas fa-list-ol mr-2"></i>Answer Options</h5>
          <span class="badge badge-info">4 options</span>
        </div>
        <div class="admin-card-body">
          <div class="option-list">
            <?php foreach ($labels as $i => $label): ?>
            <div class="option-item <?= $i === $correctIdx ? 'option-correct' : '' ?>">
              <div class="option-header">
                <div class="option-letter"><?= chr(65 + $i) ?></div>
                <div class="option-radio-wrap">
                  <input type="radio" name="opt_correct" value="<?= $i ?>" id="correct<?= $i ?>" required
                         <?= $i === $correctIdx ? 'checked' : '' ?> onchange="highlightCorrect(<?= $i ?>)" />
                  <label for="correct<?= $i ?>" class="correct-radio-label">✓ Correct</label>
                </div>
              </div>
              <input type="text" name="opt_label[<?= $i ?>]" class="form-control admin-input admin-input-sm"
                     value="<?= esc($label) ?>" placeholder="Option <?= chr(65 + $i) ?> text" required />
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="admin-card animate-fadeIn mb-4" style="animation-delay:.2s">
        <div class="admin-card-body">
          <button type="submit" class="btn btn-admin-primary btn-block">
            <i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Update Question' : 'Save Question' ?>
          </button>
          <a href="<?= base_url('admin/text-questions') ?>" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/admin/js/image-source.js') ?>"></script>
<script>
function highlightCorrect(idx) {
  document.querySelectorAll('.option-item').forEach((el, i) => el.classList.toggle('option-correct', i === idx));
}
</script>
<?php $this->endSection() ?>
