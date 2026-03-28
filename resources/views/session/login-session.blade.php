@extends('layouts.user_type.guest')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  /* ── RESET ── */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .lp-root {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Plus Jakarta Sans', sans-serif;
    position: relative;
    overflow: hidden;
    /* Background sama dengan panel kanan */
    background: linear-gradient(140deg, #eef2ff 0%, #f0f4ff 40%, #f5f0ff 100%);
  }

  /* ═══════════════════════════════
     CENTER — Form Card
  ═══════════════════════════════ */
  .lp-left {
    width: 100%;
    max-width: 480px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 52px 48px;
    position: relative;
    z-index: 10;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(79,109,255,.13), 0 4px 16px rgba(79,109,255,.07);
    border: 1px solid rgba(255,255,255,.9);
    margin: 32px 16px;
  }

  /* Logo */
  .lp-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 48px;
  }

  .lp-logo-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f6dff, #845ef7);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 18px rgba(79,109,255,.35);
  }

  .lp-logo-icon svg { width: 20px; height: 20px; fill: #fff; }

  .lp-logo-name {
    font-size: 15px;
    font-weight: 800;
    color: #1e293b;
    letter-spacing: -.3px;
  }

  .lp-logo-name small {
    display: block;
    font-size: 10px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #94a3b8;
    margin-top: 1px;
  }

  /* Heading */
  .lp-heading {
    margin-bottom: 36px;
  }

  .lp-heading h1 {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    letter-spacing: -.5px;
    margin-bottom: 8px;
  }

  .lp-heading h1 .accent {
    background: linear-gradient(90deg, #4f6dff, #845ef7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .lp-heading p {
    font-size: 13.5px;
    color: #64748b;
    line-height: 1.6;
  }

  /* Form fields */
  .lp-field { margin-bottom: 18px; }

  .lp-label {
    display: block;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: #475569;
    margin-bottom: 7px;
  }

  .lp-input-wrap { position: relative; }

  .lp-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    display: flex;
    pointer-events: none;
    transition: color .2s;
  }

  .lp-input-icon svg { width: 16px; height: 16px; }

  .lp-input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #f8faff;
    font-size: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    outline: none;
    transition: border-color .22s, box-shadow .22s, background .22s;
  }

  .lp-input::placeholder { color: #cbd5e1; }

  .lp-input:focus {
    border-color: #4f6dff;
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(79,109,255,.12);
  }

  .lp-input-wrap:focus-within .lp-input-icon { color: #4f6dff; }

  .lp-error {
    font-size: 11.5px;
    color: #ef4444;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  /* Remember row */
  .lp-remember {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 26px;
  }

  .lp-remember input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #4f6dff;
    cursor: pointer;
    border-radius: 4px;
  }

  .lp-remember label {
    font-size: 13px;
    color: #64748b;
    cursor: pointer;
  }

  /* Submit button */
  .lp-btn {
    width: 100%;
    padding: 13.5px;
    background: linear-gradient(135deg, #4f6dff 0%, #845ef7 100%);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    letter-spacing: .3px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(79,109,255,.3);
    transition: box-shadow .25s, transform .15s;
  }

  .lp-btn:hover {
    box-shadow: 0 14px 32px rgba(79,109,255,.42);
    transform: translateY(-1px);
  }

  .lp-btn:active { transform: translateY(0); }

  .lp-btn-shine {
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
    transform: skewX(-20deg);
    transition: left .6s ease;
    pointer-events: none;
  }

  .lp-btn:hover .lp-btn-shine { left: 140%; }

  /* Bottom links */
  .lp-links {
    margin-top: 28px;
    text-align: center;
  }

  .lp-links p {
    font-size: 12.5px;
    color: #94a3b8;
    margin-bottom: 6px;
  }

  .lp-links a {
    color: #4f6dff;
    font-weight: 600;
    text-decoration: none;
    transition: color .2s;
  }

  .lp-links a:hover { color: #845ef7; }

  /* Security badge */
  .lp-secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 100px;
    padding: 5px 12px;
    font-size: 11px;
    font-weight: 600;
    color: #16a34a;
  }

  .lp-secure-badge svg { width: 12px; height: 12px; fill: #16a34a; }

  /* ═══════════════════════════════
     RIGHT — Visual Panel
  ═══════════════════════════════ */
  .lp-right {
    flex: 1;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(140deg, #eef2ff 0%, #f0f4ff 40%, #f5f0ff 100%);
  }

  /* Decorative blobs */
  .lp-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(64px);
    pointer-events: none;
  }

  .lp-blob-1 {
    width: 420px; height: 420px;
    background: rgba(79,109,255,.12);
    top: -80px; right: -80px;
  }

  .lp-blob-2 {
    width: 280px; height: 280px;
    background: rgba(132,94,247,.1);
    bottom: -60px; left: 10%;
    animation: blobFloat 10s ease-in-out infinite;
  }

  .lp-blob-3 {
    width: 180px; height: 180px;
    background: rgba(14,165,233,.08);
    top: 40%; left: 60%;
    animation: blobFloat 14s ease-in-out infinite reverse;
  }

  @keyframes blobFloat {
    0%,100% { transform: translateY(0) scale(1); }
    50%      { transform: translateY(-24px) scale(1.05); }
  }

  /* Dot pattern */
  .lp-dots {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, #c7d2fe 1.2px, transparent 1.2px);
    background-size: 36px 36px;
    opacity: .5;
  }

  /* Right content */
  .lp-right-content {
    position: relative;
    z-index: 5;
    text-align: center;
    padding: 40px 48px;
    max-width: 500px;
  }

  .lp-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(79,109,255,.1);
    border: 1px solid rgba(79,109,255,.22);
    border-radius: 100px;
    padding: 6px 14px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: #4f6dff;
    margin-bottom: 28px;
  }

  .lp-pill-dot {
    width: 7px; height: 7px;
    background: #22c55e;
    border-radius: 50%;
    animation: livePulse 1.8s ease-in-out infinite;
  }

  @keyframes livePulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
    50%      { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
  }

  .lp-right-title {
    font-size: 40px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
    letter-spacing: -1.5px;
    margin-bottom: 14px;
  }

  .lp-right-title .grad {
    background: linear-gradient(90deg, #4f6dff, #845ef7, #0ea5e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .lp-right-sub {
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 44px;
  }

  /* Feature cards */
  .lp-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    text-align: left;
  }

  .lp-feat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 18px;
    transition: border-color .25s, box-shadow .25s, transform .25s;
    box-shadow: 0 2px 12px rgba(15,23,42,.05);
  }

  .lp-feat-card:hover {
    border-color: rgba(79,109,255,.3);
    box-shadow: 0 8px 28px rgba(79,109,255,.12);
    transform: translateY(-3px);
  }

  .lp-feat-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
  }

  .lp-feat-icon svg { width: 17px; height: 17px; }

  .lp-feat-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 3px;
  }

  .lp-feat-desc {
    font-size: 11px;
    color: #94a3b8;
    line-height: 1.5;
  }

  /* Responsive */
  @media (max-width: 560px) {
    .lp-left { padding: 40px 28px; margin: 24px 12px; }
  }
