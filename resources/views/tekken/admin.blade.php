<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TEKKEN 7: SHOWDOWN • Tournament Admin Control Panel</title>
  <meta name="description" content="Admin and organizer control panel for TEKKEN 7 Showdown. Manage queue, update status, and remove entries.">
  
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
        <div class="brand-icon" style="background: linear-gradient(135deg, var(--neon-red), var(--neon-gold));">
          <i class="fa-solid fa-user-shield"></i>
        </div>
        <div>
          <div class="brand-title">TEKKEN 7 <span>ADMIN PANEL</span></div>
          <div class="brand-badge">⚡ Tournament Organizer Control</div>
        </div>
      </div>

      <div class="nav-controls">
        <a href="{{ route('tekken.index') }}" class="btn-secondary" style="font-size: 0.9rem;">
          <i class="fa-solid fa-house"></i> Public View
        </a>

        <form action="{{ route('tekken.admin.logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="btn-secondary" style="font-size: 0.9rem; color: var(--neon-red); border-color: rgba(255, 42, 84, 0.4);">
            <i class="fa-solid fa-lock"></i> Lock Panel
          </button>
        </form>

        <button class="audio-btn" id="audioToggleBtn" title="Toggle Arcade Sound Effects">
          <i class="fa-solid fa-volume-high"></i>
        </button>
      </div>
    </nav>

    <!-- ADMIN MANAGEMENT VIEW -->
    <main id="queueView" class="view-page active">
      
      <!-- Event Header Banner -->
      <header class="event-header" style="border-top-color: var(--neon-red);">
        <div class="event-tag" style="color: var(--neon-red); border-color: rgba(255, 42, 84, 0.4); background: rgba(255, 42, 84, 0.1);">
          <i class="fa-solid fa-shield-halved"></i> Tournament Control Center
        </div>
        <h1 class="main-title">TEKKEN 7: <span style="color: var(--neon-red);">ADMIN QUEUE MANAGER</span></h1>
        <p class="subtitle">Live Bracket Control &bull; <strong>Delete & Manage Entries</strong></p>
      </header>

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
          <input type="text" id="searchQueue" class="search-input" placeholder="Search player name, UTR, or department...">
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

      <!-- Live Player Records Data Table (WITH DELETE PERMISSION & ANTI-FRAUD TRACKING) -->
      <div class="table-responsive">
        <table class="records-table">
          <thead>
            <tr>
              <th>Queue #</th>
              <th>Player Name</th>
              <th>Department</th>
              <th>Festive Green</th>
              <th>Transaction ID / UTR</th>
              <th>Computer / Device Info</th>
              <th>Status (Click to toggle)</th>
              <th style="color: var(--neon-red);">Action</th>
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
                $hasIpDuplicate = isset($ipCounts[$reg->ip_address]) && $ipCounts[$reg->ip_address] > 1;
                $hasHostDuplicate = isset($hostnameCounts[$reg->device_name]) && $hostnameCounts[$reg->device_name] > 1;
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
                  <div style="font-family: monospace; font-size: 0.82rem; color: var(--neon-gold); font-weight: bold;">
                    <i class="fa-solid fa-desktop" style="margin-right: 4px;"></i>{{ $reg->device_name ?: 'N/A' }}
                  </div>
                  <small style="color: var(--text-muted); font-size: 0.72rem; display: block;">
                    IP: {{ $reg->ip_address ?: '127.0.0.1' }} &bull; MAC: {{ $reg->mac_address ?: 'LOCAL' }}
                  </small>
                  @if($hasIpDuplicate || $hasHostDuplicate)
                    <div style="margin-top: 3px;">
                      <span style="background: rgba(255, 42, 84, 0.2); color: #ff6685; border: 1px solid var(--neon-red); font-size: 0.68rem; padding: 2px 6px; border-radius: 4px; font-weight: bold; display: inline-block;">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ max($ipCounts[$reg->ip_address] ?? 1, $hostnameCounts[$reg->device_name] ?? 1) }} Entries from Device
                      </span>
                    </div>
                  @endif
                </td>
                <td>
                  <span class="status-pill {{ $statusClass }}" onclick="window.cycleStatus({{ $reg->id }})" id="status-pill-{{ $reg->id }}">
                    <i class="fa-solid {{ $statusIcon }}"></i> {{ $reg->status_label }}
                  </span>
                </td>
                <td>
                  <button class="action-btn" title="Delete Entry" onclick="window.deletePlayer({{ $reg->id }})">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="empty-state">
                    <i class="fa-solid fa-gamepad"></i>
                    <h3>No Players Found</h3>
                    <p>No registration records found in the tournament queue.</p>
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
