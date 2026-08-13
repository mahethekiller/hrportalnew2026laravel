<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TEKKEN 7: TEEJ SPECIAL SHOWDOWN • Office Arcade Tournament</title>
  <meta name="description" content="Official arcade registration and live queue management portal for the office gaming event TEKKEN 7: TEEJ SPECIAL SHOWDOWN.">
  
  <!-- Local FontAwesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}">
  
  <!-- Local Custom Arcade Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/tekken.css') }}">
</head>
<body>

  <!-- Ambient Background Glow & Scanlines -->
  <div class="scanlines"></div>
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <!-- Main Application Wrapper -->
  <div id="app">

    <!-- Top Navigation Bar -->
    <nav class="navbar">
      <div class="brand">
        <div class="brand-icon">
          <i class="fa-solid fa-gamepad"></i>
        </div>
        <div>
          <div class="brand-title">TEKKEN 7 <span>SHOWDOWN</span></div>
          <div class="brand-badge">🏆 Teej Arcade Championship</div>
        </div>
      </div>

      <div class="nav-controls">
        <div class="nav-tabs">
          <button class="tab-btn active" data-tab="registrationView" id="tabRegBtn">
            <i class="fa-solid fa-pen-to-square"></i> Registration
          </button>
          <button class="tab-btn" data-tab="queueView" id="tabQueueBtn">
            <i class="fa-solid fa-list-ol"></i> Live Queue & Table
          </button>
        </div>

        <button class="audio-btn" id="audioToggleBtn" title="Toggle Arcade Sound Effects">
          <i class="fa-solid fa-volume-high"></i>
        </button>
      </div>
    </nav>

    <!-- Glowing Ticker / Celebrity Impostor Announcement Banner -->
    <div style="background: linear-gradient(90deg, rgba(255, 42, 84, 0.15), rgba(255, 215, 0, 0.15), rgba(255, 42, 84, 0.15)); border-bottom: 1px solid rgba(255, 42, 84, 0.4); padding: 10px 20px; overflow: hidden; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; color: #ffffff; z-index: 10; relative;">
      <span style="background: var(--neon-red); color: #fff; padding: 4px 10px; border-radius: 4px; font-family: var(--font-heading); font-size: 0.75rem; white-space: nowrap; font-weight: bold; box-shadow: 0 0 10px var(--neon-red-glow);">
        <i class="fa-solid fa-bullhorn"></i> EVENT ANNOUNCEMENT
      </span>
      <marquee behavior="scroll" direction="left" scrollamount="6" style="vertical-align: middle;">
        🚨 <strong>IMPORTANT EVENT UPDATE:</strong> Apparently, <strong>Samay Raina, Sourav Joshi & his wife, Bhai Jaan (Salman Khan), Rocky Bhai (Yash) & BeerBiceps</strong> are all fighting in our Tekken bracket tomorrow! 🗿 Quick reminder: <strong>IP & Device Logging is active.</strong> Stop trolling the portal or your real computer name will be posted right next to your fake celebrity crush! 🤡 ⚡ <strong>ENTRY FEE: ₹30/match</strong> ⚡
      </marquee>
    </div>

    <!-- ==========================================================================
         PAGE 1: REGISTRATION & PAYMENT FORM VIEW
         ========================================================================== -->
    <main id="registrationView" class="view-page active">
      
      <!-- Event Header Banner -->
      <header class="event-header">
        <div class="event-tag">
          <i class="fa-solid fa-bolt-lightning"></i> Office Esports Event • King of the Hill
        </div>
        <h1 class="main-title">TEKKEN 7: <span>SHOWDOWN</span></h1>
        <p class="subtitle">Winner Stays On • <strong>King of the Hill</strong></p>
      </header>

      <!-- Registration Form & Dynamic Calculator Grid -->
      <div class="form-grid">
        
        <!-- Registration Form Card -->
        <div class="card">
          <h2 class="card-title">
            <i class="fa-solid fa-user-plus"></i> Player Registration Form
          </h2>

          <form id="registrationForm">
            @csrf
            
            <!-- 1. Full Name -->
            <div class="form-group">
              <label class="form-label" for="fullName">Full Name <span class="req">*</span></label>
              <div class="input-wrapper">
                <input type="text" id="fullName" name="full_name" class="form-input" placeholder="e.g. Jin Kazama" required autocomplete="off">
                <i class="fa-solid fa-user input-icon"></i>
              </div>
            </div>

            <!-- 2. Department / Team Name -->
            <div class="form-group">
              <label class="form-label" for="department">Department / Team Name <span class="req">*</span></label>
              <div class="input-wrapper">
                <input type="text" id="department" name="department" class="form-input" placeholder="e.g. Engineering, Design, QA..." required autocomplete="off" list="deptSuggestions">
                <i class="fa-solid fa-building-user input-icon"></i>
              </div>
              <datalist id="deptSuggestions">
                <option value="Engineering"></option>
                <option value="Frontend Dev"></option>
                <option value="UI/UX Design"></option>
                <option value="Product Management"></option>
                <option value="QA & Testing"></option>
                <option value="Marketing & Sales"></option>
                <option value="HR & Operations"></option>
              </datalist>
            </div>

            <!-- Default Outfit Selection (No by default) -->
            <input type="hidden" name="festive_green" value="0">

            <!-- 4. Number of Matches / Retries -->
            <div class="form-group">
              <label class="form-label" for="matchCount">Number of Matches / Retries <span class="req">*</span></label>
              <div class="input-wrapper">
                <select id="matchCount" name="matches" class="form-select">
                  <option value="1" selected>1 Match (Standard Entry)</option>
                  <option value="2">2 Matches (Double Retry)</option>
                  <option value="3">3 Matches (Triple Entry)</option>
                  <option value="4">4 Matches (Fighter Bundle)</option>
                  <option value="5">5 Matches (Tournament Pass)</option>
                </select>
                <i class="fa-solid fa-gamepad input-icon"></i>
              </div>
            </div>

            <!-- UTR / Payment Transaction ID field -->
            <div class="form-group">
              <label class="form-label" for="utrNumber">Payment Transaction ID / UTR <span class="req">*</span></label>
              <div class="input-wrapper">
                <input type="text" id="utrNumber" name="utr_number" class="form-input" placeholder="Enter 12-digit UTR or Reference ID" required autocomplete="off">
                <i class="fa-solid fa-receipt input-icon"></i>
              </div>
              <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 6px;">
                Enter transaction reference after completing UPI payment via QR code.
              </small>
            </div>

            <!-- Primary Submit Button -->
            <button type="submit" class="btn-submit" id="submitRegBtn">
              <i class="fa-solid fa-bolt"></i> Join Queue / Register
            </button>

          </form>
        </div>

        <!-- Dynamic Fee Calculator & Embedded UPI Payment Section Card -->
        <div class="card">
          <h2 class="card-title">
            <i class="fa-solid fa-calculator"></i> Fee Breakdown & UPI Payment
          </h2>

          <!-- Dynamic Fee Summary Box -->
          <div class="fee-summary-card">
            <div class="summary-row">
              <span>Rate Per Match:</span>
              <strong id="ratePerMatchDisplay" style="color: var(--neon-gold);">₹30</strong>
            </div>
            <div class="summary-row">
              <span>Match Count:</span>
              <strong id="matchCountDisplay" style="color: var(--text-main);">1 Match</strong>
            </div>
            <div class="summary-row total">
              <span>Total Amount Due:</span>
              <span class="total-amount" id="totalFeeDisplay">₹30</span>
            </div>
          </div>

          <!-- Embedded UPI Payment QR Code Section -->
          <div class="qr-container">
            <div class="qr-box" style="width: 210px; height: 210px; padding: 6px;">
              <img src="{{ asset('assets/images/upi_qr.jpg') }}" alt="UPI Payment QR Code" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;">
            </div>

            <p class="qr-instructions">
              Scan UPI QR Code & Pay <strong id="qrAmountDisplay">30</strong> via GPay / PhonePe / Paytm
            </p>

            <div class="upi-id-pill">
              <span>UPI: <strong>mahethekiller@okhdfcbank</strong></span>
              <button type="button" class="copy-btn" id="copyUpiBtn" title="Copy UPI ID">
                <i class="fa-regular fa-copy"></i>
              </button>
            </div>

            <div class="app-icons">
              <span class="app-badge"><i class="fa-solid fa-mobile-screen"></i> GPay</span>
              <span class="app-badge"><i class="fa-solid fa-wallet"></i> PhonePe</span>
              <span class="app-badge"><i class="fa-solid fa-credit-card"></i> Paytm</span>
            </div>
          </div>

          <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; background: rgba(255, 255, 255, 0.03); padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);">
            <i class="fa-solid fa-circle-info" style="color: var(--neon-gold);"></i> Note: Please save your 12-digit UTR/Reference number from your UPI payment app and paste it into the registration form.
          </div>

        </div>

      </div>

    </main>

    <!-- ==========================================================================
         PAGE 2: LIVE QUEUE & PLAYER RECORDS TABLE VIEW
         ========================================================================== -->
    <main id="queueView" class="view-page">
      
      <!-- Metrics Dashboard Header -->
      <section class="stats-grid">
        
        <div class="stat-card">
          <div class="stat-icon green">
            <i class="fa-solid fa-users-viewfinder"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value" id="statTotalPlayers">{{ $stats['total_players'] }}</div>
            <div class="stat-label">Total Players</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon cyan">
            <i class="fa-solid fa-fire"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value" id="statPlayingCount">{{ $stats['playing'] }}</div>
            <div class="stat-label">Playing Now</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon red">
            <i class="fa-solid fa-hourglass-half"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value" id="statWaitingCount">{{ $stats['in_queue'] }}</div>
            <div class="stat-label">In Queue</div>
          </div>
        </div>

      </section>

      <!-- Search & Filter Controls Toolbar -->
      <div class="toolbar-card">
        <div class="search-box">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" id="searchQueue" class="search-input" placeholder="Search by player name or department...">
        </div>

        <div class="filter-group">
          <select id="filterStatus" class="filter-select">
            <option value="all">All Statuses</option>
            <option value="In Queue">In Queue</option>
            <option value="Playing Now">Playing Now</option>
            <option value="Completed">Completed</option>
          </select>

          <select id="filterOutfit" class="filter-select">
            <option value="all">All Outfits</option>
            <option value="green">Green Outfit (₹30)</option>
            <option value="regular">Regular Outfit (₹30)</option>
          </select>

          <a href="{{ route('tekken.export') }}" class="btn-secondary" id="exportCsvBtn" title="Export queue records to CSV">
            <i class="fa-solid fa-download"></i> Export CSV
          </a>
        </div>
      </div>

      <!-- Live Player Records Data Table (Public Read-Only Queue) -->
      <div class="table-responsive">
        <table class="records-table">
          <thead>
            <tr>
              <th>Queue #</th>
              <th>Player Name</th>
              <th>Department</th>
              <th>Festive Green</th>
              <th>Transaction ID / UTR</th>
              <th>Status (Click to toggle)</th>
            </tr>
          </thead>
          <tbody id="queueTableBody">
            @forelse($registrations as $index => $reg)
              @php
                $statusClass = match($reg->status) {
                    'playing' => 'playing-now',
                    'completed' => 'completed',
                    default => 'in-queue',
                };
                $statusIcon = match($reg->status) {
                    'playing' => 'fa-fire',
                    'completed' => 'fa-circle-check',
                    default => 'fa-hourglass-half',
                };
              @endphp
              <tr id="row-{{ $reg->id }}" data-status="{{ $reg->status }}" data-green="{{ $reg->festive_green ? 'true' : 'false' }}">
                <td><div class="queue-num">#{{ $index + 1 }}</div></td>
                <td>
                  <div class="player-name-cell">{{ $reg->full_name }}</div>
                  <small style="color: var(--text-muted); font-size: 0.75rem;">{{ $reg->created_at->format('H:i') }} &bull; {{ $reg->matches }} match(es)</small>
                </td>
                <td><span class="dept-tag">{{ $reg->department }}</span></td>
                <td>
                  @if($reg->festive_green)
                    <span class="outfit-badge green"><i class="fa-solid fa-shirt"></i> Green (₹30)</span>
                  @else
                    <span class="outfit-badge regular"><i class="fa-solid fa-user-ninja"></i> Regular (₹30)</span>
                  @endif
                </td>
                <td>
                  <span class="utr-code" title="Click to copy UTR" onclick="window.copyUTR('{{ $reg->utr_number }}')">
                    <i class="fa-regular fa-copy" style="margin-right: 4px;"></i>{{ $reg->utr_number }}
                  </span>
                </td>
                <td>
                  <span class="status-pill {{ $statusClass }}" onclick="window.cycleStatus({{ $reg->id }})" id="status-pill-{{ $reg->id }}">
                    <i class="fa-solid {{ $statusIcon }}"></i> {{ $reg->status_label }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty-state">
                    <i class="fa-solid fa-gamepad"></i>
                    <h3>No Players Found</h3>
                    <p>No registration records found in the tournament queue yet.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </main>

  </div>

  <!-- Local Custom Arcade JS -->
  <script src="{{ asset('assets/js/tekken.js') }}"></script>
</body>
</html>