</style>

<div class="lp-root">

  {{-- Background decorations --}}
  <div class="lp-dots"></div>
  <div class="lp-blob lp-blob-1"></div>
  <div class="lp-blob lp-blob-2"></div>
  <div class="lp-blob lp-blob-3"></div>

  {{-- Centered Form Card --}}
  <div class="lp-left">

    {{-- Logo --}}
    <div class="lp-logo">
      <div class="lp-logo-icon">
        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
      </div>
      <div class="lp-logo-name">
        IT Automation Dashboard
        <small>AI Production Monitoring</small>
      </div>
    </div>

    {{-- Heading --}}
    <div class="lp-heading">
      <h1>Sign in to your <span class="accent">Dashboard</span> ✦</h1>
      <p>Enter your credentials to access real-time production insights and AI-driven analytics.</p>
    </div>

    {{-- Form --}}
    <form role="form" method="POST" action="{{ url('session') }}">
      @csrf

      <div class="lp-field">
        <label class="lp-label" for="email">Email Address</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
          </span>
          <input type="email" class="lp-input" name="email" id="email"
                 placeholder="admin@company.com" value="{{ old('email') }}"
                 autocomplete="email" aria-label="Email">
        </div>
        @error('email')
          <p class="lp-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="lp-field">
        <label class="lp-label" for="password">Password</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
          </span>
          <input type="password" class="lp-input" name="password" id="password"
                 placeholder="••••••••"
                 autocomplete="current-password" aria-label="Password">
        </div>
        @error('password')
          <p class="lp-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="lp-remember">
        <input type="checkbox" id="rememberMe" name="remember" checked>
        <label for="rememberMe">Keep me signed in for 30 days</label>
      </div>

      <button type="submit" class="lp-btn">
        <span class="lp-btn-shine"></span>
        Sign In to Dashboard →
      </button>
    </form>

    {{-- Links --}}   
    <div class="lp-links">
      <p><a href="/login/forgot-password">Forgot your password?</a></p>
      <p>No account yet? <a href="register">Create one free</a></p>
      <div style="display:flex;justify-content:center;">
        <span class="lp-secure-badge">
          <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
          256-bit SSL Encrypted
        </span>
      </div>
    </div>
  </div>


</div>

@endsection