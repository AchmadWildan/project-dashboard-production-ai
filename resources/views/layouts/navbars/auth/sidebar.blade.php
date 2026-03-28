<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  
  <style>
    /* ── RESET & BASE VARIABLES (BRIGHT THEME) ── */
    #sidenav-main {
      background: #ffffff !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03), 4px 0 24px rgba(0, 0, 0, 0.02) !important;
      transition: width 0.3s ease;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border-right: 1px solid rgba(226, 232, 240, 0.8);
    }

    /* ── HEADER ── */
    .sidenav-header {
      padding: 24px 20px 16px;
      margin-bottom: 40px;
      position: relative;
    }
    .sidenav-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 20px;
      right: 20px;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
    }
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0;
    }
    .navbar-brand-img {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      /* background: linear-gradient(135deg, #00c6ff, #0072ff); */
      padding: 6px;
      box-shadow: 0 4px 12px rgba(0, 114, 255, 0.2);
      position: relative;
    }
    .brand-text {
      color: #1e293b;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.3px;
      display: flex;
      flex-direction: column;
      line-height: 1.2;
    }
    .brand-text small {
      font-size: 10px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    /* ── SCROLL AREA ── */
    .sidenav-nav-wrapper {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 0 16px 20px;
    }
    /* Custom Scrollbar for Sidenav */
    .sidenav-nav-wrapper::-webkit-scrollbar {
      width: 4px;
    }
    .sidenav-nav-wrapper::-webkit-scrollbar-track {
      background: transparent;
    }
    .sidenav-nav-wrapper::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }
    .sidenav-nav-wrapper:hover::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
    }

    /* ── NAV ITEMS ── */
    .navbar-nav {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    /* Section Titles */
    .nav-section-title {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      margin: 20px 0 8px 16px;
    }

    /* Links */
    .nav-link {
      display: flex;
      align-items: center;
      padding: 10px 14px;
      border-radius: 12px;
      color: #475569 !important;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.25s ease;
      position: relative;
    }

    /* Hover State */
    .nav-link:hover {
      background: #f1f5f9;
      color: #0f172a !important;
      transform: translateX(4px);
    }

    /* Active State */
    .nav-link.active {
      background: linear-gradient(135deg, rgba(0, 198, 255, 0.08) 0%, rgba(0, 114, 255, 0.08) 100%);
      color: #0072ff !important;
      font-weight: 700;
      box-shadow: inset 0 0 0 1px rgba(0, 114, 255, 0.15);
    }
    .nav-link.active::before {
      content: '';
      position: absolute;
      left: -16px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 20px;
      background: #0072ff;
      border-radius: 0 4px 4px 0;
      box-shadow: 0 0 8px rgba(0,114,255,0.4);
    }

    /* Icons */
    .nav-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      transition: all 0.25s ease;
      color: #64748b;
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
      border: 1px solid rgba(0,0,0,0.03);
    }
    .nav-icon svg {
      width: 16px;
      height: 16px;
      fill: currentColor;
    }

    .nav-link:hover .nav-icon {
      background: #e2e8f0;
      color: #334155;
    }

    .nav-link.active .nav-icon {
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 114, 255, 0.3);
      border: none;
    }
    
    /* Logout button specific */
    .nav-link.logout-link {
        margin-top: 16px;
        color: #ef4444 !important;
    }
    .nav-link.logout-link .nav-icon {
        color: #ef4444;
        background: #fef2f2;
        border-color: #fecaca;
    }
    .nav-link.logout-link:hover {
        background: #fee2e2;
        color: #dc2626 !important;
        transform: translateY(-2px);
    }
    
  </style>

  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/img/logo-ct.png') }}" class="navbar-brand-img" alt="logo">
      <div class="brand-text">
        IT Automation
        <!-- <small>Dashboard Console</small> -->
      </div>
    </a>
  </div>

  <div class="sidenav-nav-wrapper">
    <ul class="navbar-nav">
      
      {{-- DASHBOARD --}}
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
          </div>
          <span class="nav-link-text">Dashboard Overview</span>
        </a>
      </li>

      {{-- LIVE MACHINE STATUS --}}
      <div class="nav-section-title">Live Machine Status</div>
      
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('packing-unit1') ? 'active' : '') }}" href="{{ url('packing-unit1') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
          </div>
          <span class="nav-link-text">Packing Unit 1</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('packing-unit2') ? 'active' : '') }}" href="{{ url('packing-unit2') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
          </div>
          <span class="nav-link-text">Packing Unit 2</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('live-hormann') ? 'active' : '') }}" href="{{ url('live-hormann') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
          </div>
          <span class="nav-link-text">Hormann WH / DC</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('automation-device') ? 'active' : '') }}" href="{{ url('automation-device') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M22 9V7h-2V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2h2v-2h-2v-2h2v-2h-2V9h2zm-4 10H4V5h14v14zM6 13h5v4H6v-4zm6-6h5v3h-5V7zM6 7h5v5H6V7zm6 7h5v5h-5v-5z"/></svg>
          </div>
          <span class="nav-link-text">Automation Devices</span>
        </a>
      </li>

      {{-- PRODUCTION REPORT --}}
      <div class="nav-section-title">Production Reports</div>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('packing-unit1-report') ? 'active' : '') }}" href="{{ url('packing-unit1-report') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          </div>
          <span class="nav-link-text">Unit 1 Report</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('packing-unit2-report') ? 'active' : '') }}" href="{{ url('packing-unit2-report') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          </div>
          <span class="nav-link-text">Unit 2 Report</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('virtual-reality') ? 'active' : '') }}" href="{{ url('virtual-reality') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
          </div>
          <span class="nav-link-text">Distribution Center</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('chatbot-sql-to-ai') ? 'active' : '') }}" href="{{ url('chatbot-sql-to-ai') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/><path d="M7 9h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>
          </div>
          <span class="nav-link-text">AI Chatbot SQL</span>
        </a>
      </li>

      {{-- ACCOUNT CONTROLS --}}
      <div class="nav-section-title">Settings & Account</div>

      <li class="nav-item">
        <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          </div>
          <span class="nav-link-text">Profile Settings</span>
        </a>
      </li>

    </ul>
  </div>
</aside>
