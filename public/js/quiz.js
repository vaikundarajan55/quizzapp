// =====================================================
// QUIZ APP — quiz.js  (CI4 version)
// Drag-and-drop + touch tap-select quiz logic
// QUIZ_DATA, QUIZ_SETTINGS, SUBMIT_URL, QUIZ_TYPE injected by PHP
// =====================================================

let QUESTIONS    = [];
let answers      = {};      // { qId: { optionIndex, correct } }
let voiceEnabled = QUIZ_SETTINGS.voice_enabled !== false;
let currentIndex = 0;
let dragState    = null;
let touchSelected = null;   // { img, qid, optidx }
let elapsedSecs  = 0;

const quizContainer = document.getElementById('quizContainer');

// =====================================================
// TIMER
// =====================================================
const timerEl = document.getElementById('quizTimer');
setInterval(() => {
  elapsedSecs++;
  const m = String(Math.floor(elapsedSecs / 60)).padStart(2, '0');
  const s = String(elapsedSecs % 60).padStart(2, '0');
  if (timerEl) timerEl.textContent = `${m}:${s}`;
}, 1000);

// =====================================================
// VOICE
// =====================================================
function speak(text) {
  if (!voiceEnabled) return;
  if (!('speechSynthesis' in window)) return;
  window.speechSynthesis.cancel();
  const clean = text.replace(/[""\"]/g, '').replace(/\\n/g, '. ');
  const utter = new SpeechSynthesisUtterance(clean);
  utter.rate  = 0.95;
  utter.pitch = 1;
  window.speechSynthesis.speak(utter);
}

const voiceBtn = document.getElementById('voiceToggleBtn');
if (voiceBtn) {
  voiceBtn.addEventListener('click', () => {
    voiceEnabled = !voiceEnabled;
    voiceBtn.innerHTML = voiceEnabled
      ? '<i class="fas fa-volume-up mr-1"></i> Voice: On'
      : '<i class="fas fa-volume-mute mr-1"></i> Voice: Off';
    voiceBtn.classList.toggle('voice-off', !voiceEnabled);
    if (!voiceEnabled && 'speechSynthesis' in window) window.speechSynthesis.cancel();
  });
}

// =====================================================
// INIT
// =====================================================
function loadData() {
  QUESTIONS = QUIZ_DATA;
  renderQuiz();
}

// =====================================================
// RENDER CURRENT QUESTION
// =====================================================
function renderQuiz() {
  if (!QUESTIONS.length) return; // keep the server-rendered "no questions" message
  quizContainer.innerHTML = '';

  if (currentIndex < 0) currentIndex = 0;
  if (currentIndex > QUESTIONS.length - 1) currentIndex = QUESTIONS.length - 1;

  const q       = QUESTIONS[currentIndex];
  const status  = answers[q.id]
    ? (answers[q.id].correct ? 'status-correct' : 'status-incorrect')
    : 'status-pending';
  const isFirst = currentIndex === 0;
  const isLast  = currentIndex === QUESTIONS.length - 1;

  const card = document.createElement('div');
  card.className = `question-card ${status}`;
  card.id = `card-${q.id}`;

  card.innerHTML = `
    <div class="q-index">Question ${currentIndex + 1} of ${QUESTIONS.length}</div>

    ${q.type === 'text' ? textQuestionBody(q) : `
    <div class="drop-zone" id="dzone-${q.id}">
      <img class="question-img" src="${q.questionImage}" alt="Question ${q.id}" />
      <div class="topmark-zone" id="topmark-${q.id}" data-qid="${q.id}"></div>
    </div>

    <div class="options-row" id="options-${q.id}">
      ${q.options.map((opt, idx) => `
        <img
          class="option-img"
          src="${opt.file}"
          alt="${opt.label}"
          draggable="false"
          data-qid="${q.id}"
          data-optidx="${idx}"
        />`).join('')}
    </div>

    <div class="q-prompt">${q.prompt}</div>
    `}

    ${QUIZ_SETTINGS.allow_retry !== false
      ? `<button class="q-clear-btn" data-qid="${q.id}">Clear answer</button>`
      : ''}

    <div id="answerCard-${q.id}"></div>

    <div class="question-nav">
      <button class="nav-btn prev-btn" id="prevBtn" ${isFirst ? 'disabled' : ''}>&larr; Previous</button>
      <button class="nav-btn next-btn" id="nextBtn" ${isLast ? 'disabled' : ''}>Next &rarr;</button>
    </div>
  `;

  quizContainer.appendChild(card);

  // Re-apply existing answer
  const existing = answers[q.id];
  if (existing) {
    restoreAnswer(q, existing);
    if (QUIZ_SETTINGS.show_answer_card !== false) showAnswerCard(q, existing.correct);
  }

  attachCardEvents();
}

// =====================================================
// RESTORE ANSWER STATE ON RENDER
// =====================================================
function restoreAnswer(q, ans) {
  if (q.type === 'text') return markTextOption(q.id, ans.optionIndex, ans.correct);

  const zone   = document.getElementById(`topmark-${q.id}`);
  const option = q.options[ans.optionIndex];
  if (!zone || !option) return;

  zone.classList.add('filled');
  zone.innerHTML = '';

  const mark = document.createElement('img');
  mark.className = 'dropped-mark';
  mark.src = option.file;
  mark.alt = option.label;
  zone.appendChild(mark);

  const badge = document.createElement('div');
  badge.className = `dropped-badge ${ans.correct ? 'correct' : 'incorrect'}`;
  badge.textContent = ans.correct ? '✓' : '✕';
  zone.appendChild(badge);

  const card = document.getElementById(`card-${q.id}`);
  if (card) {
    card.classList.remove('status-pending', 'status-correct', 'status-incorrect');
    card.classList.add(ans.correct ? 'status-correct' : 'status-incorrect');
  }
}

// =====================================================
// ANSWER CARD (shown after drop)
// =====================================================
function showAnswerCard(q, isCorrect) {
  const box = document.getElementById(`answerCard-${q.id}`);
  if (!box) return;

  const raw = (q.answerCardText || q.answerLabel || '')
    .replace(/\\n/g, '\n')
    .replace(/[""]/g, '"');

  const highlighted = raw
    .replace(/\b(red)\b/gi,    '<span class="color-red">$1</span>')
    .replace(/\b(green)\b/gi,  '<span class="color-green">$1</span>')
    .replace(/\b(black)\b/gi,  '<span class="color-black">$1</span>')
    .replace(/\b(yellow)\b/gi, '<span class="color-yellow">$1</span>');

  const lines = highlighted.split('\n').map(l => `<div>${l}</div>`).join('');

  box.innerHTML = `
    <div class="answer-card">
      <strong style="color:${isCorrect ? '#2ecc71' : '#e74c3c'};font-size:12px">
        ${isCorrect ? '✓ CORRECT' : '✕ INCORRECT — Correct answer:'}
      </strong>
      <div style="margin-top:6px">${lines}</div>
      ${voiceEnabled && q.answerCardText
        ? `<button class="replay-btn" onclick="speak(\`${(q.answerCardText||'').replace(/`/g,"'")}\`)">
             <i class="fas fa-volume-up"></i> Replay
           </button>`
        : ''}
    </div>`;
}

// =====================================================
// PLACE ANSWER
// =====================================================
function placeAnswer(qidStr, optIdxStr) {
  const qid    = parseInt(qidStr, 10);
  const optIdx = parseInt(optIdxStr, 10);
  const q      = QUESTIONS.find(q => q.id === qid);
  if (!q) return;

  const option    = q.options[optIdx];
  const isCorrect = !!option.correct;

  answers[qid] = { optionIndex: optIdx, correct: isCorrect };

  if (q.type === 'text') {
    markTextOption(qid, optIdx, isCorrect);
    if (QUIZ_SETTINGS.show_answer_card !== false) showAnswerCard(q, isCorrect);
    if (isCorrect) speak(q.answerCardText || q.answerLabel || '');
    return;
  }

  const zone = document.getElementById(`topmark-${qid}`);
  zone.querySelectorAll('.dropped-mark,.dropped-badge').forEach(el => el.remove());
  zone.classList.add('filled');

  const mark = document.createElement('img');
  mark.className = 'dropped-mark';
  mark.src = option.file;
  mark.alt = option.label;
  zone.appendChild(mark);

  const badge = document.createElement('div');
  badge.className = `dropped-badge ${isCorrect ? 'correct' : 'incorrect'}`;
  badge.textContent = isCorrect ? '✓' : '✕';
  zone.appendChild(badge);

  const card = document.getElementById(`card-${qid}`);
  if (card) {
    card.classList.remove('status-pending','status-correct','status-incorrect');
    card.classList.add(isCorrect ? 'status-correct' : 'status-incorrect');
  }

  if (QUIZ_SETTINGS.show_answer_card !== false) showAnswerCard(q, isCorrect);
  if (isCorrect) speak(q.answerCardText || q.answerLabel || '');

}

// =====================================================
// CLEAR ANSWER
// =====================================================
function clearAnswer(qidStr) {
  const qid  = parseInt(qidStr, 10);
  delete answers[qid];

  const zone = document.getElementById(`topmark-${qid}`);
  if (zone) {
    zone.querySelectorAll('.dropped-mark,.dropped-badge').forEach(el => el.remove());
    zone.classList.remove('filled');
    zone.classList.remove('touch-selected');
  }

  const card = document.getElementById(`card-${qid}`);
  if (card) {
    card.classList.remove('status-correct','status-incorrect');
    card.classList.add('status-pending');
  }

  markTextOption(qid, null, false);

  const box = document.getElementById(`answerCard-${qid}`);
  if (box) box.innerHTML = '';

  touchSelected = null;
}

// =====================================================
// TEXT QUESTIONS (4 clickable text options)
// =====================================================
function escHtml(str) {
  const d = document.createElement('div');
  d.textContent = str ?? '';
  return d.innerHTML;
}

function textQuestionBody(q) {
  return `
    ${q.questionImage
      ? `<div class="text-q-media"><img class="text-q-img" src="${escHtml(q.questionImage)}" alt="Question ${q.id}" /></div>`
      : ''}
    <div class="q-prompt q-prompt-lg">${escHtml(q.prompt)}</div>
    <div class="text-options" id="options-${q.id}">
      ${q.options.map((opt, idx) => `
        <label class="text-option">
          <input type="radio" class="text-option-radio" name="answer-${q.id}"
                 data-qid="${q.id}" data-optidx="${idx}" />
          <span class="text-option-dot"></span>
          <span class="text-option-label"><b>${String.fromCharCode(65 + idx)}.</b> ${escHtml(opt.label)}</span>
          <span class="text-option-mark"></span>
        </label>`).join('')}
    </div>`;
}

/** Checks + highlights the chosen radio option (optIdx null = clear). */
function markTextOption(qid, optIdx, isCorrect) {
  const options = document.querySelectorAll(`#options-${qid} .text-option`);
  if (!options.length) return;

  const locked = optIdx !== null && QUIZ_SETTINGS.allow_retry === false;
  options.forEach((opt, i) => {
    const chosen = i === optIdx;
    const radio  = opt.querySelector('.text-option-radio');
    radio.checked  = chosen;
    radio.disabled = locked;
    opt.classList.toggle('locked', locked);
    opt.classList.toggle('correct', chosen && isCorrect);
    opt.classList.toggle('incorrect', chosen && !isCorrect);
    opt.querySelector('.text-option-mark').textContent = chosen ? (isCorrect ? '✓' : '✕') : '';
  });

  const card = document.getElementById(`card-${qid}`);
  if (card) {
    card.classList.remove('status-pending', 'status-correct', 'status-incorrect');
    card.classList.add(optIdx === null ? 'status-pending' : (isCorrect ? 'status-correct' : 'status-incorrect'));
  }
}

function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

// =====================================================
// SUBMIT
// =====================================================
function showSummary() {
  const total     = QUESTIONS.length;
  const filled    = Object.keys(answers).length;
  const correct   = Object.values(answers).filter(a => a.correct).length;
  const incorrect = filled - correct;
  const score     = total > 0 ? Math.round((correct / total) * 100) : 0;
  const pass      = score >= (QUIZ_SETTINGS.passing_score || 70);

  setText('modalTotal',     total);
  setText('modalFilled',    filled);
  setText('modalCorrect',   correct);
  setText('modalIncorrect', incorrect);
  setText('modalScore',     score + '%');

  const circle = document.getElementById('modalScoreCircle');
  if (circle) { circle.classList.remove('pass','fail'); circle.classList.add(pass ? 'pass' : 'fail'); }

  const verdict = document.getElementById('modalVerdict');
  if (verdict) verdict.textContent = pass ? '🎉 You Passed!' : '😔 Not quite — try again!';

  $('#resultModal').modal('show');

  // Save result via AJAX
  const payload = {};
  QUESTIONS.forEach(q => { if (answers[q.id] !== undefined) payload[q.id] = answers[q.id].optionIndex; });

  fetch(SUBMIT_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ answers: JSON.stringify(payload), elapsed: elapsedSecs, type: QUIZ_TYPE, 'csrf_test_name': getCsrf() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.token) {
      const actions = document.getElementById('modalActions');
      if (actions) {
        actions.innerHTML = `
          <a href="${window.location.origin}/results/${data.token}" class="btn btn-site-primary">
            <i class="fas fa-chart-bar mr-1"></i> Full Results
          </a>
          <button id="resetBtn" class="btn btn-outline-secondary">
            <i class="fas fa-redo mr-1"></i> Try Again
          </button>`;
        document.getElementById('resetBtn')?.addEventListener('click', resetQuiz);
      }
    }
  })
  .catch(() => {});
}

