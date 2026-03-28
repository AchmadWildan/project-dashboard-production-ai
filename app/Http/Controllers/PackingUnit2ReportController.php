<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\p1_kartoning_wd;
use App\Models\p2_kartoning_wd;

class PackingUnit1ReportController extends Controller
{
    public function home()
    {
        return redirect('dashboard');
    }

    public function finishGoodP1()
    {
        $data = p1_kartoning_wd::selectRaw('
            DATE_FORMAT(tgl_shift, "%b") as bulan,
            SUM(CASE WHEN kode_item = "LADA_A" THEN idkarton ELSE 0 END) as line_a,
            SUM(CASE WHEN kode_item = "LADA_B" THEN idkarton ELSE 0 END) as line_b,
            SUM(CASE WHEN kode_item = "KUNYIT" THEN idkarton ELSE 0 END) as kunyit
        ')
            ->whereYear('tgl_shift', 2026)
            ->whereIn('kode_item', ['LADA_A', 'LADA_B', 'KUNYIT'])
            ->groupBy('bulan')
            ->orderByRaw('MIN(tgl_shift)')
            ->get();

        $labels = $data->pluck('bulan');
        $lineA = $data->pluck('line_a')->map(fn($val) => (int)$val);
        $lineB = $data->pluck('line_b')->map(fn($val) => (int)$val);
        $kunyit = $data->pluck('kunyit')->map(fn($val) => (int)$val);
        // dd($lineA->first());
        return response()->json([
            'labels' => $labels,
            'lineA' => $lineA,
            'lineB' => $lineB,
            'kunyit' => $kunyit,
        ]);
    }

    public function finishGoodP2()
    {
        $data = p2_kartoning_wd::selectRaw('
                    DATE_FORMAT(tgl_shift, "%b") as bulan,
                    SUM(CASE WHEN kode_item = "KUNYIT" THEN idkarton ELSE 0 END) as kunyit,
                    SUM(CASE WHEN kode_item = "KETUMBAR" THEN idkarton ELSE 0 END) as ketumbar,
                    SUM(CASE WHEN kode_item = "KEMASAN BAWANG PUTIH" THEN idkarton ELSE 0 END) as baput,
                    SUM(CASE WHEN kode_item = "MARINASI" THEN idkarton ELSE 0 END) as marinasi,
                    SUM(CASE WHEN kode_item LIKE "%POUCH%" THEN idkarton ELSE 0 END) as pouch,
                    SUM(CASE WHEN kode_item LIKE "%DISPLAY%" THEN idkarton ELSE 0 END) as display,
                    SUM(
                        CASE 
                            WHEN kode_item NOT IN ("KUNYIT", "KETUMBAR", "KEMASAN BAWANG PUTIH", "MARINASI") 
                                AND kode_item NOT LIKE "%POUCH%" 
                                AND kode_item NOT LIKE "%DISPLAY%" 
                            THEN idkarton ELSE 0 
                        END
                    ) AS seasoning
                ')
            ->whereYear('tgl_shift', 2026)
            ->groupBy('bulan')
            ->orderByRaw('MIN(tgl_shift)')
            ->get();

        $labels = $data->pluck('bulan');
        $kunyit = $data->pluck('kunyit')->map(fn($val) => (int)$val);
        $ketumbar = $data->pluck('ketumbar')->map(fn($val) => (int)$val);
        $baput = $data->pluck('baput')->map(fn($val) => (int)$val);
        $marinasi = $data->pluck('marinasi')->map(fn($val) => (int)$val);
        $pouch = $data->pluck('pouch')->map(fn($val) => (int)$val);
        $display = $data->pluck('display')->map(fn($val) => (int)$val);
        $seasoning = $data->pluck('seasoning')->map(fn($val) => (int)$val);
        // dd($data);
        return response()->json([
            'labels' => $labels,
            'kunyit' => $kunyit,
            'ketumbar' => $ketumbar,
            'baput' => $baput,
            'marinasi' => $marinasi,
            'pouch' => $pouch,
            'display' => $display,
            'seasoning' => $seasoning,
        ]);
    }
}
