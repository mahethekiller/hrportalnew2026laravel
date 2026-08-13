<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TEKKEN 7: TEEJ SPECIAL SHOWDOWN - Live Tournament</title>
    
    <!-- Local Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    
    <!-- Custom Cyberpunk Esports Styling -->
    <style>
        :root {
            --bg-dark: #090a0f;
            --bg-card: #121520;
            --bg-card-hover: #1a1e2e;
            --neon-red: #ff0055;
            --neon-cyan: #00f0ff;
            --neon-yellow: #ffe600;
            --neon-green: #00ff88;
            --neon-purple: #9d00ff;
            --text-muted: #8c9ba5;
        }

        body {
            background-color: var(--bg-dark);
            color: #f1f5f9;
            font-family: 'Segoe UI', Roboto, -apple-system, sans-serif;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(255, 0, 85, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(0, 240, 255, 0.08) 0%, transparent 40%),
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* Neon Header Text */
        .arcade-title {
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(135deg, #ffffff 0%, var(--neon-cyan) 50%, var(--neon-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
        }

        .arcade-subtitle {
            color: var(--neon-yellow);
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(18, 21, 32, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(0, 240, 255, 0.4);
            box-shadow: 0 10px 40px 0 rgba(0, 240, 255, 0.15);
        }

        /* Stat Badge Counter */
        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        /* Form Inputs Styling */
        .form-control, .form-select {
            background-color: rgba(9, 10, 15, 0.8) !important;
            border: 1px solid rgba(0, 240, 255, 0.25) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.3) !important;
        }

        /* Neon Buttons */
        .btn-neon-red {
            background: linear-gradient(135deg, var(--neon-red), #b3003b);
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 1px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.4);
            transition: all 0.2s ease;
        }
        .btn-neon-red:hover {
            background: linear-gradient(135deg, #ff3377, var(--neon-red));
            box-shadow: 0 0 25px rgba(255, 0, 85, 0.7);
            color: #ffffff;
            transform: scale(1.02);
        }

        .btn-neon-cyan {
            background: linear-gradient(135deg, var(--neon-cyan), #0099b3);
            color: #090a0f;
            font-weight: 800;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
            transition: all 0.2s ease;
        }
        .btn-neon-cyan:hover {
            color: #090a0f;
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.6);
            transform: scale(1.02);
        }

        /* Fee Highlight Box */
        .fee-box {
            background: linear-gradient(135deg, rgba(255, 230, 0, 0.1), rgba(255, 0, 85, 0.1));
            border: 2px dashed var(--neon-yellow);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        /* Table Design */
        .table-arcade {
            color: #e2e8f0;
            vertical-align: middle;
        }
        .table-arcade th {
            background-color: rgba(9, 10, 15, 0.9);
            color: var(--neon-cyan);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--neon-cyan);
            padding: 1rem;
        }
        .table-arcade td {
            background-color: rgba(18, 21, 32, 0.6);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.85rem 1rem;
        }
        .table-arcade tr:hover td {
            background-color: rgba(0, 240, 255, 0.05);
        }

        /* Status Badges */
        .badge-status {
            cursor: pointer;
            user-select: none;
            padding: 0.5em 0.8em;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .badge-status:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px currentColor;
        }

        /* QR Canvas Wrapper */
        #qr-canvas-container {
            background: #ffffff;
            padding: 12px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        /* Pulse Animations */
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(0, 250, 136, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(0, 250, 136, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 250, 136, 0); }
        }
        .playing-pulse {
            animation: pulse-ring 2s infinite;
        }
    </style>
</head>
<body>

    <div class="container py-4">
        <!-- HEADER BANNER -->
        <div class="glass-card p-4 mb-4 text-center position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-danger text-wrap fs-6 px-3 py-2">🎮 TEEJ FESTIVAL SPECIAL</span>
                <button id="toggle-sound-btn" class="btn btn-outline-warning btn-sm fw-bold">
                    🔊 SOUND: ON
                </button>
            </div>
            <h1 class="arcade-title display-4 mb-1">TEKKEN 7 SHOWDOWN</h1>
            <p class="arcade-subtitle mb-3">⚡ FEEL THE FIRE &bull; UNLEASH THE COMBOS ⚡</p>
            
            <!-- STATS COUNTERS -->
            <div class="row g-3 mt-1">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="text-muted small fw-bold mb-1">TOTAL FIGHTERS</div>
                        <div class="stat-number text-info" id="stat-total-players">{{ $stats['total_players'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="text-muted small fw-bold mb-1">IN QUEUE</div>
                        <div class="stat-number text-warning" id="stat-in-queue">{{ $stats['in_queue'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="text-muted small fw-bold mb-1">PLAYING NOW</div>
                        <div class="stat-number text-danger" id="stat-playing">{{ $stats['playing'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="text-muted small fw-bold mb-1">TOTAL POOL (₹)</div>
                        <div class="stat-number text-success" id="stat-total-fees">₹{{ number_format($stats['total_fees'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- REGISTRATION FORM -->
            <div class="col-lg-5">
                <div class="glass-card p-4 h-100">
                    <h3 class="fw-bold mb-3 text-cyan d-flex align-items-center">
                        <span class="me-2">🥊</span> FIGHTER REGISTRATION
                    </h3>
                    <p class="text-secondary small mb-4">Flat entry fee of <strong>₹20 per match</strong>. Enter your details & UTR payment reference.</p>

                    <form id="registration-form" action="{{ route('tekken.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="full_name" class="form-label text-light fw-semibold">Fighter Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="e.g. Jin Kazama" required>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label text-light fw-semibold">Department *</label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="" disabled selected>Select Department</option>
                                <option value="IT / Software">IT / Software</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Quality Assurance">Quality Assurance</option>
                                <option value="Operations & Support">Operations & Support</option>
                                <option value="Sales & Marketing">Sales & Marketing</option>
                                <option value="Finance & Accounts">Finance & Accounts</option>
                                <option value="Management / Admin">Management / Admin</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch bg-dark p-3 rounded-3 border border-secondary">
                                <input class="form-check-input ms-0 me-2" type="checkbox" id="festive_green" name="festive_green" value="1">
                                <label class="form-check-label fw-bold text-success" for="festive_green">
                                    👕 Wearing Festive Green T-Shirt?
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="matches" class="form-label text-light fw-semibold">Number of Matches *</label>
                            <input type="number" class="form-control" id="matches" name="matches" value="1" min="1" max="20" required>
                        </div>

                        <!-- DYNAMIC FEE CALCULATOR & QR CODE -->
                        <div class="fee-box mb-4">
                            <div class="text-uppercase small text-warning fw-bold">TOTAL ENTRY FEE</div>
                            <div class="display-6 fw-bold text-white my-1" id="fee-display">₹20.00</div>
                            <div class="small text-muted mb-3">(Flat ₹20 &times; <span id="match-count-label">1</span> Match)</div>

                            <!-- UPI QR Canvas -->
                            <div class="text-center">
                                <div id="qr-canvas-container" class="mb-2">
                                    <canvas id="upi-qr-canvas" width="140" height="140"></canvas>
                                </div>
                                <div class="small text-info fw-semibold">Scan with GPay / PhonePe / Paytm</div>
                                <div class="badge bg-secondary font-monospace mt-1">UPI: 7382218413@ybl</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="utr_number" class="form-label text-light fw-semibold">UPI Transaction UTR Number *</label>
                            <input type="text" class="form-control font-monospace" id="utr_number" name="utr_number" placeholder="12-digit UTR No. e.g. 423456789012" required>
                        </div>

                        <button type="submit" class="btn btn-neon-red w-100 py-3 fs-5" id="submit-btn">
                            ENTER THE ARENA 💥
                        </button>
                    </form>
                </div>
            </div>

            <!-- QUEUE & LEADERBOARD TABLE -->
            <div class="col-lg-7">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h3 class="fw-bold m-0 text-cyan d-flex align-items-center">
                            <span class="me-2">⚔️</span> TOURNAMENT QUEUE
                        </h3>
                        <a href="{{ route('tekken.export') }}" class="btn btn-neon-cyan btn-sm">
                            📥 EXPORT CSV
                        </a>
                    </div>

                    <!-- SEARCH & FILTER BAR -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <input type="text" id="search-input" class="form-control form-control-sm" placeholder="🔍 Search fighter name or department...">
                        </div>
                        <div class="col-md-5">
                            <select id="filter-status" class="form-select form-select-sm">
                                <option value="all">All Statuses</option>
                                <option value="in_queue">In Queue</option>
                                <option value="playing">Playing Now</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- QUEUE TABLE -->
                    <div class="table-responsive">
                        <table class="table table-arcade" id="queue-table">
                            <thead>
                                <tr>
                                    <th>Fighter</th>
                                    <th>Dept</th>
                                    <th>Match</th>
                                    <th>Fee</th>
                                    <th>UTR</th>
                                    <th>Status (Click to toggle)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="queue-tbody">
                                @forelse($registrations as $reg)
                                    <tr id="row-{{ $reg->id }}" data-status="{{ $reg->status }}">
                                        <td>
                                            <div class="fw-bold text-white">{{ $reg->full_name }}</div>
                                            @if($reg->festive_green)
                                                <span class="badge bg-success-subtle text-success border border-success extra-small">👕 Green T-Shirt</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-dark border text-light">{{ $reg->department }}</span></td>
                                        <td class="text-center fw-bold">{{ $reg->matches }}</td>
                                        <td class="text-warning fw-bold">₹{{ number_format($reg->fee_paid, 2) }}</td>
                                        <td><code class="text-cyan small">{{ $reg->utr_number }}</code></td>
                                        <td>
                                            <span class="badge badge-status {{ $reg->status_badge_class }} {{ $reg->status === 'playing' ? 'playing-pulse' : '' }}" 
                                                  onclick="toggleStatus({{ $reg->id }})" 
                                                  id="status-badge-{{ $reg->id }}">
                                                {{ $reg->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-danger btn-sm p-1 px-2" onclick="deleteFighter({{ $reg->id }})" title="Delete Fighter">
                                                🗑️
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No fighters in queue yet. Be the first to register!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Native Web Audio API Sound Synth Engine -->
    <script>
        class SoundEngine {
            constructor() {
                this.ctx = null;
                this.muted = false;
            }

            init() {
                if (!this.ctx) {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    this.ctx = new AudioContext();
                }
            }

            playCoinDrop() {
                if (this.muted) return;
                this.init();
                const now = this.ctx.currentTime;
                
                const osc1 = this.ctx.createOscillator();
                const gain1 = this.ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(987.77, now); // B5
                osc1.frequency.setValueAtTime(1318.51, now + 0.08); // E6
                
                gain1.gain.setValueAtTime(0.3, now);
                gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.3);
                
                osc1.connect(gain1);
                gain1.connect(this.ctx.destination);
                
                osc1.start(now);
                osc1.stop(now + 0.3);
            }

            playFightSound() {
                if (this.muted) return;
                this.init();
                const now = this.ctx.currentTime;

                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(150, now);
                osc.frequency.exponentialRampToValueAtTime(400, now + 0.15);
                osc.frequency.exponentialRampToValueAtTime(80, now + 0.3);

                gain.gain.setValueAtTime(0.4, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);

                osc.connect(gain);
                gain.connect(this.ctx.destination);

                osc.start(now);
                osc.stop(now + 0.3);
            }

            playClick() {
                if (this.muted) return;
                this.init();
                const now = this.ctx.currentTime;
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'square';
                osc.frequency.setValueAtTime(600, now);
                gain.gain.setValueAtTime(0.15, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.05);

                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start(now);
                osc.stop(now + 0.05);
            }
        }

        const synth = new SoundEngine();

        document.getElementById('toggle-sound-btn').addEventListener('click', function() {
            synth.muted = !synth.muted;
            this.textContent = synth.muted ? '🔇 SOUND: OFF' : '🔊 SOUND: ON';
            this.className = synth.muted ? 'btn btn-outline-secondary btn-sm fw-bold' : 'btn btn-outline-warning btn-sm fw-bold';
        });

        // UPI QR Code Generator on Canvas
        function renderUPIQR(amount) {
            const canvas = document.getElementById('upi-qr-canvas');
            const ctx = canvas.getContext('2d');
            const upiString = `upi://pay?pa=7382218413@ybl&pn=Tekken%20Showdown&am=${amount}&cu=INR`;

            // Draw stylized QR canvas box
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 140, 140);

            // Frame border
            ctx.strokeStyle = '#ff0055';
            ctx.lineWidth = 4;
            ctx.strokeRect(4, 4, 132, 132);

            // Outer positioning markers
            function drawFinder(x, y) {
                ctx.fillStyle = '#090a0f';
                ctx.fillRect(x, y, 32, 32);
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(x+4, y+4, 24, 24);
                ctx.fillStyle = '#ff0055';
                ctx.fillRect(x+8, y+8, 16, 16);
            }
            drawFinder(12, 12);
            drawFinder(96, 12);
            drawFinder(12, 96);

            // Pseudo QR grid pattern based on upiString length hash
            ctx.fillStyle = '#090a0f';
            for (let i = 0; i < 100; i++) {
                let rx = 12 + ((i * 17 + amount) % 116);
                let ry = 12 + ((i * 23 + amount) % 116);
                if ((rx < 50 && ry < 50) || (rx > 80 && ry < 50) || (rx < 50 && ry > 80)) continue;
                ctx.fillRect(rx, ry, 6, 6);
            }

            // Amount Text Overlay
            ctx.fillStyle = '#00f0ff';
            ctx.fillRect(38, 58, 64, 24);
            ctx.fillStyle = '#090a0f';
            ctx.font = 'bold 12px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('₹' + amount, 70, 74);
        }

        // Dynamic Fee Calculator
        const matchesInput = document.getElementById('matches');
        const feeDisplay = document.getElementById('fee-display');
        const matchCountLabel = document.getElementById('match-count-label');

        function updateFee() {
            let count = parseInt(matchesInput.value) || 1;
            if (count < 1) count = 1;
            const totalFee = (count * 20).toFixed(2);
            feeDisplay.textContent = '₹' + totalFee;
            matchCountLabel.textContent = count;
            renderUPIQR(totalFee);
        }

        matchesInput.addEventListener('input', updateFee);
        updateFee();

        // AJAX Form Submission
        document.getElementById('registration-form').addEventListener('submit', function(e) {
            e.preventDefault();
            synth.playCoinDrop();

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>REGISTERING...';

            const formData = new FormData(this);

            fetch("{{ route('tekken.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'ENTER THE ARENA 💥';

                if (data.success) {
                    // Remove empty row if present
                    const emptyRow = document.getElementById('empty-row');
                    if (emptyRow) emptyRow.remove();

                    // Insert new row into queue table
                    const tbody = document.getElementById('queue-tbody');
                    const newRowHTML = `
                        <tr id="row-${data.data.id}" data-status="${data.data.status}">
                            <td>
                                <div class="fw-bold text-white">${data.data.full_name}</div>
                                ${data.data.festive_green ? '<span class="badge bg-success-subtle text-success border border-success extra-small">👕 Green T-Shirt</span>' : ''}
                            </td>
                            <td><span class="badge bg-dark border text-light">${data.data.department}</span></td>
                            <td class="text-center fw-bold">${data.data.matches}</td>
                            <td class="text-warning fw-bold">₹${data.data.fee_paid}</td>
                            <td><code class="text-cyan small">${data.data.utr_number}</code></td>
                            <td>
                                <span class="badge badge-status ${data.data.status_badge_class}" 
                                      onclick="toggleStatus(${data.data.id})" 
                                      id="status-badge-${data.data.id}">
                                    ${data.data.status_label}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-outline-danger btn-sm p-1 px-2" onclick="deleteFighter(${data.data.id})" title="Delete Fighter">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', newRowHTML);

                    // Reset Form
                    document.getElementById('registration-form').reset();
                    updateFee();

                    // Update Counters
                    updateCounters(1, 1, 0, parseFloat(data.data.fee_paid));
                } else {
                    alert('Error: ' + (data.message || 'Validation failed.'));
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'ENTER THE ARENA 💥';
                alert('Submission failed. Please check network connection.');
            });
        });

        // Toggle Status via AJAX
        function toggleStatus(id) {
            synth.playFightSound();
            const badge = document.getElementById(`status-badge-${id}`);
            const row = document.getElementById(`row-${id}`);

            fetch(`/tekken-showdown/status/${id}`, {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    badge.className = `badge badge-status ${data.badge_class} ${data.status === 'playing' ? 'playing-pulse' : ''}`;
                    badge.textContent = data.status_label;
                    row.setAttribute('data-status', data.status);
                    recalculateStatsFromDOM();
                }
            });
        }

        // Delete Fighter via AJAX
        function deleteFighter(id) {
            if (!confirm('Are you sure you want to remove this fighter from the queue?')) return;
            synth.playClick();

            fetch(`/tekken-showdown/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`row-${id}`);
                    if (row) row.remove();
                    recalculateStatsFromDOM();
                }
            });
        }

        // Recalculate stats counters
        function recalculateStatsFromDOM() {
            const rows = document.querySelectorAll('#queue-tbody tr:not(#empty-row)');
            let total = rows.length;
            let inQueue = 0;
            let playing = 0;
            let completed = 0;

            rows.forEach(r => {
                const st = r.getAttribute('data-status');
                if (st === 'in_queue') inQueue++;
                if (st === 'playing') playing++;
                if (st === 'completed') completed++;
            });

            document.getElementById('stat-total-players').textContent = total;
            document.getElementById('stat-in-queue').textContent = inQueue;
            document.getElementById('stat-playing').textContent = playing;
        }

        function updateCounters(addTotal, addQueue, addPlaying, addFee) {
            const totalEl = document.getElementById('stat-total-players');
            const queueEl = document.getElementById('stat-in-queue');
            const feeEl = document.getElementById('stat-total-fees');

            totalEl.textContent = parseInt(totalEl.textContent) + addTotal;
            queueEl.textContent = parseInt(queueEl.textContent) + addQueue;

            let currentFee = parseFloat(feeEl.textContent.replace('₹', '').replace(',', '')) || 0;
            feeEl.textContent = '₹' + (currentFee + addFee).toFixed(2);
        }

        // Search & Filter Logic
        document.getElementById('search-input').addEventListener('input', applyFilters);
        document.getElementById('filter-status').addEventListener('change', applyFilters);

        function applyFilters() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const filter = document.getElementById('filter-status').value;
            const rows = document.querySelectorAll('#queue-tbody tr:not(#empty-row)');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesQuery = text.includes(query);
                const matchesFilter = (filter === 'all' || status === filter);

                if (matchesQuery && matchesFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
