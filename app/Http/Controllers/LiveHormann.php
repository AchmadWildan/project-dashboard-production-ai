<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveHormann extends Controller
{
    public function getLiveWhUnit1()
    {
        $data = DB::connection('history_hormann')
            ->table('mjsupdatepintua as a')
            ->select('a.Pintu', 'a.Status')
            ->unionAll(
                DB::connection('history_hormann')
                    ->table('mjsupdatepintub as b')
                    ->select('b.Pintu', 'b.Status')
            )
            ->unionAll(
                DB::connection('history_hormann')
                    ->table('mjsupdatepintuc as c')
                    ->select('c.Pintu', 'c.Status')
            )
            ->get();

        return response()->json($data);
    }
    public function getLiveWhUnit2()
    {
        $dataA = DB::connection('history_hormann')
            ->table('bgsupdatepintua as a')
            ->select('a.Pintu', 'a.Status')
            ->where('a.id', '<>', 12);

        $dataB = DB::connection('history_hormann')
            ->table('bgsupdatepintub as b')
            ->select('b.Pintu', 'b.Status');

        $dataC = DB::connection('history_hormann')
            ->table('bgsupdatepintuc as c')
            ->select('c.Pintu', 'c.Status');

        $data = $dataA
            ->unionAll($dataB)
            ->unionAll($dataC)
            ->get();

        return response()->json($data);
    }
    public function getLiveDC()
    {
        $dataA = DB::connection('history_hormann')
            ->table('dcupdatepintua as a')
            ->select('a.Pintu', 'a.Status');

        $dataB = DB::connection('history_hormann')
            ->table('dcupdatepintub as b')
            ->select('b.Pintu', 'b.Status');

        $dataC = DB::connection('history_hormann')
            ->table('dcupdatepintuc as c')
            ->select('c.Pintu', 'c.Status');

        $data = $dataA
            ->unionAll($dataB)
            ->unionAll($dataC)
            ->get();

        return response()->json($data);
    }
}
