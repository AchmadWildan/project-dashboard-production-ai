<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\p2_livecounter;

class PackingUnit2Controller extends Controller
{
    public function getLiveCounter()
    {
        $dataLiveScada = p2_livecounter::select(
            'mesin',
            'status_mesin',
            'updated_at',
        )->get();
        return response()->json($dataLiveScada);
    }
    public function getRecapMachine()    {
        $data = p2_livecounter::selectRaw("
        SUM(CASE WHEN status_mesin = 'ON' THEN 1 ELSE 0 END) AS mesin_on,
        SUM(CASE WHEN status_mesin = 'OFF' OR status_mesin = 'Loss Connection' THEN 1 ELSE 0 END) AS mesin_off,
        SUM(CASE WHEN aktif = 1 THEN 1 ELSE 0 END) AS mesin_aktif,
        SUM(CASE WHEN aktif = 0 THEN 1 ELSE 0 END) AS mesin_nonaktif
    ")->first();

        return response()->json($data);    }

}
