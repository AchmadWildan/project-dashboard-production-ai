@extends('layouts.user_type.auth')

@section('content')

<style>
  /* =============================================
     PACKING UNIT 2 – Premium Dashboard Styles
     ============================================= */
  .pu2 {
    --pu2-online:  #10b981;
    --pu2-offline: #ef4444;
    --pu2-retire:  #f59e0b;
    --pu2-total:   #6366f1;
    --pu2-grad-start: #0ea5e9;
    --pu2-grad-end:   #6366f1;
  }

  /* ---------- Page header ---------- */
  .pu2-page-header {
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    border-radius: 20px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(168, 85, 247, .3);
  }
  .pu2-page-header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
  }
  .pu2-page-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 20%;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
  }
  .pu2-page-header .header-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    letter-spacing: .3px;
  }
  .pu2-page-header .header-subtitle {
    font-size: .825rem;
    color: rgba(255,255,255,.75);
    margin: .25rem 0 0;
  }
  .pu2-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 50px;
    padding: .3rem .9rem;
    font-size: .72rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: .4px;
  }
  .pu2-live-badge .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 0 0 rgba(74, 222, 128, .6);
    animation: pu2-pulse 1.6s infinite;
  }
  @keyframes pu2-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(74, 222, 128, .6); }
    70%  { box-shadow: 0 0 0 8px rgba(74, 222, 128, 0); }
    100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
  }
  .pu2-last-update {
    font-size: .7rem;
    color: rgba(255,255,255,.65);
    margin-top: .4rem;
  }

  /* ---------- Stat Cards ---------- */
  .pu2 .stat-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0,0,0,.07);
    transition: transform .22s ease, box-shadow .22s ease;
    overflow: hidden;
  }
  .pu2 .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 36px rgba(0,0,0,.11);
  }
  .pu2 .stat-card .card-body {
    padding: 1.2rem 1.4rem !important;
  }
  .pu2 .stat-card .stat-label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #94a3b8;
    margin-bottom: .3rem;
  }
  .pu2 .stat-card .stat-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    margin: 0;
  }
  .pu2 .stat-card .stat-sub {
    font-size: .72rem;
    color: #94a3b8;
    margin-top: .35rem;
  }
  .pu2 .stat-icon {
    width: 54px; height: 54px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
    flex-shrink: 0;
  }
  .pu2 .stat-icon.online  { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 6px 18px rgba(16,185,129,.35); }
  .pu2 .stat-icon.offline { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 6px 18px rgba(239,68,68,.35); }
  .pu2 .stat-icon.retire  { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 6px 18px rgba(245,158,11,.35); }
  .pu2 .stat-icon.total   { background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 6px 18px rgba(99,102,241,.35); }

  .pu2 .stat-value.online  { color: var(--pu2-online); }
  .pu2 .stat-value.offline { color: var(--pu2-offline); }
  .pu2 .stat-value.retire  { color: var(--pu2-retire); }
  .pu2 .stat-value.total   { color: var(--pu2-total); }

  /* ---------- Progress bar on stat cards ---------- */
  .pu2 .stat-progress {
    height: 4px;
    border-radius: 999px;
    background: #f1f5f9;
    margin-top: .85rem;
    overflow: hidden;
  }
  .pu2 .stat-progress-bar {
    height: 100%;
    border-radius: 999px;
    transition: width .8s ease;
  }

  /* ---------- Table Card ---------- */
  .pu2 .table-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,.07);
    overflow: hidden;
  }
  .pu2 .table-card .card-header {
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    padding: 1.25rem 1.5rem;
  }
  .pu2 .table-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
  }
  .pu2 .table-card-subtitle {
    font-size: .76rem;
    color: #94a3b8;
    margin: .15rem 0 0;
  }

  /* ---------- Modern Table ---------- */
  .pu2 .table-modern {
    margin: 0;
  }
  .pu2 .table-modern thead tr {
    background: #f8fafc;
  }
  .pu2 .table-modern thead th {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #94a3b8;
    padding: .9rem 1rem;
    border: none;
    white-space: nowrap;
  }
  .pu2 .table-modern tbody td {
    padding: .85rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: .85rem;
    color: #475569;
  }
  .pu2 .table-modern tbody tr:last-child td { border-bottom: none; }
  .pu2 .table-modern tbody tr {
    transition: background .15s ease;
  }
  .pu2 .table-modern tbody tr:hover td { background: #f8fafc; }

  /* ---------- Status Badges ---------- */
  .pu2-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .3rem .75rem;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .3px;
  }
  .pu2-badge.online  { background: rgba(16,185,129,.12);  color: #059669; }
  .pu2-badge.offline { background: rgba(239,68,68,.12);   color: #dc2626; }
  .pu2-badge.retire  { background: rgba(245,158,11,.12);  color: #d97706; }
  .pu2-badge .badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
  }
  .pu2-badge.online .badge-dot { animation: pu2-pulse 1.6s infinite; box-shadow: none; }

  /* ---------- Toolbar ---------- */
  .pu2-toolbar {
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .pu2-btn-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    transition: all .2s;
    cursor: pointer;
    text-decoration: none;
  }
  .pu2-btn-icon:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #334155;
  }
  #pu2-search {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: .35rem .85rem .35rem 2.1rem;
    font-size: .82rem;
    color: #334155;
    appearance: none;
    outline: none;
    width: 180px;
    transition: border-color .2s, box-shadow .2s;
  }
  #pu2-search:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
  }
  .pu2-search-wrap {
    position: relative;
  }
  .pu2-search-wrap i {
    position: absolute;
    left: .7rem; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: .8rem;
    pointer-events: none;
  }

  /* ---------- Empty state ---------- */
  .pu2-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
  }
  .pu2-empty i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }
  .pu2-empty p { font-size: .85rem; margin: 0; }
