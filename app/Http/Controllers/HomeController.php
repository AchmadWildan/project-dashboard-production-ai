<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\p1_kartoning_wd;
use App\Models\p2_kartoning_wd;
use App\Models\p1_live_scada;
use App\Models\p1_mapping_mesin;
use App\Models\p1_counter_scada;
use App\Models\p1_ls_time;

class HomeController extends Controller
{
    public function home()
    {
        return redirect('dashboard');
    }

    public function getProductivity(Request $request)
    {
        $master_mesin = p1_mapping_mesin::select(
            'mesin_scada',
            'mesin_multiline',
        )
            ->where('aktif', 1)
            ->get()
            ->pluck('mesin_scada', 'mesin_multiline')
            ->toArray();

        $mesin_scada = array_values($master_mesin);
        $mesin_multi = array_keys($master_mesin);

        $live_scada = p1_live_scada::select('Kode_mesin', 'counter', 'status_mesin')
            ->whereIn('Kode_mesin', $mesin_scada)
            ->get()
            ->toArray();


        $tanggalShift = '2026-03-04';
        $waktuShift = '2026-03-06 07:00:00';
        $shift = 'SHIFT 1';

        // Counter aktual mesin multiline
        $subqueryB = p1_counter_scada::select('kode_mesin', DB::raw('SUM(counter_mesin) as total_counter'))
            ->where('tglshift', $tanggalShift)
            ->where('shift', $shift)
            ->groupBy('kode_mesin');

        $getActualCounterP1Multi = p1_live_scada::query()->from('live_scada as a') // Mendefinisikan alias 'a'
            ->joinSub($subqueryB, 'b', function ($join) {
                $join->on('a.Kode_mesin', '=', 'b.kode_mesin');
            })
            ->join('mapping_mesin as mm', 'a.Kode_mesin', '=', 'mm.mesin_scada')
            ->where('mm.aktif', 1)
            ->selectRaw("
                a.Kode_mesin,
                a.counter AS counter_live,
                a.status_mesin,
                b.total_counter AS counter_sebelum,
                (a.counter - b.total_counter) AS counter_aktual,
                NOW() AS created_at
            ")
            ->get();
        // dd($mesin_scada);
        // Hitung operating time
        $statusLogs = p1_ls_time::query()
            ->wherein('kode_mesin', $mesin_scada)
            ->where('waktu', '>=', $waktuShift)
            ->orderBy('waktu', 'asc')
            ->get()
            ->toArray();

        $totalWaktuOperasional = 0;
        $lastRunningTime = null;
            // dd($statusLogs);
        foreach ($statusLogs as $log) {
            $logTime = new Carbon($log->waktu);
            if ($log->status_mesin === '1') {
                $lastRunningTime = $logTime;
            } elseif ($log->status_mesin === '0' && $lastRunningTime) {
                $totalWaktuOperasional += $lastRunningTime->diffInMinutes($logTime);
                $lastRunningTime = null; // Reset setelah dihitung
            }
        }

        // // Jika status terakhir adalah 'running' dan belum berhenti sampai sekarang
        if ($lastRunningTime) {
            $totalWaktuOperasional += $lastRunningTime->diffInMinutes(Carbon::now());
        }
        return response()->json($lastRunningTime);
    }
}
