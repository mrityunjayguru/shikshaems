<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;

class TraccarService
{
    private $phone = "8318096514"; // your login phone
    private $jsessionId;
    private $token;

    /*
    |--------------------------------------------------------------------------
    | STEP 1 - Authenticate & Get JSESSIONID
    |--------------------------------------------------------------------------
    */
    public function authenticate()
    {
        try {

            $response = Http::withOptions([
                'cookies' => false, // VERY IMPORTANT
            ])->asForm()->post(
                "https://app.trackroutepro.com/Auth/verifyUser",
                [
                    'phone' => $this->phone
                ]
            );

            if (!$response->successful()) {
                Log::error("❌ Login failed", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            $data = $response->json();

            $this->jsessionId = $data['jsessionid'] ?? null;
            $this->token      = $data['token'] ?? null;

            if (!$this->jsessionId || !$this->token) {
                Log::error("❌ Token or JSESSIONID missing in response");
                return false;
            }

            Log::info("✅ Auth Success");
            Log::info("JSESSIONID: " . $this->jsessionId);

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Authentication error: " . $e->getMessage());
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2 - Get Token Using JSESSIONID
    |--------------------------------------------------------------------------
    */
    public function getToken()
    {
        try {

            $response = Http::withOptions([
                'cookies' => false // VERY IMPORTANT
            ])
                ->withHeaders([
                    'Cookie' => "JSESSIONID={$this->jsessionId}",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]) ->asForm()
                ->post("https://trackback.trackroutepro.com/api/session/token");

            if (!$response->successful()) {
                Log::error("❌ Token fetch failed", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            // If API returns plain text
            $this->token = trim($response->body());

            // If API returns JSON like { "token": "..." }
            // $this->token = $response->json()['token'] ?? null;

            if (!$this->token) {
                Log::error("❌ Token not found in response");
                return false;
            }

            Log::info("✅ Socket Token: " . $this->token);

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Token error: " . $e->getMessage());
            return false;
        }
    }



    /*
    |--------------------------------------------------------------------------
    | STEP 3 - Connect WebSocket
    |--------------------------------------------------------------------------
    */
    public function listen(callable $callback)
    {
        if (!$this->authenticate()) {
            return;
        }

        if (!$this->getToken()) {
            return;
        }

        while (true) {

            try {

                Log::info("📡 Connecting to WebSocket...");

                $headers = [
                    // "Cookie" => "JSESSIONID={$this->jsessionId}",
                    "Authorization" => "Bearer {$this->token}",
                    "Origin" => "https://trackback.trackroutepro.com",
                    "User-Agent" => "Mozilla/5.0"
                ];

                $client = new Client(
                    "wss://trackback.trackroutepro.com/api/socket",
                    [
                        'headers' => $headers,
                        'timeout' => 60
                    ]
                );

                Log::info("✅ WebSocket Connected");

                while (true) {

                    $message = $client->receive();

                    if (!$message) {
                        continue;
                    }

                    $data = json_decode($message, true);

                    if (isset($data['positions'])) {
                        foreach ($data['positions'] as $position) {
                            $callback($position);
                        }
                    }
                }
            } catch (\Exception $e) {

                Log::error("❌ Socket error: " . $e->getMessage());
                Log::info("🔁 Reconnecting in 5 seconds...");
                sleep(5);
            }
        }
    }
}
