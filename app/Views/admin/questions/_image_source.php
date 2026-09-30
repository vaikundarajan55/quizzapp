<?php
/**
 * Image source picker — one per image slot on the question form.
 *
 * @var string $slot         unique DOM id suffix
 * @var array  $names        form field names: mode, path, upload, code, canvas, format
 * @var string $path         current image path or full https:// URL (may be '')
 * @var string $code         saved canvas code (may be '')
 * @var int    $w, $h        default canvas size
 * @var string $placeholder  path placeholder
 * @var bool   $compact      smaller layout for option cards
 */
$mode    = $code !== '' ? 'canvas' : 'path';
$curExt  = strtoupper(pathinfo(parse_url($path, PHP_URL_PATH) ?: $path, PATHINFO_EXTENSION));
$modes   = [
  'path'   => ['fa-link',   'Path / URL'],
  'upload' => ['fa-upload', 'Upload'],
  'canvas' => ['fa-code',   'Canvas'],
];
?>
<div class="img-source <?= $compact ? 'img-source-compact' : '' ?>" id="src-<?= $slot ?>"
     data-mode="<?= $mode ?>" data-w="<?= (int) $w ?>" data-h="<?= (int) $h ?>"
     data-base="<?= base_url('/') ?>">

  <div class="src-toolbar">
    <div class="src-tabs">
      <?php foreach ($modes as $m => [$icon, $text]): ?>
      <label class="src-tab <?= $mode === $m ? 'active' : '' ?>">
        <input type="radio" name="<?= $names['mode'] ?>" value="<?= $m ?>" <?= $mode === $m ? 'checked' : '' ?> />
        <i class="fas <?= $icon ?>"></i><span><?= $text ?></span>
      </label>
      <?php endforeach; ?>
      <span class="src-tab-glider"></span>
    </div>

    <select name="<?= $names['format'] ?>" class="src-format" title="Save image as">
      <option value="">Keep<?= $curExt ? ' (' . esc($curExt) . ')' : ' format' ?></option>
      <option value="png">PNG</option>
      <option value="jpg">JPEG</option>
      <option value="webp">WebP</option>
    </select>
  </div>

  <!-- Path -->
  <div class="src-pane" data-pane="path">
    <input type="text" name="<?= $names['path'] ?>"
           class="form-control admin-input <?= $compact ? 'admin-input-sm' : '' ?> src-path"
           value="<?= esc($path) ?>" placeholder="<?= esc($placeholder) ?>" />
  </div>

  <!-- Upload -->
  <div class="src-pane" data-pane="upload">
    <label class="src-drop">
      <input type="file" name="<?= $names['upload'] ?>" accept="image/png,image/jpeg,image/webp,image/gif" class="src-file" />
      <i class="fas fa-cloud-upload-alt"></i>
      <span class="src-drop-text">Choose or drop an image</span>
    </label>
  </div>

  <!-- Canvas code -->
  <div class="src-pane" data-pane="canvas">
    <textarea name="<?= $names['code'] ?>" class="src-code" rows="<?= $compact ? 5 : 8 ?>" spellcheck="false"
              placeholder="// ctx, width, height are available"><?= esc($code) ?></textarea>
    <div class="src-canvas-bar">
      <button type="button" class="btn btn-xs btn-admin-primary src-run"><i class="fas fa-play mr-1"></i>Run</button>
      <span class="src-size">
        <input type="number" class="src-w" min="8" max="2000" value="<?= (int) $w ?>" aria-label="Width" />
        ×
        <input type="number" class="src-h" min="8" max="2000" value="<?= (int) $h ?>" aria-label="Height" />
        px
      </span>
    </div>
    <div class="src-error"></div>
    <input type="hidden" name="<?= $names['canvas'] ?>" class="src-canvas-data" value="" />
  </div>

  <div class="src-preview">
    <?php if ($path !== ''): ?>
    <img src="<?= esc(image_url($path)) ?>" alt="Preview" class="src-preview-img" />
    <?php else: ?>
    <span class="src-empty">Preview will appear here</span>
    <?php endif; ?>
    <?php if ($curExt): ?><span class="src-badge"><?= esc($curExt) ?></span><?php endif; ?>
  </div>
</div>
