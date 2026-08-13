<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TEKKEN 7: SHOWDOWN • Admin Security Verification</title>
  
  <!-- Local FontAwesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}">
  
  <!-- Local Custom Arcade Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/tekken.css') }}">

  <style>
    .login-container {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .pin-card {
      max-width: 440px;
      width: 100%;
      background: var(--bg-card);
      border: 1.5px solid var(--neon-red);
      border-radius: var(--radius-lg);
      padding: 36px 28px;
      box-shadow: 0 0 35px rgba(255, 42, 84, 0.25);
      text-align: center;
    }
    .pin-input {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      letter-spacing: 8px;
      text-align: center;
      color: var(--neon-gold) !important;
      border-color: rgba(255, 42, 84, 0.4) !important;
    }
    .pin-input:focus {
      border-color: var(--neon-red) !important;
      box-shadow: 0 0 20px var(--neon-red-glow) !important;
    }
    .btn-unlock {
      width: 100%;
      background: linear-gradient(135deg, var(--neon-red), #b3003b);
      color: #ffffff;
      font-family: var(--font-heading);
      font-size: 1.1rem;
      font-weight: 800;
      padding: 14px;
      border: none;
      border-radius: var(--radius-md);
      cursor: pointer;
      box-shadow: 0 0 20px var(--neon-red-glow);
      transition: var(--transition);
      letter-spacing: 1px;
    }
    .btn-unlock:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 30px rgba(255, 42, 84, 0.6);
    }
  </style>
</head>
<body>

  <!-- Ambient Background Glow & Scanlines -->
  <div class="scanlines"></div>
  <div class="ambient-glow-1" style="background: radial-gradient(circle, var(--neon-red-glow) 0%, transparent 70%);"></div>

  <!-- Main Application Wrapper -->
  <div id="app">

    <!-- Top Navigation Bar -->
    <nav class="navbar">
      <div class="brand">
        <div class="brand-icon" style="background: linear-gradient(135deg, var(--neon-red), var(--neon-gold));">
          <i class="fa-solid fa-lock"></i>
        </div>
        <div>
          <div class="brand-title">TEKKEN 7 <span>SECURITY</span></div>
          <div class="brand-badge">Restricted Access Portal</div>
        </div>
      </div>

      <div class="nav-controls">
        <a href="{{ route('tekken.index') }}" class="btn-secondary" style="font-size: 0.9rem;">
          <i class="fa-solid fa-house"></i> Public View
        </a>
      </div>
    </nav>

    <!-- PIN UNLOCK CARD -->
    <div class="login-container">
      <div class="pin-card">
        <div style="font-size: 3rem; color: var(--neon-red); margin-bottom: 12px;">
          <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2 style="font-family: var(--font-heading); color: #ffffff; font-size: 1.5rem; margin-bottom: 6px;">
          ADMIN VERIFICATION
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
          Enter 6-digit security code to unlock Admin Panel.
        </p>

        @if(session('error'))
          <div style="background: rgba(255, 42, 84, 0.15); border: 1px solid var(--neon-red); color: #ff8099; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; font-weight: 600;">
            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
          </div>
        @endif

        @if(session('success'))
          <div style="background: rgba(0, 255, 102, 0.15); border: 1px solid var(--neon-green); color: var(--neon-green); padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; font-weight: 600;">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('tekken.admin.login') }}" method="POST">
          @csrf
          <div class="form-group mb-4">
            <input type="password" name="pin" class="form-input pin-input" maxlength="6" placeholder="******" required autofocus autocomplete="off">
          </div>

          <button type="submit" class="btn-unlock mb-3">
            UNLOCK ADMIN PANEL <i class="fa-solid fa-key ms-1"></i>
          </button>
        </form>
      </div>
    </div>

  </div>

</body>
</html>
