@extends('layouts.user_type.auth')

@section('content')

<style>
  /* =============================================
     DASHBOARD – Premium OEE & Productivity
     ============================================= */
  .db {
    --db-green:  #10b981;
    --db-yellow: #f59e0b;
    --db-red:    #ef4444;
    --db-blue:   #6366f1;
    --db-cyan:   #06b6d4;
  }

  /* ---------- Page Header ---------- */
  .db-page-header {
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    border-radius: 20px;
    padding: 1.4rem 2rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(168,85,247,.3);
  }
  .db-page-header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
  }
  .db-page-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 20%;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
  }
  .db-page-header .header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
  }
  .db-page-header .header-subtitle {
    font-size: .8rem;
    color: rgba(255,255,255,.7);
    margin: .2rem 0 0;
  }
  .db-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 50px;
    padding: .3rem .9rem;
    font-size: .72rem;
    font-weight: 600;
    color: #fff;
  }
  .db-live-badge .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #4ade80;
    animation: db-pulse 1.6s infinite;
  }
  @keyframes db-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(74,222,128,.7); }
    70%  { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
    100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
  }

  /* ---------- Section label ---------- */
  .db-section-label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1rem;
  }
  .db-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8edf3;
  }
  .db-section-label .sl-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  /* ---------- OEE Summary Cards ---------- */
  .db-oee-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 28px rgba(0,0,0,.07);
    overflow: hidden;
    height: 100%;
  }
  .db-oee-card .card-header {
    padding: .9rem 1.4rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
  }
  .db-oee-card-title {
    font-size: .9rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
  }
  .db-oee-card-sub {
    font-size: .72rem;
    color: #94a3b8;
    margin: .1rem 0 0;
  }

  /* OEE circle */
  .db-oee-circle {
    width: 100px; height: 100px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
  }
  .db-oee-circle .oee-val {
    font-size: 1.65rem;
    font-weight: 800;
    line-height: 1;
    color: #fff;
  }
  .db-oee-circle .oee-lbl {
    font-size: .62rem;
    font-weight: 600;
    color: rgba(255,255,255,.8);
    letter-spacing: .5px;
    margin-top: 2px;
  }
  .db-oee-circle.above { background: linear-gradient(135deg,#10b981,#059669); box-shadow: 0 8px 24px rgba(16,185,129,.35); }
  .db-oee-circle.below { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 8px 24px rgba(245,158,11,.35); }
  .db-oee-circle.danger { background: linear-gradient(135deg,#ef4444,#dc2626); box-shadow: 0 8px 24px rgba(239,68,68,.35); }

  /* Progress bars */
  .db-prog-label {
    font-size: .72rem;
    color: #64748b;
    font-weight: 500;
  }
  .db-prog-val {
    font-size: .72rem;
    font-weight: 700;
    color: #1e293b;
  }
  .db-progress {
    height: 7px;
    border-radius: 999px;
    background: #f1f5f9;
    overflow: hidden;
    margin: .3rem 0 .7rem;
  }
  .db-progress-bar {
    height: 100%;
    border-radius: 999px;
    transition: width .8s ease;
  }
  .db-progress-bar.avail { background: linear-gradient(90deg,#6366f1,#818cf8); }
  .db-progress-bar.perf  { background: linear-gradient(90deg,#06b6d4,#22d3ee); }
  .db-progress-bar.qual  { background: linear-gradient(90deg,#10b981,#34d399); }

  /* Footer info strip */
  .db-oee-footer {
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    padding: .75rem 1.4rem;
    display: flex;
    gap: 1.5rem;
    align-items: center;
  }
  .db-oee-footer-item .fi-label {
    font-size: .65rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  .db-oee-footer-item .fi-val {
    font-size: .82rem;
    font-weight: 700;
    color: #1e293b;
  }

  /* Status badge */
  .db-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: .22rem .65rem;
    border-radius: 50px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .3px;
  }
  .db-status.good    { background: rgba(16,185,129,.12); color: #059669; }
  .db-status.warn    { background: rgba(245,158,11,.12); color: #d97706; }
  .db-status.danger  { background: rgba(239,68,68,.12);  color: #dc2626; }
  .db-status .sd { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

  /* ---------- Machine Table Card ---------- */
  .db-table-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 28px rgba(0,0,0,.07);
    overflow: hidden;
    height: 100%;
  }
  .db-table-card .card-header {
    padding: .9rem 1.4rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
  }
  .db-table-card-title {
    font-size: .9rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .db-tc-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    color: #fff;
  }
  .db-tc-icon.oee   { background: linear-gradient(135deg,#6366f1,#818cf8); }
  .db-tc-icon.prod  { background: linear-gradient(135deg,#10b981,#34d399); }

  /* Table style */
  .db-table {
    margin: 0;
    table-layout: fixed;
    width: 100%;
  }
  .db-table thead th {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #94a3b8;
    padding: .75rem 1rem !important;
    border: none;
    background: #f8fafc;
    white-space: nowrap;
  }
  .db-table tbody td {
    padding: .8rem 1rem !important;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9 !important;
    font-size: .83rem;
    color: #475569;
    border-top: none !important;
  }
  .db-table tbody tr:last-child td { border-bottom: none !important; }
  .db-table tbody tr { transition: background .15s ease; }
  .db-table tbody tr:hover td { background: #f8fafc; }

  /* Inline progress */
  .db-inline-prog {
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .db-inline-prog .ip-val {
    font-size: .8rem;
    font-weight: 700;
    min-width: 36px;
    text-align: right;
    flex-shrink: 0;
  }
  .db-inline-prog .ip-bar {
    flex: 1;
    height: 7px;
    border-radius: 999px;
    background: #f1f5f9;
    overflow: hidden;
  }
  .db-inline-prog .ip-fill {
    height: 100%;
    border-radius: 999px;
    transition: width .8s ease;
  }
  .ip-fill.good   { background: linear-gradient(90deg,#10b981,#34d399); }
  .ip-fill.warn   { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
  .ip-fill.danger { background: linear-gradient(90deg,#ef4444,#f87171); }
  .ip-fill.blue   { background: linear-gradient(90deg,#6366f1,#818cf8); }

  .db-machine-name {
    font-size: .83rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }
  .db-machine-sub {
    font-size: .7rem;
    color: #94a3b8;
  }
</style>

<div class="db">

  {{-- ══════ Page Header ══════ --}}
  <div class="db-page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <p class="header-title">📊 Dashboard OEE & Produktivitas</p>
      <p class="header-subtitle mb-0">Monitoring real-time Overall Equipment Effectiveness — Plant 1 &amp; Plant 2</p>
    </div>
    <div class="d-flex align-items-center gap-3">
      <div style="font-size:.72rem;color:rgba(255,255,255,.7)">{{ now()->isoFormat('dddd, D MMMM YYYY · HH:mm') }} WIB</div>
      <span class="db-live-badge"><span class="dot"></span> LIVE</span>
    </div>
  </div>

  {{-- ══════ OEE Summary ══════ --}}
  <p class="db-section-label">
    <span class="sl-dot" style="background:#6366f1"></span>
    Overall Equipment Effectiveness — Summary
  </p>
  <div class="row g-4 mb-2">

    {{-- OEE Plant 1 --}}
    <div class="col-lg-6 col-12">
      <div class="db-oee-card card">
        <div class="card-header">
          <p class="db-oee-card-title">🏭 OEE — Plant 1 (Packing Unit 1)</p>
          <p class="db-oee-card-sub">Ringkasan availability, performance &amp; quality Plant 1</p>
        </div>
        <div class="card-body p-0">
          <div class="d-flex align-items-center gap-4 p-4">
            <div class="db-oee-circle above">
              <span class="oee-val">85%</span>
              <span class="oee-lbl">OEE</span>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Availability</span>
                <span class="db-prog-val">92%</span>
              </div>
              <div class="db-progress"><div class="db-progress-bar avail" style="width:92%"></div></div>
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Performance</span>
                <span class="db-prog-val">88%</span>
              </div>
              <div class="db-progress"><div class="db-progress-bar perf" style="width:88%"></div></div>
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Quality</span>
                <span class="db-prog-val">97%</span>
              </div>
              <div class="db-progress" style="margin-bottom:0"><div class="db-progress-bar qual" style="width:97%"></div></div>
            </div>
          </div>
        </div>
        <div class="db-oee-footer">
          <div class="db-oee-footer-item">
            <p class="fi-label mb-0">Target OEE</p>
            <p class="fi-val mb-0">80%</p>
          </div>
          <div class="db-oee-footer-item">
            <p class="fi-label mb-0">Status</p>
            <span class="db-status good"><span class="sd"></span>Di atas target</span>
          </div>
          <div class="db-oee-footer-item ms-auto">
            <p class="fi-label mb-0">Selisih</p>
            <p class="fi-val mb-0" style="color:#10b981">+5%</p>
          </div>
        </div>
      </div>
    </div>

    {{-- OEE Plant 2 --}}
    <div class="col-lg-6 col-12">
      <div class="db-oee-card card">
        <div class="card-header">
          <p class="db-oee-card-title">🏭 OEE — Plant 2 (Packing Unit 2)</p>
          <p class="db-oee-card-sub">Ringkasan availability, performance &amp; quality Plant 2</p>
        </div>
        <div class="card-body p-0">
          <div class="d-flex align-items-center gap-4 p-4">
            <div class="db-oee-circle below">
              <span class="oee-val">78%</span>
              <span class="oee-lbl">OEE</span>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Availability</span>
                <span class="db-prog-val">88%</span>
              </div>
              <div class="db-progress"><div class="db-progress-bar avail" style="width:88%"></div></div>
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Performance</span>
                <span class="db-prog-val">81%</span>
              </div>
              <div class="db-progress"><div class="db-progress-bar perf" style="width:81%"></div></div>
              <div class="d-flex justify-content-between">
                <span class="db-prog-label">Quality</span>
                <span class="db-prog-val">95%</span>
              </div>
              <div class="db-progress" style="margin-bottom:0"><div class="db-progress-bar qual" style="width:95%"></div></div>
            </div>
          </div>
        </div>
        <div class="db-oee-footer">
          <div class="db-oee-footer-item">
            <p class="fi-label mb-0">Target OEE</p>
            <p class="fi-val mb-0">80%</p>
          </div>
          <div class="db-oee-footer-item">
            <p class="fi-label mb-0">Status</p>
            <span class="db-status warn"><span class="sd"></span>Sedikit di bawah target</span>
          </div>
          <div class="db-oee-footer-item ms-auto">
            <p class="fi-label mb-0">Selisih</p>
            <p class="fi-val mb-0" style="color:#f59e0b">-2%</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ══════ OEE per Mesin ══════ --}}
  <p class="db-section-label mt-4">
    <span class="sl-dot" style="background:#06b6d4"></span>
    OEE per Mesin
  </p>
  <div class="row g-4 mb-2">

    {{-- OEE P1 --}}
    <div class="col-lg-6 col-12">
      <div class="db-table-card card">
        <div class="card-header">
          <p class="db-table-card-title">
            <span class="db-tc-icon oee"><i class="ni ni-settings-gear-65"></i></span>
            OEE per Mesin — Plant 1
          </p>
        </div>
        <div class="card-body p-0">
          <table class="db-table">
            <thead>
              <tr>
                <th style="width:42%">Mesin</th>
                <th>OEE</th>
                <th style="width:90px" class="text-center">Avail.</th>
                <th style="width:115px" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><p class="db-machine-name">Line A — Packing</p><span class="db-machine-sub">Plant 1</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">88%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:88%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">93%</td>
                <td class="text-center"><span class="db-status good"><span class="sd"></span>Baik</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Line B — Packing</p><span class="db-machine-sub">Plant 1</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">83%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:83%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">89%</td>
                <td class="text-center"><span class="db-status good"><span class="sd"></span>Stabil</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Mixer 1</p><span class="db-machine-sub">Plant 1</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#f59e0b">75%</span>
                    <div class="ip-bar"><div class="ip-fill warn" style="width:75%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">82%</td>
                <td class="text-center"><span class="db-status warn"><span class="sd"></span>Perlu perhatian</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Conveyor Palletizer</p><span class="db-machine-sub">Plant 1</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#ef4444">69%</span>
                    <div class="ip-bar"><div class="ip-fill danger" style="width:69%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">78%</td>
                <td class="text-center"><span class="db-status danger"><span class="sd"></span>Di bawah target</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- OEE P2 --}}
    <div class="col-lg-6 col-12">
      <div class="db-table-card card">
        <div class="card-header">
          <p class="db-table-card-title">
            <span class="db-tc-icon oee"><i class="ni ni-settings-gear-65"></i></span>
            OEE per Mesin — Plant 2
          </p>
        </div>
        <div class="card-body p-0">
          <table class="db-table">
            <thead>
              <tr>
                <th style="width:42%">Mesin</th>
                <th>OEE</th>
                <th style="width:90px" class="text-center">Avail.</th>
                <th style="width:115px" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><p class="db-machine-name">Blending Line</p><span class="db-machine-sub">Plant 2</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">80%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:80%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">87%</td>
                <td class="text-center"><span class="db-status good"><span class="sd"></span>Baik</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Seasoning Line</p><span class="db-machine-sub">Plant 2</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#f59e0b">77%</span>
                    <div class="ip-bar"><div class="ip-fill warn" style="width:77%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">84%</td>
                <td class="text-center"><span class="db-status warn"><span class="sd"></span>Perlu perhatian</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Packing Multihead</p><span class="db-machine-sub">Plant 2</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">82%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:82%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">89%</td>
                <td class="text-center"><span class="db-status good"><span class="sd"></span>Stabil</span></td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Palletizer</p><span class="db-machine-sub">Plant 2</span></td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#ef4444">70%</span>
                    <div class="ip-bar"><div class="ip-fill danger" style="width:70%"></div></div>
                  </div>
                </td>
                <td class="text-center" style="font-weight:700;font-size:.82rem">80%</td>
                <td class="text-center"><span class="db-status danger"><span class="sd"></span>Di bawah target</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  {{-- ══════ Produktivitas ══════ --}}
  <p class="db-section-label mt-4">
    <span class="sl-dot" style="background:#10b981"></span>
    Produktivitas Mesin — Output vs Target
  </p>
  <div class="row g-4">

    {{-- Produktivitas P1 --}}
    <div class="col-lg-6 col-12">
      <div class="db-table-card card">
        <div class="card-header">
          <p class="db-table-card-title">
            <span class="db-tc-icon prod"><i class="ni ni-chart-bar-32"></i></span>
            Produktivitas — Plant 1
          </p>
        </div>
        <div class="card-body p-0">
          <table class="db-table">
            <thead>
              <tr>
                <th style="width:35%">Mesin</th>
                <th style="width:100px" class="text-center">Output/jam</th>
                <th style="width:100px" class="text-center">Target</th>
                <th>% Target</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><p class="db-machine-name">Line A — Packing</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">1.050 bag</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">1.000 bag</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">105%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:100%"></div></div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Line B — Packing</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">930 bag</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">1.000 bag</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#f59e0b">93%</span>
                    <div class="ip-bar"><div class="ip-fill warn" style="width:93%"></div></div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Mixer 1</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">24 batch</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">26 batch</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#f59e0b">92%</span>
                    <div class="ip-bar"><div class="ip-fill warn" style="width:92%"></div></div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Produktivitas P2 --}}
    <div class="col-lg-6 col-12">
      <div class="db-table-card card">
        <div class="card-header">
          <p class="db-table-card-title">
            <span class="db-tc-icon prod"><i class="ni ni-chart-bar-32"></i></span>
            Produktivitas — Plant 2
          </p>
        </div>
        <div class="card-body p-0">
          <table class="db-table">
            <thead>
              <tr>
                <th style="width:35%">Mesin</th>
                <th style="width:100px" class="text-center">Output/jam</th>
                <th style="width:100px" class="text-center">Target</th>
                <th>% Target</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><p class="db-machine-name">Blending Line</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">18 batch</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">20 batch</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#f59e0b">90%</span>
                    <div class="ip-bar"><div class="ip-fill warn" style="width:90%"></div></div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Seasoning Line</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">1.200 kg</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">1.300 kg</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#ef4444">92%</span>
                    <div class="ip-bar"><div class="ip-fill danger" style="width:92%"></div></div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><p class="db-machine-name">Packing Multihead</p></td>
                <td class="text-center" style="font-weight:700;color:#1e293b">980 bag</td>
                <td class="text-center" style="font-size:.78rem;color:#94a3b8">1.000 bag</td>
                <td>
                  <div class="db-inline-prog">
                    <span class="ip-val" style="color:#10b981">98%</span>
                    <div class="ip-bar"><div class="ip-fill good" style="width:98%"></div></div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

</div>{{-- /db --}}

@endsection