function getCsrf() {
  const m = document.cookie.match(/csrf_test_name=([^;]+)/);
  return m ? m[1] : '';
}

function resetQuiz() {
  answers      = {};
  touchSelected = null;
  currentIndex  = 0;
  elapsedSecs   = 0;
  renderQuiz();
  $('#resultModal').modal('hide');
}

// =====================================================
// DRAG  (pointer events — desktop)
// =====================================================
function attachCardEvents() {
  document.querySelectorAll('.option-img').forEach(img => {
    img.addEventListener('pointerdown', onDragStart);
  });

  document.querySelectorAll('.q-clear-btn').forEach(btn => {
    btn.addEventListener('click', () => clearAnswer(btn.dataset.qid));
  });

  document.getElementById('prevBtn')?.addEventListener('click', () => { currentIndex--; renderQuiz(); });
  document.getElementById('nextBtn')?.addEventListener('click', () => { currentIndex++; renderQuiz(); });

  // Touch: tap option to select, tap zone to drop
  document.querySelectorAll('.option-img').forEach(img => {
    img.addEventListener('click', onTouchOptionClick);
  });

  document.querySelectorAll('.topmark-zone').forEach(zone => {
    zone.addEventListener('click', onTouchZoneClick);
  });

  document.querySelectorAll('.text-option-radio').forEach(radio => {
    radio.addEventListener('change', () => placeAnswer(radio.dataset.qid, radio.dataset.optidx));
  });
}

