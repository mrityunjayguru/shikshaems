<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use WebSocket\Client;
use Exception;

class ListenTraccarSocket extends Command
{
    protected $signature = 'traccar:listen';
    protected $description = 'Listen to Traccar GPS WebSocket 24/7';

    public function handle()
    {
        while (true) {

            try {

                $this->info("Step 1: Verifying phone...");

                // 🔹 STEP 1 — VERIFY USER (GET JSESSIONID)
                $verifyResponse = Http::post(
                    'https://app.trackroutepro.com/Auth/verifyUser',
                    [
                        'phone' => config('services.traccar.phone'),
                    ]
                );

                if (!$verifyResponse->successful()) {
                    throw new Exception("verifyUser failed");
                }

                $verifyData = $verifyResponse->json();

                $jsessionId =
                    $verifyData['jsessionid']
                    ?? $verifyData['JSESSIONID']
                    ?? $verifyData['data']['jsessionid']
                    ?? null;

                if (!$jsessionId) {
                    throw new Exception("JSESSIONID not found in verify response");
                }

                $this->info("JSESSIONID received");

                // 🔹 STEP 2 — GET TOKEN USING COOKIE
                $this->info("Step 2: Requesting token...");

                $tokenResponse = Http::withHeaders([
                    'Cookie' => 'JSESSIONID=' . $jsessionId,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->asForm()->post(
                    'https://trackback.trackroutepro.com/api/session/token'
                );
                // $token = trim($tokenResponse->body());
                // dd($token);
                if (!$tokenResponse->successful()) {
                    throw new Exception("Token API failed");
                }

                $token = trim($tokenResponse->body())?? null;

                if (!$token) {
                    throw new Exception("Token not found in response");
                }

                $this->info("Token received");

                // 🔹 STEP 3 — CONNECT WEBSOCKET
                $this->info("Connecting to WebSocket...");

                $client = new Client(
                    "wss://trackback.trackroutepro.com/api/socket",
                    [
                        'headers' => [
                            'Authorization: Bearer ' . $token
                        ]
                    ]
                );

                $this->info("WebSocket connected. Listening...");

                // 🔹 STEP 4 — LISTEN FOREVER
                while (true) {

                    $message = $client->receive();
                    $data = json_decode($message, true);

                    if (isset($data['type']) && $data['type'] === 'position') {
                        $this->processPosition($data);
                    }
                }
            } catch (Exception $e) {

                $this->error("Connection lost: " . $e->getMessage());
                $this->info("Reconnecting in 5 seconds...");
                sleep(5);
            }
        }
    }

    protected function processPosition($data)
    {
        // 🔹 Save GPS Data (Example)
        \Log::info('GPS Position', $data);

        // Example DB insert (optional)
        /*
        \App\Models\BusLocation::create([
            'device_id' => $data['deviceId'] ?? null,
            'latitude'  => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'speed'     => $data['speed'] ?? null,
            'recorded_at' => now(),
        ]);
        */
    }
}
