<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\p1_live_scada;
use App\Models\p1_mapping_mesin;

class PackingUnit1Controller extends Controller
{
    public function getLiveScada()
    {
        $dataLiveScada = p1_mapping_mesin::select(
            'mapping_mesin.aktif',
            'mapping_mesin.mesin_scada',
            'mapping_mesin.mesin_multiline as mesin_angka',
            'st.status_mesin',
            'st.waktu',
            'st.shift'
        )
            ->joinSub(function ($q) {
                $q->from('ls_time as lt1')
                    ->select('lt1.kode_mesin', 'lt1.status_mesin', 'lt1.waktu', 'lt1.shift')
                    ->join(DB::raw('(SELECT kode_mesin, MAX(waktu) as last_waktu FROM ls_time GROUP BY kode_mesin) as lt2'), function ($join) {
                        $join->on('lt1.kode_mesin', '=', 'lt2.kode_mesin')
                            ->on('lt1.waktu', '=', 'lt2.last_waktu');
                    });
            }, 'st', function ($join) {
                $join->on('mapping_mesin.mesin_scada', '=', 'st.kode_mesin')
                    ->orOn('mapping_mesin.mesin_multiline', '=', 'st.kode_mesin');
            })
            ->where('mapping_mesin.aktif', 1)
            ->get();
        return response()->json($dataLiveScada);
    }
    public function getRecapMachine()
    {
        $dataRecapMachine = p1_mapping_mesin::from('mapping_mesin as mm')
            ->selectRaw('
        SUM(CASE WHEN mm.aktif = 0 THEN 1 ELSE 0 END) AS mesin_nonaktif,
        SUM(CASE WHEN st.status_mesin = 1 THEN 1 ELSE 0 END) AS mesin_online,
        SUM(CASE WHEN st.status_mesin = 0 AND mm.aktif = 1 THEN 1 ELSE 0 END) AS mesin_offline,
        COUNT(mm.mesin_multiline) AS total_mesin
    ')
            ->joinSub(function ($q) {
                $q->from('ls_time as lt1')
                    ->select('lt1.kode_mesin', 'lt1.status_mesin', 'lt1.waktu')
                    ->join(DB::raw('(
                SELECT kode_mesin, MAX(waktu) AS last_waktu
                FROM ls_time
                GROUP BY kode_mesin
            ) as lt2'), function ($join) {
                        $join->on('lt1.kode_mesin', '=', 'lt2.kode_mesin')
                            ->on('lt1.waktu', '=', 'lt2.last_waktu');
                    });
            }, 'st', function ($join) {
                $join->on('mm.mesin_scada', '=', 'st.kode_mesin')
                    ->orOn('mm.mesin_multiline', '=', 'st.kode_mesin');
            })
            ->first();

        return response()->json($dataRecapMachine);
    }
}