</style>

<div class="pu2">

  {{-- ══════════ Page Header ══════════ --}}
  <div class="pu2-page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <p class="header-title">🏭 Packing Unit 2 — Machine Monitor</p>
      <p class="header-subtitle mb-0">Real-time status monitoring for all machines in Production Unit 2</p>
      <p class="pu2-last-update mb-0" id="pu2LastUpdate">Last updated: —</p>
    </div>
    <div class="text-end">
      <span class="pu2-live-badge">
        <span class="dot"></span> LIVE
      </span>
    </div>
  </div>

  {{-- ══════════ Stat Cards ══════════ --}}
  <div class="row g-4 mb-4">

    {{-- Online --}}
    <div class="col-xl-3 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Machine Online</p>
              <h4 class="stat-value online mb-0" id="onlineMachine">—</h4>
              <p class="stat-sub">units currently running</p>
            </div>
            <div class="stat-icon online">
              <i class="ni ni-check-bold"></i>
            </div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="onlineBar" style="width:0%; background: linear-gradient(90deg,#10b981,#34d399)"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Offline --}}
    <div class="col-xl-3 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Machine Offline</p>
              <h4 class="stat-value offline mb-0" id="offlineMachine">—</h4>
              <p class="stat-sub">units currently stopped</p>
            </div>
            <div class="stat-icon offline">
              <i class="ni ni-fat-remove"></i>
            </div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="offlineBar" style="width:0%; background: linear-gradient(90deg,#ef4444,#f87171)"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Retire --}}
    <div class="col-xl-3 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Machine Retire</p>
              <h4 class="stat-value retire mb-0" id="retireMachine">—</h4>
              <p class="stat-sub">units decommissioned</p>
            </div>
            <div class="stat-icon retire">
              <i class="ni ni-time-alarm"></i>
            </div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="retireBar" style="width:0%; background: linear-gradient(90deg,#f59e0b,#fbbf24)"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Total --}}
    <div class="col-xl-3 col-sm-6">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <p class="stat-label mb-1">Total Machine</p>
              <h4 class="stat-value total mb-0" id="totalMachine">—</h4>
              <p class="stat-sub">registered in Unit 2</p>
            </div>
            <div class="stat-icon total">
              <i class="ni ni-tv-2"></i>
            </div>
          </div>
          <div class="stat-progress">
            <div class="stat-progress-bar" id="totalBar" style="width:100%; background: linear-gradient(90deg,#6366f1,#818cf8)"></div>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /row stat cards --}}

  {{-- ══════════ Table Card ══════════ --}}
  <div class="row">
    <div class="col-12">
      <div class="card table-card">

        {{-- Card Header --}}
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
              <h6 class="table-card-title">
                <i class="ni ni-bullet-list-67 me-2" style="color:#6366f1"></i>
                List Machine — P2
              </h6>
              <p class="table-card-subtitle">All machines registered to Packing Unit 2</p>
            </div>
            <div class="pu2-toolbar">
              <div class="pu2-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="pu2-search" placeholder="Search machine…">
              </div>
              <a class="pu2-btn-icon" title="Refresh" onclick="location.reload()">
                <i class="fa fa-refresh"></i>
              </a>
            </div>
          </div>
        </div>

        {{-- Card Body / Table --}}
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tabel-mesin" class="table table-modern align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Status</th>
                  <th>Machine</th>
                  <th class="text-center">Last Time (ON/OFF)</th>
                  <th class="text-center">Cycle</th>
                  <th class="text-center">State</th>
                </tr>
              </thead>
              <tbody>
                {{-- Populated by packing-unit2.js --}}
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>{{-- /pu2 --}}

{{-- Update progress bars whenever the counters change --}}
<script>
  function pu2UpdateBars() {
    const total   = parseInt(document.getElementById('totalMachine').textContent)   || 0;
    const online  = parseInt(document.getElementById('onlineMachine').textContent)  || 0;
    const offline = parseInt(document.getElementById('offlineMachine').textContent) || 0;
    const retire  = parseInt(document.getElementById('retireMachine').textContent)  || 0;
    if (!total) return;
    document.getElementById('onlineBar').style.width  = (online  / total * 100) + '%';
    document.getElementById('offlineBar').style.width = (offline / total * 100) + '%';
    document.getElementById('retireBar').style.width  = (retire  / total * 100) + '%';
    // Timestamp
    const now = new Date();
    document.getElementById('pu2LastUpdate').textContent =
      'Last updated: ' + now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID');
  }

  // Observe counter elements for changes
  ['onlineMachine','offlineMachine','retireMachine','totalMachine'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) {
      new MutationObserver(pu2UpdateBars).observe(el, { childList: true, subtree: true, characterData: true });
    }
  });

  // Client-side search filter
  document.addEventListener('DOMContentLoaded', function() {
    var searchBox = document.getElementById('pu2-search');
    if (searchBox) {
      searchBox.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#tabel-mesin tbody tr').forEach(function(row) {
          row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
      });
    }
  });
</script>

@endsection

@push('dashboard')
<script src="{{ asset('js/packing-unit2.js') }}"></script>
@endpush