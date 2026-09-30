<?php $this->extend('admin/layouts/main') ?>
<?php $this->section('content') ?>

<div class="page-header">
  <div>
    <h2 class="page-title">Settings</h2>
    <p class="page-sub">Configure quiz behaviour and appearance</p>
  </div>
</div>

<form method="post" action="<?= base_url('admin/settings/save') ?>">
  <?= csrf_field() ?>
  <div class="row">
    <!-- General -->
    <div class="col-lg-6 mb-4">
      <div class="admin-card animate-fadeIn">
        <div class="admin-card-header">
          <h5><i class="fas fa-info-circle mr-2"></i>General</h5>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label-admin">Quiz Title</label>
            <input type="text" name="quiz_title" class="form-control admin-input"
                   value="<?= esc($settings['quiz_title']) ?>" required />
          </div>
          <div class="form-group">
            <label class="form-label-admin">Quiz Subtitle</label>
            <input type="text" name="quiz_subtitle" class="form-control admin-input"
                   value="<?= esc($settings['quiz_subtitle']) ?>" />
          </div>
          <div class="form-group">
            <label class="form-label-admin">Passing Score (%)</label>
            <input type="number" name="passing_score" class="form-control admin-input"
                   value="<?= (int)$settings['passing_score'] ?>"
                   min="1" max="100" />
          </div>
          <div class="form-group">
            <label class="form-label-admin">Questions Per Quiz</label>
            <input type="number" name="questions_per_quiz" class="form-control admin-input"
                   value="<?= (int)$settings['questions_per_quiz'] ?>"
                   min="0" />
            <small class="text-muted">Set to 0 to show all active questions.</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="col-lg-6 mb-4">
      <div class="admin-card animate-fadeIn" style="animation-delay:.1s">
        <div class="admin-card-header">
          <h5><i class="fas fa-sliders-h mr-2"></i>Features</h5>
        </div>
        <div class="admin-card-body">

          <div class="settings-toggle-row">
            <div>
              <div class="stg-label">Voice Read-Aloud</div>
              <div class="stg-sub">Speaks correct answer text after each drop</div>
            </div>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" name="voice_enabled"
                     id="voiceEnabled" value="1" <?= !empty($settings['voice_enabled']) ? 'checked' : '' ?>>
              <label class="custom-control-label" for="voiceEnabled"></label>
            </div>
          </div>

          <div class="settings-toggle-row">
            <div>
              <div class="stg-label">Show Answer Card</div>
              <div class="stg-sub">Display the correct answer after each attempt</div>
            </div>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" name="show_answer_card"
                     id="showAnswerCard" value="1" <?= !empty($settings['show_answer_card']) ? 'checked' : '' ?>>
              <label class="custom-control-label" for="showAnswerCard"></label>
            </div>
          </div>

          <div class="settings-toggle-row">
            <div>
              <div class="stg-label">Allow Retry</div>
              <div class="stg-sub">Let users clear their answer and try again</div>
            </div>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" name="allow_retry"
                     id="allowRetry" value="1" <?= !empty($settings['allow_retry']) ? 'checked' : '' ?>>
              <label class="custom-control-label" for="allowRetry"></label>
            </div>
          </div>

        </div>
      </div>

      <!-- Appearance -->
      <div class="admin-card animate-fadeIn mt-0" style="animation-delay:.2s">
        <div class="admin-card-header">
          <h5><i class="fas fa-palette mr-2"></i>Appearance</h5>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label-admin">Theme Colour</label>
            <div class="d-flex align-items-center">
              <input type="color" name="theme_color" class="form-control admin-input mr-2"
                     value="<?= esc($settings['theme_color']) ?>"
                     style="width:60px;height:38px;padding:2px" />
              <input type="text" id="themeColorText"
                     class="form-control admin-input"
                     value="<?= esc($settings['theme_color']) ?>"
                     maxlength="7" style="width:120px" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label-admin">Background Style</label>
            <select name="bg_type" class="form-control admin-input">
              <option value="ocean"    <?= $settings['bg_type'] === 'ocean'    ? 'selected' : '' ?>>🌊 Animated Ocean</option>
              <option value="gradient" <?= $settings['bg_type'] === 'gradient' ? 'selected' : '' ?>>🎨 Gradient</option>
              <option value="plain"    <?= $settings['bg_type'] === 'plain'    ? 'selected' : '' ?>>⬛ Plain Dark</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-right mb-4">
    <button type="submit" class="btn btn-admin-primary px-5">
      <i class="fas fa-save mr-1"></i> Save Settings
    </button>
  </div>
</form>

<!-- Change Password -->
<form method="post" action="<?= base_url('admin/settings/password') ?>">
  <?= csrf_field() ?>
  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="admin-card animate-fadeIn">
        <div class="admin-card-header">
          <h5><i class="fas fa-key mr-2"></i>Change Password</h5>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label-admin">Current Password</label>
            <input type="password" name="current_password" class="form-control admin-input"
                   autocomplete="current-password" required />
          </div>
          <div class="form-group">
            <label class="form-label-admin">New Password</label>
            <input type="password" name="new_password" class="form-control admin-input"
                   autocomplete="new-password" minlength="8" required />
            <small class="text-muted">At least 8 characters.</small>
          </div>
          <div class="form-group">
            <label class="form-label-admin">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control admin-input"
                   autocomplete="new-password" minlength="8" required />
          </div>
          <button type="submit" class="btn btn-admin-primary">
            <i class="fas fa-key mr-1"></i> Update Password
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
// Sync color picker ↔ text input
const colorPicker = document.querySelector('input[name="theme_color"]');
const colorText   = document.getElementById('themeColorText');
colorPicker.addEventListener('input', () => colorText.value = colorPicker.value);
colorText.addEventListener('input', () => {
  if (/^#[0-9a-f]{6}$/i.test(colorText.value)) colorPicker.value = colorText.value;
});
</script>
<?php $this->endSection() ?>