// ---- pointer drag ----
function onDragStart(e) {
  const img  = e.currentTarget;
  const rect = img.getBoundingClientRect();

  const clone = img.cloneNode(true);
  clone.classList.add('drag-clone');
  clone.style.cssText = `position:fixed;left:${rect.left}px;top:${rect.top}px;width:${rect.width}px;height:${rect.height}px;margin:0;pointer-events:none;z-index:9999`;
  document.body.appendChild(clone);

  dragState = {
    img, clone,
    qid: img.dataset.qid,
    optidx: img.dataset.optidx,
    offsetX: e.clientX - rect.left,
    offsetY: e.clientY - rect.top,
    currentZone: null,
  };

  img.classList.add('dragging');
  document.body.classList.add('dnd-active');

  document.addEventListener('pointermove', onPointerMove);
  document.addEventListener('pointerup',   onPointerUp);
  document.addEventListener('pointercancel', onPointerUp);
}

function onPointerMove(e) {
  if (!dragState) return;
  e.preventDefault();
  const { clone, offsetX, offsetY, qid } = dragState;
  clone.style.left = `${e.clientX - offsetX}px`;
  clone.style.top  = `${e.clientY - offsetY}px`;

  clone.style.visibility = 'hidden';
  const under = document.elementFromPoint(e.clientX, e.clientY);
  clone.style.visibility = 'visible';

  const zone      = under?.closest('.topmark-zone');
  const validZone = zone && zone.dataset.qid === qid ? zone : null;

  if (dragState.currentZone && dragState.currentZone !== validZone) dragState.currentZone.classList.remove('drag-over');
  if (validZone) validZone.classList.add('drag-over');
  dragState.currentZone = validZone;
}

