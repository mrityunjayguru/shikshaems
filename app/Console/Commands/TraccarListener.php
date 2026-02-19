<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebSocket\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RouteVehicleHistory;
use Illuminate\Support\Facades\Log;

class TraccarListener extends Command
{
    protected $signature = 'traccar:listen';
    protected $description = 'Permanent TrackRoutePro GPS listener';

    private $phone;
    private $host;
    private $client;

    public function __construct()
    {
        parent::__construct();

        $this->phone = config('services.traccar.phone');
        $this->host  = config('services.traccar.socket_url');
    }

    // public function handle()
    // {
    //     while (true) {

    //         try {

    //             $this->loginAndConnect();

    //             while (true) {

    //                 $message = $this->client->receive();
    //                 $data = json_decode($message, true);
    //                 Log::info($data);
    //                 $deviceMap = [];

    //                 if (isset($data['devices'])) {
    //                     foreach ($data['devices'] as $dev) {
    //                         $deviceMap[$dev['id']] = $dev['uniqueId']; // IMEI
    //                     }
    //                 }

    //                 if (!isset($data['positions'])) continue;

    //                 foreach ($data['positions'] as $pos) {

    //                     $deviceId = $pos['deviceId'] ?? null;

    //                     if (!$deviceId || !isset($deviceMap[$deviceId])) {
    //                         $this->warn("Device not mapped: " . $deviceId);
    //                         continue;
    //                     }

    //                     $imei = $deviceMap[$deviceId]; // REAL IMEI

    //                     if (!$imei) continue;

    //                     $gps = DB::table('g_p_s')->where('imei_no', $imei)->first();
    //                     if (!$gps) {
    //                         $this->warn("GPS not found for IMEI: " . $imei);
    //                         continue;
    //                     }

    //                     $trip = RouteVehicleHistory::where('vehicle_id', $gps->assigned_to)
    //                         ->where('tracking', 1)
    //                         ->first();

    //                     if (!$trip) continue;

    //                     DB::table('bus_locations')->insert([
    //                         'device_id'   => $imei,
    //                         'trip_id'     => $trip->id,
    //                         'device_time' => Carbon::parse($pos['deviceTime'])->format('Y-m-d H:i:s'),
    //                         'latitude'    => $pos['latitude'],
    //                         'longitude'   => $pos['longitude'],
    //                         'speed'       => $pos['speed'] ?? 0,
    //                         'created_at'  => now(),
    //                         'updated_at'  => now(),
    //                     ]);

    //                     $this->info("📍 Trip {$trip->id} updated");
    //                 }
    //             }
    //         } catch (\Throwable $e) {

    //             $this->error("Socket closed: " . $e->getMessage());
    //             $this->info("Re-login in 5 seconds...");
    //             sleep(5);
    //         }
    //     }
    // }

    public function handle()
    {
        while (true) {

            try {

                $this->info("🔌 Connecting to Traccar...");
                $this->loginAndConnect();

                $deviceMap = []; // deviceId => IMEI

                while (true) {
                    $message = $this->client->receive();
                    $data = json_decode($message, true);

                    if (!$data) {
                        $this->warn("Invalid JSON received");
                        continue;
                    }

                    /*
                |--------------------------------------------------------------------------
                | STEP 1 — Capture Devices (contains IMEI mapping)
                |--------------------------------------------------------------------------
                */
                    if (isset($data['devices'])) {

                        foreach ($data['devices'] as $dev) {
                            $deviceMap[$dev['id']] = $dev['uniqueId']; // IMEI
                            $this->info("📡 Device mapped: {$dev['id']} => {$dev['uniqueId']}");
                        }

                        continue;
                    }

                    /*
                |--------------------------------------------------------------------------
                | STEP 2 — Process Positions (Live GPS)
                |--------------------------------------------------------------------------
                */
                    if (!isset($data['positions'])) {
                        continue;
                    }

                    foreach ($data['positions'] as $pos) {

                        $deviceId = $pos['deviceId'] ?? null;

                        if (!$deviceId || !isset($deviceMap[$deviceId])) {
                            $this->warn("❌ Unknown deviceId: " . $deviceId);
                            continue;
                        }

                        // REAL IMEI
                        $imei = $deviceMap[$deviceId];

                        /*
                    |--------------------------------------------------------------------------
                    | STEP 3 — Find GPS in our DB
                    |--------------------------------------------------------------------------
                    */
                        $gps = DB::table('g_p_s')->where('imei_no', $imei)->first();

                        if (!$gps) {
                            $this->warn("❌ GPS not found in DB for IMEI: " . $imei);
                            continue;
                        }

                        /*
                    |--------------------------------------------------------------------------
                    | STEP 4 — Find Active Trip
                    |--------------------------------------------------------------------------
                    */
                        $trip = RouteVehicleHistory::where('vehicle_id', $gps->assigned_to)
                            ->where('tracking', 1)
                            ->first();

                        if (!$trip) {
                            $this->warn("❌ No active trip for vehicle: " . $gps->assigned_to);
                            continue;
                        }

                        /*
                    |--------------------------------------------------------------------------
                    | STEP 5 — Insert Location
                    |--------------------------------------------------------------------------
                    */
                        try {

                            DB::table('bus_locations')->insert([
                                'device_id'   => $imei,
                                'trip_id'     => $trip->id,
                                'device_time' => \Carbon\Carbon::parse($pos['deviceTime'])->format('Y-m-d H:i:s'),
                                'latitude'    => $pos['latitude'],
                                'longitude'   => $pos['longitude'],
                                'speed'       => $pos['speed'] ?? 0,
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ]);

                            $this->info("📍 Trip {$trip->id} updated | IMEI: {$imei}");
                        } catch (\Throwable $e) {
                            $this->error("DB Insert Failed: " . $e->getMessage());
                        }
                    }
                }
            } catch (\Throwable $e) {

                $this->error("❌ Socket closed: " . $e->getMessage());
                $this->info("🔄 Reconnecting in 5 seconds...");
                sleep(5);
            }
        }
    }


    private function loginAndConnect()
    {
        $this->info("🔐 Logging into TrackRoutePro...");

        $response = Http::asForm()->post(
            "https://app.trackroutepro.com/Auth/verifyUser",
            ['phone' => $this->phone]
        );

        if (!$response->successful()) {
            throw new \Exception("Login failed");
        }

        $session = $response->json()['jsessionid'];

        $this->info("✅ Session received");

        $this->client = new Client($this->host, [
            'headers' => ['Cookie' => "JSESSIONID=$session"],
            'timeout' => 120
        ]);

        $this->info("🚀 Socket Connected");
    }
}
