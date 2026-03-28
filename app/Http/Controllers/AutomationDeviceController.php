<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\p1_ip_mesin;
use App\Models\p1_ip_timbangan;
use App\Models\p2_ip_mesin;
use App\Models\p2_ip_timbangan;

class AutomationDeviceController extends Controller
{
    /**
     * Cek apakah device online.
     * Strategi:
     *   1. Coba koneksi ke port 80 (web-based device).
     *   2. Jika gagal, fallback ke ping — untuk device yang tidak punya web
     *      tapi masih bisa di-ping (PLC, timbangan, dsb.).
     */
    private function isDeviceOnline(string $ip): bool
    {
        // ── Step 1: port 80 check (timeout 2 detik) ──────────────────────────
        try {
            $conn = @fsockopen($ip, 80, $errno, $errstr, 2);
            if ($conn) {
                fclose($conn);
                return true;
            }
        } catch (\Exception $e) {
            // lanjut ke ping
        }

        // ── Step 2: ICMP ping fallback ────────────────────────────────────────
        // Windows: ping -n 1 -w 500 <ip>   (w = timeout ms)
        // Linux  : ping -c 1 -W 1  <ip>
        try {
            $os  = strtoupper(substr(PHP_OS, 0, 3));
            $cmd = ($os === 'WIN')
                ? "ping -n 1 -w 500 {$ip}"
                : "ping -c 1 -W 1 {$ip}";

            exec($cmd, $output, $exitCode);
            return $exitCode === 0;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getDataMesinP1()
    {
        $devices = p1_ip_mesin::select('ip', 'kode_mesin')->get();

        $result = [];

        foreach ($devices as $device) {

            $ip = $device->ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'kode_mesin' => $device->kode_mesin,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataTimbanganP1()
    {
        $devices = p1_ip_timbangan::select('ip', 'device')->get();

        $result = [];

        foreach ($devices as $device) {

            $ip = $device->ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'device' => $device->device,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataHormannDCOut()
    {
        $devices = DB::connection('history_hormann')
            ->table('controldcout')
            ->select([
            DB::raw("
            CASE
                WHEN IP_Pintu = '10.252.10.81' THEN 'Pintu A16-A21'
                WHEN IP_Pintu = '10.252.10.84' THEN 'Pintu B16-B21'
                WHEN IP_Pintu = '10.252.10.85' THEN 'Pintu C16-C21'
            END AS area
        "),
            'IP_Pintu as Ip'
        ])
            ->distinct()
            ->get();


        foreach ($devices as $device) {

            $ip = $device->Ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'area' => $device->area,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataHormannWHP2()
    {
        $devices = DB::connection('history_hormann')
            ->table('controlp2wh')
            ->selectRaw('MIN(Area_Pintu) as area, IP_Pintu as Ip')
            ->groupBy('IP_Pintu')
            ->get();

        foreach ($devices as $device) {

            $ip = $device->Ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'area' => $device->area,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataHormannWHP1()
    {
        $devices = DB::connection('history_hormann')
            ->table('controlp1wh')
            ->select('Area_Pintu as area', 'IP_Pintu as Ip')
            ->distinct()
            ->get();

        foreach ($devices as $device) {

            $ip = $device->Ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'area' => $device->area,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataHormannDCIn()
    {
        $devices = DB::connection('history_hormann')
            ->table('controldcin')
            ->select([
            DB::raw("
            CASE
                WHEN IP_Pintu = '10.252.10.152' THEN 'Pintu MOI A12'
                WHEN IP_Pintu = '10.252.10.158' THEN 'Pintu A13-A15'
                WHEN IP_Pintu = '10.252.10.83' THEN 'Pintu BC13-BC15'
            END AS area
        "),
            'IP_Pintu as Ip'
        ])
            ->distinct()
            ->get();

        foreach ($devices as $device) {

            $ip = $device->Ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'area' => $device->area,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }

        return response()->json($result);
    }
    public function getDataMesinP2()
    {
        $devices = p2_ip_mesin::select('ip', 'mesin')->get();

        $result = [];

        foreach ($devices as $device) {

            $ip = $device->ip;
            $isOnline = $this->isDeviceOnline($ip);

            $result[] = [
                'kode_mesin' => $device->mesin,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }
        return response()->json($result);
    }
    public function getDataTimbanganP2()
    {
        $devices = p2_ip_timbangan::select('ip', 'device')->get();

        $result = [];

        foreach ($devices as $device) {

            $ip = $device->ip;
            $port = 80;

            $isOnline = false;

            try {
                $conn = @fsockopen($ip, $port, $errno, $errstr, 2);
                if ($conn) {
                    fclose($conn);
                    $isOnline = true;
                }
            }
            catch (\Exception $e) {
                $isOnline = false;
            }

            $result[] = [
                'device' => $device->device,
                'ip' => $ip,
                'online' => $isOnline
            ];
        }
        return response()->json($result);
    }
    public function getRecapDevice()
    {
        return response()->json([
            'rekap' => [
                'total_device' => 0,
                'online_device' => 0,
                'offline_device' => 0,
            ]
        ]);
    }
}
