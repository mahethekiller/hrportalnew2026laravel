/* ==========================================================================
   TEKKEN 7: TEEJ SPECIAL SHOWDOWN - APPLICATION LOGIC (LARAVEL INTEGRATED)
   Handles: Tab Navigation, Real-time Fee Calc, Canvas QR, Audio Synth, AJAX Sync
   ========================================================================== */

(function () {
  'use strict';

  let isMuted = false;

  // Web Audio API Arcade Sound Synthesizer
  const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

  function playSound(type) {
    if (isMuted) return;
    try {
      if (audioCtx.state === 'suspended') {
        audioCtx.resume();
      }
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.connect(gain);
      gain.connect(audioCtx.destination);

      const now = audioCtx.currentTime;

      if (type === 'click') {
        osc.type = 'sine';
        osc.frequency.setValueAtTime(440, now);
        osc.frequency.exponentialRampToValueAtTime(880, now + 0.08);
        gain.gain.setValueAtTime(0.15, now);
        gain.gain.linearRampToValueAtTime(0.01, now + 0.08);
        osc.start(now);
        osc.stop(now + 0.08);
      } else if (type === 'success') {
        osc.type = 'triangle';
        osc.frequency.setValueAtTime(523.25, now); // C5
        osc.frequency.setValueAtTime(659.25, now + 0.08); // E5
        osc.frequency.setValueAtTime(783.99, now + 0.16); // G5
        osc.frequency.setValueAtTime(1046.50, now + 0.24); // C6
        gain.gain.setValueAtTime(0.2, now);
        gain.gain.linearRampToValueAtTime(0.01, now + 0.35);
        osc.start(now);
        osc.stop(now + 0.35);
      } else if (type === 'toggle') {
        osc.type = 'sine';
        osc.frequency.setValueAtTime(600, now);
        osc.frequency.exponentialRampToValueAtTime(300, now + 0.06);
        gain.gain.setValueAtTime(0.1, now);
        gain.gain.linearRampToValueAtTime(0.01, now + 0.06);
        osc.start(now);
        osc.stop(now + 0.06);
      }
    } catch (e) {
      console.warn("Audio Context Error:", e);
    }
  }

  // Canvas QR Code Generator
  function drawQRCode(text, canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const width = 180;
    const height = 180;
    canvas.width = width;
    canvas.height = height;

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    const cells = 21;
    const cellSize = width / cells;

    let hash = 0;
    for (let i = 0; i < text.length; i++) {
      hash = (hash << 5) - hash + text.charCodeAt(i);
      hash |= 0;
    }

    ctx.fillStyle = '#08090c';

    function drawFinder(x, y) {
      ctx.fillStyle = '#08090c';
      ctx.fillRect(x * cellSize, y * cellSize, 7 * cellSize, 7 * cellSize);
      ctx.fillStyle = '#ffffff';
      ctx.fillRect((x + 1) * cellSize, (y + 1) * cellSize, 5 * cellSize, 5 * cellSize);
      ctx.fillStyle = '#00cc52';
      ctx.fillRect((x + 2) * cellSize, (y + 2) * cellSize, 3 * cellSize, 3 * cellSize);
    }

    drawFinder(0, 0);
    drawFinder(14, 0);
    drawFinder(0, 14);

    ctx.fillStyle = '#08090c';
    for (let r = 0; r < cells; r++) {
      for (let c = 0; c < cells; c++) {
        if ((r < 7 && c < 7) || (r < 7 && c >= 14) || (r >= 14 && c < 7)) continue;
        let pseudoBit = Math.sin(r * 12.9898 + c * 78.233 + hash) * 43758.5453;
        pseudoBit = pseudoBit - Math.floor(pseudoBit);
        if (pseudoBit > 0.48) {
          ctx.fillRect(c * cellSize, r * cellSize, cellSize, cellSize);
        }
      }
    }

    const logoSize = 34;
    const logoX = (width - logoSize) / 2;
    const logoY = (height - logoSize) / 2;
    ctx.fillStyle = '#00FF66';
    ctx.fillRect(logoX - 2, logoY - 2, logoSize + 4, logoSize + 4);
    ctx.fillStyle = '#07080c';
    ctx.fillRect(logoX, logoY, logoSize, logoSize);

    ctx.fillStyle = '#00FF66';
    ctx.font = 'bold 16px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('UPI', width / 2, height / 2);
  }

  // Dynamic Fee Calculation
  function calculateFee() {
    const matchCountSelect = document.getElementById('matchCount');
    
    if (!matchCountSelect) return;

    const ratePerMatch = 30; // Flat ₹30 per match
    const matchCount = parseInt(matchCountSelect.value, 10) || 1;
    const totalFee = ratePerMatch * matchCount;

    const rateDisplay = document.getElementById('ratePerMatchDisplay');
    const matchesDisplay = document.getElementById('matchCountDisplay');
    const totalDisplay = document.getElementById('totalFeeDisplay');
    const qrAmountDisplay = document.getElementById('qrAmountDisplay');
    const discountSavedTag = document.getElementById('discountSavedTag');

    if (rateDisplay) rateDisplay.textContent = `₹${ratePerMatch}`;
    if (matchesDisplay) matchesDisplay.textContent = `${matchCount} ${matchCount === 1 ? 'Match' : 'Matches'}`;
    if (totalDisplay) totalDisplay.textContent = `₹${totalFee}`;
    if (qrAmountDisplay) qrAmountDisplay.textContent = totalFee;

    if (discountSavedTag) {
      discountSavedTag.style.display = 'inline-block';
      discountSavedTag.textContent = `⚡ Entry Rate: ₹30 / match`;
    }

    const upiUri = `upi://pay?pa=mahethekiller@okhdfcbank&pn=Tekken%20Showdown&am=${totalFee}&cu=INR`;
    drawQRCode(upiUri, 'qrCanvas');
  }

  // View Switching (Tab Navigation)
  function initNavigation() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const views = document.querySelectorAll('.view-page');

    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetView = btn.getAttribute('data-tab');
        playSound('click');

        tabBtns.forEach(b => b.classList.remove('active'));
        views.forEach(v => v.classList.remove('active'));

        btn.classList.add('active');
        const activeView = document.getElementById(targetView);
        if (activeView) activeView.classList.add('active');
      });
    });
  }

  // Toast Notification System
  function showToast(message, iconClass = 'fa-solid fa-circle-check') {
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="${iconClass}"></i><span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  // Form Submission Handler via AJAX
  function initForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const matchSelect = document.getElementById('matchCount');
    if (matchSelect) {
      matchSelect.addEventListener('change', () => {
        playSound('click');
        calculateFee();
      });
    }

    calculateFee();

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const fullName = document.getElementById('fullName').value.trim();
      const department = document.getElementById('department').value.trim();
      const festiveGreenInput = document.querySelector('input[name="festive_green"]');
      const festiveGreenVal = festiveGreenInput ? festiveGreenInput.value : 0;
      const matches = parseInt(document.getElementById('matchCount').value, 10);
      const utr = document.getElementById('utrNumber').value.trim();

      if (!fullName || !department || !utr) {
        showToast('Please fill in all required fields!', 'fa-solid fa-triangle-exclamation');
        return;
      }

      if (utr.length < 6) {
        showToast('Please enter a valid Transaction / UTR ID!', 'fa-solid fa-circle-exclamation');
        return;
      }

      const submitBtn = document.getElementById('submitRegBtn');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Registering...';

      const formData = new FormData();
      formData.append('full_name', fullName);
      formData.append('department', department);
      formData.append('festive_green', festiveGreenVal);
      formData.append('matches', matches);
      formData.append('utr_number', utr);

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

      fetch('/tekken-showdown/register', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-bolt"></i> Join Queue / Register';

        if (data.success) {
          playSound('success');
          showToast(`🎮 Registered ${fullName} successfully! Total Fee: ₹${data.data.fee_paid}`);

          // Insert new row into table
          appendRowToTable(data.data);
          recalculateStatsFromDOM();

          form.reset();
          calculateFee();

          // Switch to Queue View tab
          const queueTabBtn = document.querySelector('[data-tab="queueView"]');
          if (queueTabBtn) queueTabBtn.click();
        } else {
          showToast(data.message || 'Validation error!', 'fa-solid fa-circle-exclamation');
        }
      })
      .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-bolt"></i> Join Queue / Register';
        showToast('Submission error. Please check connection.', 'fa-solid fa-circle-exclamation');
      });
    });
  }

  // Table Row Builder & DOM Helpers
  function appendRowToTable(item) {
    const tbody = document.getElementById('queueTableBody');
    if (!tbody) return;

    const emptyRow = tbody.querySelector('.empty-state')?.closest('tr');
    if (emptyRow) emptyRow.remove();

    const rowCount = tbody.querySelectorAll('tr').length + 1;
    const outfitBadge = item.festive_green
      ? `<span class="outfit-badge green"><i class="fa-solid fa-shirt"></i> Green (₹30)</span>`
      : `<span class="outfit-badge regular"><i class="fa-solid fa-user-ninja"></i> Regular (₹30)</span>`;

    let statusClass = 'in-queue';
    let statusIcon = 'fa-hourglass-half';
    if (item.status === 'playing' || item.status === 'Playing Now') {
      statusClass = 'playing-now';
      statusIcon = 'fa-fire';
    } else if (item.status === 'completed' || item.status === 'Completed') {
      statusClass = 'completed';
      statusIcon = 'fa-circle-check';
    }

    const hasActionCol = document.querySelector('.records-table th:last-child')?.textContent.includes('Action');
    const actionTdHTML = hasActionCol ? `
      <td>
        <button class="action-btn" title="Delete Entry" onclick="window.deletePlayer(${item.id})">
          <i class="fa-solid fa-trash-can"></i>
        </button>
      </td>
    ` : '';

    const tr = document.createElement('tr');
    tr.id = `row-${item.id}`;
    tr.setAttribute('data-status', item.status);
    tr.setAttribute('data-green', item.festive_green ? 'true' : 'false');
    tr.innerHTML = `
      <td><div class="queue-num">#${rowCount}</div></td>
      <td>
        <div class="player-name-cell">${escapeHtml(item.full_name || item.name)}</div>
        <small style="color: var(--text-muted); font-size: 0.75rem;">${item.time || 'Just now'} • ${item.matches || 1} match(es)</small>
      </td>
      <td><span class="dept-tag">${escapeHtml(item.department || item.dept)}</span></td>
      <td>${outfitBadge}</td>
      <td>
        <span class="utr-code" title="Click to copy UTR" onclick="window.copyUTR('${item.utr_number || item.utr}')">
          <i class="fa-regular fa-copy" style="margin-right: 4px;"></i>${escapeHtml(item.utr_number || item.utr)}
        </span>
      </td>
      <td>
        <span class="status-pill ${statusClass}" onclick="window.cycleStatus(${item.id})" id="status-pill-${item.id}">
          <i class="fa-solid ${statusIcon}"></i> ${item.status_label || item.status}
        </span>
      </td>
      ${actionTdHTML}
    `;

    tbody.prepend(tr);
  }

  // Global Actions (Status Toggle, Delete, Copy UTR)
  window.cycleStatus = function (id) {
    playSound('toggle');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch(`/tekken-showdown/status/${id}`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const pill = document.getElementById(`status-pill-${id}`);
        const row = document.getElementById(`row-${id}`);

        let statusClass = 'in-queue';
        let statusIcon = 'fa-hourglass-half';
        if (data.status === 'playing') {
          statusClass = 'playing-now';
          statusIcon = 'fa-fire';
        } else if (data.status === 'completed') {
          statusClass = 'completed';
          statusIcon = 'fa-circle-check';
        }

        if (pill) {
          pill.className = `status-pill ${statusClass}`;
          pill.innerHTML = `<i class="fa-solid ${statusIcon}"></i> ${data.status_label}`;
        }
        if (row) {
          row.setAttribute('data-status', data.status);
        }
        recalculateStatsFromDOM();
        showToast(`Status updated to: ${data.status_label}`);
      }
    });
  };

  window.deletePlayer = function (id) {
    if (!confirm('Are you sure you want to remove this player from the queue?')) return;
    playSound('click');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch(`/tekken-showdown/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const row = document.getElementById(`row-${id}`);
        if (row) row.remove();
        recalculateStatsFromDOM();
        showToast('Player entry removed', 'fa-solid fa-trash-can');
      }
    });
  };

  window.copyUTR = function (utr) {
    navigator.clipboard.writeText(utr).then(() => {
      playSound('click');
      showToast(`Copied UTR: ${utr}`, 'fa-regular fa-clipboard');
    });
  };

  function recalculateStatsFromDOM() {
    const rows = document.querySelectorAll('#queueTableBody tr:not(:has(.empty-state))');
    let totalPlayers = rows.length;
    let playingCount = 0;
    let waitingCount = 0;

    rows.forEach(r => {
      const st = r.getAttribute('data-status');

      if (st === 'playing' || st === 'Playing Now') playingCount++;
      else if (st === 'in_queue' || st === 'In Queue') waitingCount++;
    });

    const totalEl = document.getElementById('statTotalPlayers');
    const playEl = document.getElementById('statPlayingCount');
    const waitEl = document.getElementById('statWaitingCount');

    if (totalEl) totalEl.textContent = totalPlayers;
    if (playEl) playEl.textContent = playingCount;
    if (waitEl) waitEl.textContent = waitingCount;
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Table Filter & Search Controls
  function initTableControls() {
    const searchInput = document.getElementById('searchQueue');
    const statusFilter = document.getElementById('filterStatus');
    const outfitFilter = document.getElementById('filterOutfit');
    const copyUpiBtn = document.getElementById('copyUpiBtn');

    function applyFilters() {
      const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
      const statusVal = statusFilter ? statusFilter.value : 'all';
      const outfitVal = outfitFilter ? outfitFilter.value : 'all';

      const rows = document.querySelectorAll('#queueTableBody tr:not(:has(.empty-state))');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status');
        const isGreen = row.getAttribute('data-green') === 'true';

        const matchQuery = text.includes(query);
        const matchStatus = (statusVal === 'all' || status === statusVal || 
                            (statusVal === 'In Queue' && status === 'in_queue') ||
                            (statusVal === 'Playing Now' && status === 'playing') ||
                            (statusVal === 'Completed' && status === 'completed'));
        const matchOutfit = (outfitVal === 'all' || (outfitVal === 'green' && isGreen) || (outfitVal === 'regular' && !isGreen));

        row.style.display = (matchQuery && matchStatus && matchOutfit) ? '' : 'none';
      });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (outfitFilter) outfitFilter.addEventListener('change', applyFilters);

    if (copyUpiBtn) {
      copyUpiBtn.addEventListener('click', () => {
        navigator.clipboard.writeText('mahethekiller@okhdfcbank');
        playSound('click');
        showToast('Copied UPI ID: mahethekiller@okhdfcbank', 'fa-regular fa-clipboard');
      });
    }
  }

  // Audio Toggle Button
  function initAudioToggle() {
    const audioBtn = document.getElementById('audioToggleBtn');
    if (!audioBtn) return;

    audioBtn.addEventListener('click', () => {
      isMuted = !isMuted;
      audioBtn.innerHTML = isMuted ? '<i class="fa-solid fa-volume-xmark"></i>' : '<i class="fa-solid fa-volume-high"></i>';
      audioBtn.style.color = isMuted ? 'var(--neon-red)' : 'var(--neon-gold)';
      showToast(isMuted ? 'Arcade SFX Muted' : 'Arcade SFX Enabled', isMuted ? 'fa-solid fa-volume-xmark' : 'fa-solid fa-volume-high');
    });
  }

  // Initialize Application
  document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initForm();
    initTableControls();
    initAudioToggle();
    recalculateStatsFromDOM();
  });

})();