function onPointerUp() {
  if (!dragState) return;
  const { img, clone, qid, optidx, currentZone } = dragState;

  document.removeEventListener('pointermove', onPointerMove);
  document.removeEventListener('pointerup',   onPointerUp);
  document.removeEventListener('pointercancel', onPointerUp);

  img.classList.remove('dragging');
  document.body.classList.remove('dnd-active');
  clone.remove();
  if (currentZone) { currentZone.classList.remove('drag-over'); placeAnswer(qid, optidx); }
  dragState = null;
}

// ---- touch / tap select ----
function onTouchOptionClick(e) {
  const img    = e.currentTarget;
  const qid    = img.dataset.qid;
  const optidx = img.dataset.optidx;

  // Deselect all in this card
  document.querySelectorAll(`#options-${qid} .option-img`).forEach(i => i.classList.remove('touch-selected'));

  if (touchSelected && touchSelected.img === img) {
    // deselect
    touchSelected = null;
    return;
  }

  img.classList.add('touch-selected');
  touchSelected = { img, qid, optidx };

  // Also light up the drop zone
  const zone = document.getElementById(`topmark-${qid}`);
  if (zone) zone.classList.add('touch-selected');
}

function onTouchZoneClick(e) {
  if (!touchSelected) return;
  const zone = e.currentTarget;
  const qid  = zone.dataset.qid;
  if (touchSelected.qid !== qid) return;

  zone.classList.remove('touch-selected');
  touchSelected.img.classList.remove('touch-selected');
  placeAnswer(qid, touchSelected.optidx);
  touchSelected = null;
}

// =====================================================
// GLOBAL BUTTONS
// =====================================================
document.getElementById('submitBtn')?.addEventListener('click', showSummary);
document.getElementById('resetBtn2')?.addEventListener('click', resetQuiz);
document.getElementById('resetBtn')?.addEventListener('click', resetQuiz);

// =====================================================
// START
// =====================================================
loadData();
