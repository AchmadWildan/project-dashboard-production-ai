@extends('layouts.user_type.auth')

@section('content')

<style>
  /* =============================================
     AUTOMATION DEVICE – Premium Dashboard Styles
     ============================================= */
  .ad {
    --ad-online:  #10b981;
    --ad-offline: #ef4444;
    --ad-total:   #6366f1;
  }

  /* ---------- Page Header ---------- */
  .ad-page-header {
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    border-radius: 20px; 
    padding: 1.4rem 2rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(168, 85, 247, .3);
  }
  .ad-page-header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
  }
  .ad-page-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 20%;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
  }
  .ad-page-header .header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    letter-spacing: .3px;
  }
  .ad-page-header .header-subtitle {
    font-size: .8rem;
    color: rgba(255,255,255,.6);
    margin: .2rem 0 0;
  }
  .ad-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50px;
    padding: .3rem .9rem;
    font-size: .72rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: .4px;
  }
  .ad-live-badge .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #4ade80;
    animation: ad-pulse 1.6s infinite;
  }
  @keyframes ad-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(74,222,128,.7); }
    70%  { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
    100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
  }
  .ad-last-update {
    font-size: .68rem;
    color: rgba(255,255,255,.5);
    margin-top: .35rem;
  }

  /* ---------- Stat Cards ---------- */
  .ad .stat-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0,0,0,.07);
    transition: transform .22s ease, box-shadow .22s ease;
    overflow: hidden;
  }
  .ad .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 36px rgba(0,0,0,.11);
  }
  .ad .stat-card .card-body {
    padding: 1.2rem 1.4rem !important;
  }
  .ad .stat-label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #94a3b8;
    margin-bottom: .3rem;
  }
  .ad .stat-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    margin: 0;
  }
  .ad .stat-sub {
    font-size: .72rem;
    color: #94a3b8;
    margin-top: .35rem;
  }
  .ad .stat-icon {
    width: 54px; height: 54px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
    flex-shrink: 0;
  }
  .ad .stat-icon.online  { background: linear-gradient(135deg,#10b981,#059669); box-shadow: 0 6px 18px rgba(16,185,129,.35); }
  .ad .stat-icon.offline { background: linear-gradient(135deg,#ef4444,#dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.35); }
  .ad .stat-icon.total   { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(99,102,241,.35); }
  .ad .stat-value.online  { color: var(--ad-online); }
  .ad .stat-value.offline { color: var(--ad-offline); }
  .ad .stat-value.total   { color: var(--ad-total); }
  .ad .stat-progress {
    height: 4px;
    border-radius: 999px;
    background: #f1f5f9;
    margin-top: .85rem;
    overflow: hidden;
  }
  .ad .stat-progress-bar {
    height: 100%;
    border-radius: 999px;
    transition: width .8s ease;
  }

  /* ---------- Section Group Header ---------- */
  .ad-group-label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1rem;
    margin-top: 1.5rem;
  }
  .ad-group-label:first-of-type { margin-top: 0; }
  .ad-group-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8edf3;
  }
  .ad-group-label .group-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  /* ---------- Table Card ---------- */
  .ad .table-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 28px rgba(0,0,0,.07);
    overflow: hidden;
    height: 100%;
  }
  .ad .table-card .card-header {
    padding: .85rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
  }
  .ad .table-card-title {
    font-size: .88rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .ad .table-card-title .tc-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    color: #fff;
    flex-shrink: 0;
  }

  /* Icon color variants */
  .tc-icon.machine { background: linear-gradient(135deg,#6366f1,#818cf8); }
  .tc-icon.hormann { background: linear-gradient(135deg,#f59e0b,#fbbf24); }
  .tc-icon.wh      { background: linear-gradient(135deg,#06b6d4,#22d3ee); }

  /* ---------- Modern Table ---------- */
  .ad .table-modern { margin: 0; }
  .ad .table-modern thead tr { background: #f8fafc; }
  /* Force symmetrical padding — override Bootstrap !important */
  .ad .table-modern thead th,
  .ad .table-modern tbody td {
    padding: .75rem 1rem !important;
    vertical-align: middle;
    border: none;
  }
  .ad .table-modern thead th {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #94a3b8;
    white-space: nowrap;
  }
  .ad .table-modern tbody td {
    border-bottom: 1px solid #f1f5f9 !important;
    font-size: .83rem;
    color: #475569;
  }
  .ad .table-modern tbody tr:last-child td { border-bottom: none !important; }
  .ad .table-modern tbody tr { transition: background .15s ease; }
  .ad .table-modern tbody tr:hover td { background: #f8fafc; }

  /* ---------- IP Link ---------- */
  .ad-ip-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'SFMono-Regular', 'Consolas', monospace;
    font-size: .78rem;
    font-weight: 600;
    color: #6366f1;
    text-decoration: none;
    padding: .2rem .6rem;
    background: rgba(99,102,241,.08);
    border-radius: 6px;
    border: 1px solid rgba(99,102,241,.2);
    transition: all .2s ease;
  }
  .ad-ip-link:hover {
    background: rgba(99,102,241,.15);
    border-color: rgba(99,102,241,.4);
    color: #4f46e5;
    text-decoration: none;
  }
  .ad-ip-link i { font-size: .65rem; opacity: .7; }

  /* ---------- Status Badges ---------- */
  .ad-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .28rem .7rem;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .4px;
  }
  .ad-badge.online  { background: rgba(16,185,129,.12);  color: #059669; }
  .ad-badge.offline { background: rgba(239,68,68,.12);   color: #dc2626; }
  .ad-badge .bd { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
  .ad-badge.online .bd { animation: ad-pulse 1.6s infinite; }
</style>

<div class="ad">

  {{-- ══════ Page Header ══════ --}}
  <div class="ad-page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <p class="header-title">🖥️ Automation Device Monitor</p>
      <p class="header-subtitle mb-0">Live network status — Machine &amp; Hormann devices across all areas</p>
      <p class="ad-last-update mb-0" id="adLastUpdate">Last updated: —</p>
    </div>
    <span class="ad-live-badge"><span class="dot"></span> LIVE</span>
  </div>

  {{-- ══════ Stat Cards ══════ --}}
  <div class="row g-4 mb-4">

    <div class="col-xl-4 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Device Online</p>
              <h4 class="stat-value online mb-0" id="onlineDevice">—</h4>
              <p class="stat-sub">reachable on network</p>
            </div>
            <div class="stat-icon online"><i class="ni ni-check-bold"></i></div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="onlineBar" style="width:0%; background:linear-gradient(90deg,#10b981,#34d399)"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Device Offline</p>
              <h4 class="stat-value offline mb-0" id="offlineDevice">—</h4>
              <p class="stat-sub">unreachable on network</p>
            </div>
            <div class="stat-icon offline"><i class="ni ni-fat-remove"></i></div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="offlineBar" style="width:0%; background:linear-gradient(90deg,#ef4444,#f87171)"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Total Device</p>
              <h4 class="stat-value total mb-0" id="totalDevice">—</h4>
              <p class="stat-sub">registered in system</p>
            </div>
            <div class="stat-icon total"><i class="ni ni-tv-2"></i></div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="totalBar" style="width:100%; background:linear-gradient(90deg,#6366f1,#818cf8)"></div>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /stat cards --}}

  {{-- ══════ Machine Device ══════ --}}
  <p class="ad-group-label">
    <span class="group-dot" style="background:#6366f1"></span>
    Machine Device Status
  </p>
  <div class="row g-4 mb-2">

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon machine"><i class="ni ni-settings-gear-65"></i></span>
            Machine Device — P1
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-mesin-p1" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Machine</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon machine"><i class="ni ni-settings-gear-65"></i></span>
            Machine Device — P2
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-mesin-p2" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Machine</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ══════ Hormann DC ══════ --}}
  <p class="ad-group-label">
    <span class="group-dot" style="background:#f59e0b"></span>
    Hormann Device — Distribution Center
  </p>
  <div class="row g-4 mb-2">

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon hormann"><i class="ni ni-box-2"></i></span>
            Hormann DC — In
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-hormann-dcin" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon hormann"><i class="ni ni-box-2"></i></span>
            Hormann DC — Out
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-hormann-dcout" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ══════ Hormann WH ══════ --}}
  <p class="ad-group-label">
    <span class="group-dot" style="background:#06b6d4"></span>
    Hormann Device — Warehouse
  </p>
  <div class="row g-4">

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon wh"><i class="ni ni-building"></i></span>
            Hormann WH — P1
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-hormann-whp1" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon wh"><i class="ni ni-building"></i></span>
            Hormann WH — P2
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-hormann-whp2" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
  {{-- ══════ Timbangan P1 dan P2 ══════ --}}
  <p class="ad-group-label">
    <span class="group-dot" style="background:#06b6d4"></span>
    Perangkat Timbangan
  </p>
  <div class="row g-4">

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon wh"><i class="ni ni-building"></i></span>
            Timbangan — P1
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-timbangan-p1" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-12">
      <div class="card table-card">
        <div class="card-header bg-white">
          <p class="table-card-title">
            <span class="tc-icon wh"><i class="ni ni-building"></i></span>
            Timbangan — P2
          </p>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-timbangan-p2" class="table table-modern align-items-center mb-0" style="table-layout:fixed;width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:48px">#</th>
                  <th class="text-center" style="width:100px">Device</th>
                  <th class="text-center" style="width:165px">IP Address</th>
                  <th class="text-center" style="width:105px">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>{{-- /ad --}}

@endsection
@push('dashboard')
<script src="{{ asset('js/automation-device.js') }}"></script>
@endpush