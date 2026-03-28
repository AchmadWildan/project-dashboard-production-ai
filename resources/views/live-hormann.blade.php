@extends('layouts.user_type.auth')

@section('content')

<style>
  /* =============================================
     LIVE HORMANN – Premium Door Status Styles
     ============================================= */
  .lh {
    --lh-open:   #10b981;
    --lh-closed: #ef4444;
    --lh-grad1-s: #0ea5e9; --lh-grad1-e: #6366f1;
    --lh-grad2-s: #f59e0b; --lh-grad2-e: #ef4444;
    --lh-grad3-s: #10b981; --lh-grad3-e: #06b6d4;
  }

  /* ---------- Page Header ---------- */
  .lh-page-header {
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    border-radius: 20px;
    padding: 1.4rem 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(168, 85, 247, .3);
  }
  .lh-page-header::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
  }
  .lh-page-header::after {
    content: '';
    position: absolute;
    bottom: -70px; left: 15%;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
  }
  .lh-page-header .header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    letter-spacing: .3px;
  }
  .lh-page-header .header-subtitle {
    font-size: .8rem;
    color: rgba(255,255,255,.6);
    margin: .2rem 0 0;
  }
  .lh-live-badge {
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
  .lh-live-badge .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #4ade80;
    animation: lh-pulse 1.6s infinite;
  }
  @keyframes lh-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(74,222,128,.7); }
    70%  { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
    100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
  }
  .lh-last-update {
    font-size: .68rem;
    color: rgba(255,255,255,.5);
    margin-top: .35rem;
  }

  /* ---------- Section Card ---------- */
  .lh-section-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 28px rgba(0,0,0,.08);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }
  .lh-section-header {
    padding: .9rem 1.4rem;
    display: flex;
    align-items: center;
    gap: .65rem;
  }
  .lh-section-header .sec-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,.2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #fff;
  }
  .lh-section-header .sec-title {
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
  }
  .lh-section-header .sec-sub {
    font-size: .7rem;
    color: rgba(255,255,255,.75);
    margin: 0;
  }
  .lh-section-header .sec-count {
    margin-left: auto;
    background: rgba(255,255,255,.2);
    border-radius: 50px;
    padding: .2rem .75rem;
    font-size: .7rem;
    font-weight: 600;
    color: #fff;
  }

  /* Gradient headers per section */
  .lh-header-wh1  { background: linear-gradient(135deg, #0ea5e9, #6366f1); }
  .lh-header-wh2  { background: linear-gradient(135deg, #f59e0b, #ef4444); }
  .lh-header-dc   { background: linear-gradient(135deg, #10b981, #06b6d4); }

  /* ---------- Door Grid ---------- */
  .lh-section-body {
    background: #fff;
    padding: 1rem 1.2rem 1.2rem;
  }

  /* Row label */
  .lh-row-label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #94a3b8;
    display: flex;
    align-items: center;
    margin-bottom: .5rem;
    margin-top: .8rem;
  }
  .lh-row-label:first-child { margin-top: 0; }
  .lh-row-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
    margin-left: .5rem;
  }

  /* Door grid — wrap, fixed gap so all rows stay on one line */
  .lh-door-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: space-between;
  }

  /* Individual door card — fixed size for visual consistency across all sections */
  .lh-door {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 0 0 56px;
    width: 56px;
    height: 74px;
    border-radius: 12px;
    cursor: default;
    transition: transform .2s ease, box-shadow .2s ease;
    position: relative;
    border: 2px solid transparent;
  }
  .lh-door:hover {
    transform: translateY(-4px);
  }
  .lh-door .door-icon {
    font-size: 1.5rem;
    margin-bottom: 4px;
    line-height: 1;
  }
  .lh-door .door-name {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .3px;
  }
  .lh-door .door-status-dot {
    position: absolute;
    top: 6px; right: 6px;
    width: 7px; height: 7px;
    border-radius: 50%;
  }

  /* OPEN state */
  .lh-door.door-open {
    background: linear-gradient(135deg, rgba(16,185,129,.12), rgba(16,185,129,.06));
    border-color: rgba(16,185,129,.3);
    box-shadow: 0 4px 14px rgba(16,185,129,.18);
  }
  .lh-door.door-open .door-icon { color: #059669; }
  .lh-door.door-open .door-name { color: #059669; }
  .lh-door.door-open .door-status-dot {
    background: #10b981;
    animation: lh-pulse 1.6s infinite;
  }

  /* CLOSED state */
  .lh-door.door-closed {
    background: linear-gradient(135deg, rgba(239,68,68,.1), rgba(239,68,68,.05));
    border-color: rgba(239,68,68,.25);
    box-shadow: 0 4px 12px rgba(239,68,68,.12);
  }
  .lh-door.door-closed .door-icon { color: #dc2626; }
  .lh-door.door-closed .door-name { color: #dc2626; }
  .lh-door.door-closed .door-status-dot { background: #ef4444; }

  /* ---------- Legend ---------- */
  .lh-legend {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-left: auto;
  }
  .lh-legend-item {
    display: flex;
    align-items: center;
    gap: .35rem;
    font-size: .7rem;
    color: #64748b;
    font-weight: 500;
  }
  .lh-legend-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
  }
  .lh-legend-dot.open   { background: #10b981; }
  .lh-legend-dot.closed { background: #ef4444; }

  /* ---------- Stats strip ---------- */
  .lh-stats-strip {
    display: flex;
    gap: .75rem;
    margin-bottom: 1rem;
  }
  .lh-stat-pill {
    display: flex;
    align-items: center;
    gap: .4rem;
    background: #f8fafc;
    border: 1px solid #e8edf3;
    border-radius: 50px;
    padding: .25rem .8rem;
    font-size: .72rem;
    color: #475569;
    font-weight: 600;
  }
  .lh-stat-pill .pill-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
  }
</style>

<div class="lh">

  {{-- ══════ Page Header ══════ --}}
  <div class="lh-page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <p class="header-title">🚪 Hormann Door Monitor</p>
      <p class="header-subtitle mb-0">Live status monitoring — Warehouse Unit 1, Unit 2 &amp; Distribution Center</p>
      <p class="lh-last-update mb-0" id="lhLastUpdate">Last updated: —</p>
    </div>
    <div class="d-flex align-items-center gap-3">
      <div class="lh-legend">
        <div class="lh-legend-item"><div class="lh-legend-dot open"></div> Open</div>
        <div class="lh-legend-item"><div class="lh-legend-dot closed"></div> Closed</div>
      </div>
      <span class="lh-live-badge"><span class="dot"></span> LIVE</span>
    </div>
  </div>

  {{-- ══════ WH Unit 1 ══════ --}}
  <div class="lh-section-card">
    <div class="lh-section-header lh-header-wh1">
      <div class="sec-icon"><i class="fas fa-warehouse"></i></div>
      <div>
        <p class="sec-title">WH — Unit 1</p>
        <p class="sec-sub">Door 1–14 · Rows A, B, C</p>
      </div>
      <div class="lh-stats-strip ms-auto mb-0" id="lh-stats-wh1" style="margin-bottom:0!important">
        <div class="lh-stat-pill"><div class="pill-dot" style="background:#10b981"></div><span id="wh1-open">—</span> Open</div>
        <div class="lh-stat-pill"><div class="pill-dot" style="background:#ef4444"></div><span id="wh1-closed">—</span> Closed</div>
      </div>
    </div>
    <div class="lh-section-body" id="body-wh-unit1">
      <div class="text-center text-muted py-4" style="font-size:.82rem"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</div>
    </div>
  </div>

  {{-- ══════ WH Unit 2 + DC ══════ --}}
  <div class="row g-4">

    {{-- WH Unit 2 --}}
    <div class="col-lg-6 col-12">
      <div class="lh-section-card" style="margin-bottom:0">
        <div class="lh-section-header lh-header-wh2">
          <div class="sec-icon"><i class="fas fa-industry"></i></div>
          <div>
            <p class="sec-title">WH — Unit 2</p>
            <p class="sec-sub">Door 1–12 · Rows A, B, C</p>
          </div>
          <div class="lh-stats-strip ms-auto mb-0" id="lh-stats-wh2" style="margin-bottom:0!important">
            <div class="lh-stat-pill"><div class="pill-dot" style="background:#10b981"></div><span id="wh2-open">—</span> Open</div>
            <div class="lh-stat-pill"><div class="pill-dot" style="background:#ef4444"></div><span id="wh2-closed">—</span> Closed</div>
          </div>
        </div>
        <div class="lh-section-body" id="body-wh-unit2">
          <div class="text-center text-muted py-4" style="font-size:.82rem"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</div>
        </div>
      </div>
    </div>

    {{-- Distribution Center --}}
    <div class="col-lg-6 col-12">
      <div class="lh-section-card" style="margin-bottom:0">
        <div class="lh-section-header lh-header-dc">
          <div class="sec-icon"><i class="fas fa-shipping-fast"></i></div>
          <div>
            <p class="sec-title">Distribution Center</p>
            <p class="sec-sub">Door 13–21 · Rows A, B, C</p>
          </div>
          <div class="lh-stats-strip ms-auto mb-0" id="lh-stats-dc" style="margin-bottom:0!important">
            <div class="lh-stat-pill"><div class="pill-dot" style="background:#10b981"></div><span id="dc-open">—</span> Open</div>
            <div class="lh-stat-pill"><div class="pill-dot" style="background:#ef4444"></div><span id="dc-closed">—</span> Closed</div>
          </div>
        </div>
        <div class="lh-section-body" id="body-dc">
          <div class="text-center text-muted py-4" style="font-size:.82rem"><i class="fa fa-spinner fa-spin me-1"></i> Loading…</div>
        </div>
      </div>
    </div>

  </div>

</div>{{-- /lh --}}

@endsection
@push('dashboard')
<script src="{{ asset('js/live-hormann.js') }}"></script>
@endpush