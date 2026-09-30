/* =====================================================
   QUIZ APP — IMAGE SOURCE PICKER (question form)
   Each .img-source lets the admin choose Path / Upload / Canvas code.
   Canvas code runs as a function body with (ctx, width, height) and is
   rendered to PNG on submit; the server re-encodes to the chosen format.
   ===================================================== */

const CANVAS_TEMPLATE = `// ctx: CanvasRenderingContext2D, width/height: canvas size
const cx = width / 2, cy = height / 2;
ctx.fillStyle = '#e53935';
ctx.beginPath();
ctx.arc(cx, cy, Math.min(width, height) * 0.35, 0, Math.PI * 2);
ctx.fill();`;

document.querySelectorAll('.img-source').forEach(initImageSource);

function initImageSource(root) {
  const $ = sel => root.querySelector(sel);
  const preview   = $('.src-preview');
  const pathInput = $('.src-path');
  const fileInput = $('.src-file');
  const codeInput = $('.src-code');
  const dataInput = $('.src-canvas-data');
  const wInput    = $('.src-w');
  const hInput    = $('.src-h');
  const errorBox  = $('.src-error');
  const savedCode = codeInput.value;

  root.dirty = false; // canvas code/size changed since page load

  /* ---- Tabs ---- */
  root.querySelectorAll('.src-tab input').forEach(radio => {
    radio.addEventListener('change', () => setMode(radio.value));
  });

  function setMode(mode) {
    root.dataset.mode = mode;
    root.querySelectorAll('.src-tab').forEach(t => t.classList.toggle('active', t.querySelector('input').checked));
    moveGlider();
    if (mode === 'canvas') {
      if (!codeInput.value.trim()) { codeInput.value = CANVAS_TEMPLATE; root.dirty = true; }
      renderCanvasPreview();
    } else if (mode === 'upload' && fileInput.files[0]) {
      showFile(fileInput.files[0]);
    } else {
      showPath();
    }
  }

  function moveGlider() {
    const active = root.querySelector('.src-tab.active');
    const glider = root.querySelector('.src-tab-glider');
    if (!active || !glider) return;
    glider.style.width     = active.offsetWidth + 'px';
    glider.style.transform = `translateX(${active.offsetLeft}px)`;
  }
  requestAnimationFrame(moveGlider);
  window.addEventListener('resize', moveGlider);

  /* ---- Preview helpers ---- */
  function showNode(node) {
    preview.querySelectorAll('.src-preview-img, canvas, .src-empty').forEach(n => n.remove());
    preview.prepend(node);
    preview.classList.remove('src-pop');
    void preview.offsetWidth; // restart animation
    preview.classList.add('src-pop');
  }

  function showPath() {
    const path = pathInput.value.trim();
    if (!path) {
      const empty = document.createElement('span');
      empty.className = 'src-empty';
      empty.textContent = 'Preview will appear here';
      return showNode(empty);
    }
    const img = new Image();
    img.className = 'src-preview-img';
    img.alt = 'Preview';
    img.onerror = () => { img.alt = 'Image not found'; };
    img.src = /^(https?:|data:|\/)/.test(path) ? path : root.dataset.base + path;
    showNode(img);
  }

  function showFile(file) {
    const img = new Image();
    img.className = 'src-preview-img';
    img.alt = file.name;
    img.src = URL.createObjectURL(file);
    img.onload = () => URL.revokeObjectURL(img.src);
    showNode(img);
    $('.src-drop-text').textContent = `${file.name} (${Math.round(file.size / 1024)} KB)`;
  }

  /* ---- Path ---- */
  pathInput.addEventListener('change', showPath);

  /* ---- Upload (click or drag & drop) ---- */
  fileInput.addEventListener('change', () => fileInput.files[0] && showFile(fileInput.files[0]));
  const drop = $('.src-drop');
  ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('is-over'); }));
  ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, () => drop.classList.remove('is-over')));
  drop.addEventListener('drop', e => {
    e.preventDefault();
    if (!e.dataTransfer.files[0]) return;
    fileInput.files = e.dataTransfer.files;
    showFile(fileInput.files[0]);
  });

  /* ---- Canvas code ---- */
  root.renderCanvas = function () {
    const w = clamp(parseInt(wInput.value, 10) || +root.dataset.w);
    const h = clamp(parseInt(hInput.value, 10) || +root.dataset.h);
    const canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    try {
      new Function('ctx', 'width', 'height', codeInput.value)(canvas.getContext('2d'), w, h);
      errorBox.textContent = '';
      root.classList.remove('has-error');
      return canvas;
    } catch (err) {
      errorBox.textContent = err.message;
      root.classList.add('has-error');
      return null;
    }
  };

  function renderCanvasPreview() {
    const canvas = root.renderCanvas();
    if (canvas) showNode(canvas);
  }

  let debounce;
  const markDirty = () => {
    root.dirty = true;
    clearTimeout(debounce);
    debounce = setTimeout(renderCanvasPreview, 400);
  };
  codeInput.addEventListener('input', markDirty);
  wInput.addEventListener('input', markDirty);
  hInput.addEventListener('input', markDirty);
  $('.src-run').addEventListener('click', renderCanvasPreview);

  // Tab key inserts two spaces in the code editor
  codeInput.addEventListener('keydown', e => {
    if (e.key !== 'Tab') return;
    e.preventDefault();
    codeInput.setRangeText('  ', codeInput.selectionStart, codeInput.selectionEnd, 'end');
    markDirty();
  });

  // Saved canvas image: reuse its size so re-renders match
  if (root.dataset.mode === 'canvas' && savedCode) {
    const img = preview.querySelector('.src-preview-img');
    if (img) {
      const sync = () => { if (img.naturalWidth) { wInput.value = img.naturalWidth; hInput.value = img.naturalHeight; } };
      img.complete ? sync() : img.addEventListener('load', sync);
    }
  }

  /* ---- Submit: render canvas slots that changed (or have no image yet) ---- */
  root.prepareSubmit = function () {
    dataInput.value = '';
    if (root.dataset.mode !== 'canvas') return true;
    if (!root.dirty && pathInput.value.trim()) return true;
    const canvas = root.renderCanvas();
    if (!canvas) return false;
    dataInput.value = canvas.toDataURL('image/png');
    return true;
  };
}

function clamp(n) { return Math.max(8, Math.min(2000, n)); }

document.querySelectorAll('form').forEach(form => {
  if (!form.querySelector('.img-source')) return;
  form.addEventListener('submit', e => {
    for (const root of form.querySelectorAll('.img-source')) {
      if (!root.prepareSubmit()) {
        e.preventDefault();
        root.scrollIntoView({ behavior: 'smooth', block: 'center' });
        if (typeof showToast === 'function') showToast('Fix the canvas code error before saving.', 'error');
        return;
      }
    }
  });
});
