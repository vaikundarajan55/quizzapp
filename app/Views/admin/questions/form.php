<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>
<?php $isEdit = !empty($question); ?>

<div class="page-header">
  <div>
    <h2 class="page-title"><?= $isEdit ? 'Edit Question #' . $question['id'] : 'New Question' ?></h2>
    <p class="page-sub"><?= $isEdit ? 'Update question details and options' : 'Fill in all fields to add a new question' ?></p>
  </div>
  <a href="<?= base_url('admin/questions') ?>" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left mr-1"></i> Back
  </a>
</div>

<form method="post"
      action="<?= $isEdit ? base_url('admin/questions/update/' . $question['id']) : base_url('admin/questions/store') ?>"
      enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="row">
    <!-- Left: Main fields -->
    <div class="col-lg-7">
      <div class="admin-card animate-fadeIn mb-4">
        <div class="admin-card-header">
          <h5><i class="fas fa-info-circle mr-2"></i>Question Details</h5>
        </div>
        <div class="admin-card-body">

          <div class="form-group">
            <label class="form-label-admin">Question Prompt <span class="text-danger">*</span></label>
            <textarea name="prompt" class="form-control admin-input" rows="3" required
                      placeholder="e.g. Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark."
            ><?= esc($question['prompt'] ?? '') ?></textarea>
          </div>

          <div class="form-group">
            <label class="form-label-admin">Answer Label <span class="text-danger">*</span></label>
            <input type="text" name="answerLabel" class="form-control admin-input"
                   value="<?= esc($question['answerLabel'] ?? '') ?>"
                   placeholder="e.g. IALA Region A – Lateral Port Hand Mark (Red Can)" required />
          </div>

          <div class="form-group">
            <label class="form-label-admin">Answer Card Text</label>
            <textarea name="answerCardText" class="form-control admin-input" rows="3"
                      placeholder="Text shown in the answer card & spoken aloud (use \n for newlines)"
            ><?= esc($question['answerCardText'] ?? '') ?></textarea>
            <small class="text-muted">Use \n to separate lines. Color words (red/green) are highlighted automatically.</small>
          </div>

          <div class="form-group">
            <label class="form-label-admin">Question Image <span class="text-danger">*</span></label>
            <?= view('admin/questions/_image_source', [
              'slot'        => 'q',
              'names'       => [
                'mode'   => 'questionImageMode',   'path'   => 'questionImage',
                'upload' => 'questionImageUpload', 'code'   => 'questionImageCode',
                'canvas' => 'questionImageCanvas', 'format' => 'questionImageFormat',
              ],
              'path'        => $question['questionImage'] ?? '',
              'code'        => $question['questionImageCode'] ?? '',
              'w'           => 263,
              'h'           => 395,
              'placeholder' => 'assets/questions/q1.png',
              'compact'     => false,
            ], ['saveData' => false]) ?>
          </div>

          <div class="form-group form-check mt-3">
            <input type="checkbox" class="form-check-input" name="active" id="activeCheck" value="1"
                   <?= ($question['active'] ?? true) ? 'checked' : '' ?>>
            <label class="form-check-label" for="activeCheck">Active (visible to quiz takers)</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Options -->
    <div class="col-lg-5">
      <div class="admin-card animate-fadeIn mb-4" style="animation-delay:.1s">
        <div class="admin-card-header">
          <h5><i class="fas fa-list-ul mr-2"></i>Answer Options</h5>
          <span class="badge badge-info">Exactly 4 options</span>
        </div>
        <div class="admin-card-body">
          <p class="text-muted small mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            Pick an image source for each option (path, upload or canvas code) and mark which one is correct.
          </p>

          <?php
          $defaultOptions = [
            ['label'=>'Option A','file'=>'','code'=>'','correct'=>false],
            ['label'=>'Option B','file'=>'','code'=>'','correct'=>false],
            ['label'=>'Option C','file'=>'','code'=>'','correct'=>true],
            ['label'=>'Option D','file'=>'','code'=>'','correct'=>false],
          ];
          $opts = $question['options'] ?? $defaultOptions;
          $correctIdx = 0;
          foreach ($opts as $i => $opt) {
            if (!empty($opt['correct'])) { $correctIdx = $i; break; }
          }
          ?>

          <div class="option-list" id="optionList">
            <?php foreach ($opts as $i => $opt): ?>
            <div class="option-item <?= !empty($opt['correct']) ? 'option-correct' : '' ?>" id="optItem<?= $i ?>">
              <div class="option-header">
                <div class="option-letter"><?= chr(65 + $i) ?></div>
                <div class="option-radio-wrap">
                  <input type="radio" name="opt_correct" value="<?= $i ?>"
                         id="correct<?= $i ?>"
                         <?= !empty($opt['correct']) ? 'checked' : '' ?>
                         onchange="highlightCorrect(<?= $i ?>)" />
                  <label for="correct<?= $i ?>" class="correct-radio-label">✓ Correct</label>
                </div>
              </div>
              <div class="form-group mb-2">
                <input type="text" name="opt_label[<?= $i ?>]" class="form-control admin-input admin-input-sm"
                       value="<?= esc($opt['label']) ?>" placeholder="Label (e.g. Red Can)" />
              </div>
              <?= view('admin/questions/_image_source', [
                'slot'        => 'o' . $i,
                'names'       => [
                  'mode'   => "opt_mode[$i]",   'path'   => "opt_file[$i]",
                  'upload' => "opt_upload[$i]", 'code'   => "opt_code[$i]",
                  'canvas' => "opt_canvas[$i]", 'format' => "opt_format[$i]",
                ],
                'path'        => $opt['file'] ?? '',
                'code'        => $opt['code'] ?? '',
                'w'           => 148,
                'h'           => 148,
                'placeholder' => 'assets/options/q1_' . chr(97 + $i) . '.png',
                'compact'     => true,
              ], ['saveData' => false]) ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Submit -->
      <div class="admin-card animate-fadeIn mb-4" style="animation-delay:.2s">
        <div class="admin-card-body">
          <button type="submit" class="btn btn-admin-primary btn-block">
            <i class="fas fa-save mr-1"></i>
            <?= $isEdit ? 'Update Question' : 'Save Question' ?>
          </button>
          <a href="<?= base_url('admin/questions') ?>" class="btn btn-outline-secondary btn-block mt-2">
            Cancel
          </a>
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
  document.querySelectorAll('.option-item').forEach((el, i) => {
    el.classList.toggle('option-correct', i === idx);
  });
}
</script>
<?php $this->endSection() ?>
