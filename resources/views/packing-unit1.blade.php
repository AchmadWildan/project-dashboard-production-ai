@extends('layouts.user_type.auth')

@section('content')

<div class="row">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Machine Online</p>
              <h5 class="font-weight-bolder mb-0" id="onlineMachine"></h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Machine Offline</p>
              <h5 class="font-weight-bolder mb-0" id="offlineMachine"></h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Machine Retire</p>
              <h5 class="font-weight-bolder mb-0" id="retireMachine"></h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Machine</p>
              <h5 class="font-weight-bolder mb-0" id="totalMachine"></h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
              <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div class="row mt-4">
  <div class="col-lg-12">
    <div class="card z-index-2">
      <div class="card-header pb-0">
        <h6>Finish Good P1 (Jumlah Karton)</h6> 
        <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
            <span class="font-weight-bold">4% more</span> in 2021
          </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="finishGoodP1" class="chart-canvas" height="300" data-url="{{ route('dashboard.finishGoodP1') }}"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>   -->
<!-- <div class="row mt-4">
  <div class="col-lg-12">
    <div class="card z-index-2">
      <div class="card-header pb-0">
        <h6>Finish Good P2 (Jumlah Karton)</h6> 
        <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
            <span class="font-weight-bold">4% more</span> in 2021
          </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="finishGoodP2" class="chart-canvas" height="300" data-url="{{ route('dashboard.finishGoodP2') }}"></canvas>
        </div>
      </div>
    </div>
  </div>
</div> -->
<div class="row my-4">
  <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <div class="row">
          <div class="col-lg-6 col-7">
            <h6>Projects</h6>
            <p class="text-sm mb-0">
              <i class="fa fa-check text-info" aria-hidden="true"></i>
              <span class="font-weight-bold ms-1">30 done</span> this month
            </p>
          </div>
          <div class="col-lg-6 col-5 my-auto text-end">
            <div class="dropdown float-lg-end pe-4">
              <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v text-secondary"></i>
              </a>
              <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="table-responsive">
          <table id="tabel-mesin" class="table display align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Real</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Machine</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Time (ON/OFF)</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cycle</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">State</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-lg-4 col-md-6">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6>Orders overview</h6>
        <p class="text-sm">
          <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
          <span class="font-weight-bold">24%</span> this month
        </p>
      </div>
      <div class="card-body p-3">
        <div class="timeline timeline-one-side">
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-bell-55 text-success text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 PM</p>
            </div>
          </div>
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-html5 text-danger text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">New order #1832412</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 11 PM</p>
            </div>
          </div>
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-cart text-info text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">Server payments for April</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 9:34 PM</p>
            </div>
          </div>
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-credit-card text-warning text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">New card added for order #4395133</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">20 DEC 2:20 AM</p>
            </div>
          </div>
          <div class="timeline-block mb-3">
            <span class="timeline-step">
              <i class="ni ni-key-25 text-primary text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">Unlock packages for development</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">18 DEC 4:54 AM</p>
            </div>
          </div>
          <div class="timeline-block">
            <span class="timeline-step">
              <i class="ni ni-money-coins text-dark text-gradient"></i>
            </span>
            <div class="timeline-content">
              <h6 class="text-dark text-sm font-weight-bold mb-0">New order #9583120</h6>
              <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">17 DEC</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
</div>

@endsection
@push('dashboard')
<script src="{{ asset('js/packing-unit1.js') }}"></script>
@endpush