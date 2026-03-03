<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use App\Models\GPS;
use App\Models\RouteVehicleHistory;
use App\Services\SimpleTripTrackingService;
use Carbon\Carbon;

class TraccarHttpListener extends Command
{
    protected $signature = 'traccar:http-listen {--school_id=} {--interval=5}';
    protected $description = 'HTTP polling based Traccar listener (fallback for WebSocket issues)';

    private $baseUrl;
    private $tripService;
    private $lastPositions = [];
    private $lastBroadcastTime = []; // Track last broadcast time per trip

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = rtrim(str_replace('wss://', 'https://', env('TRACCAR_SOCKET_URL', 'https://trackback.trackroutepro.com')), '/api/socket');
        $this->tripService = app(SimpleTripTrackingService::class);
    }

    public function handle()
    {
        $schoolId = $this->option('school_id');
        $interval = (int) $this->option('interval'); // seconds

        $this->info("🚀 Starting HTTP Polling Traccar Listener (Interval: {$interval}s)...");
        $this->info("🌐 Base URL: {$this->baseUrl}");

        while (true) {
            try {
                if ($schoolId) {
                    $school = School::find($schoolId);
                    if ($school) {
                        $this->pollSchool($school);
                    }
                } else {
                    $this->pollAllSchools();
                }

                sleep($interval);

            } catch (\Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                Log::error('HTTP Listener Error:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                sleep($interval);
            }
        }
    }

    /**
     * Poll all schools with active trips
     */
    private function pollAllSchools()
    {
        $activeSchools = $this->getSchoolsWithActiveTrips();

        if ($activeSchools->isEmpty()) {
            $this->info("⏳ [" . now()->format('H:i:s') . "] No active trips found. Waiting...");
            return;
        }

        $this->info("📡 [" . now()->format('H:i:s') . "] Polling " . $activeSchools->count() . " schools with active trips");

        foreach ($activeSchools as $school) {
            $this->pollSchool($school);
        }
    }

    /**
     * Poll specific school's Traccar data
     */
    private function pollSchool($school)
    {
        try {
            $this->info("🔍 Polling: {$school->name} (ID: {$school->id})");

            // Get session
            $sessionId = $school->getTraccarSession();

            if (!$sessionId) {
                $this->warn("⚠️ Failed to get Traccar session for: {$school->name}");
                return;
            }

            // Fetch devices
            $devicesResponse = Http::withHeaders([
                'Cookie' => "JSESSIONID={$sessionId}"
            ])->get("{$this->baseUrl}/api/devices");
            //   $this->error($devicesResponse) ;    
            if (!$devicesResponse->successful()) {
                $this->error("❌ Failed to fetch devices for {$school->name}: " . $devicesResponse->status());
                return;
            }

            $devices = $devicesResponse->json();
            $this->processDevices($school, $devices);

            // Fetch positions
            $positionsResponse = Http::withHeaders([
                'Cookie' => "JSESSIONID={$sessionId}"
            ])->get("{$this->baseUrl}/api/positions");

            if (!$positionsResponse->successful()) {
                $this->error("❌ Failed to fetch positions for {$school->name}: " . $positionsResponse->status());
                return;
            }

            $positions = $positionsResponse->json();
            
            if (empty($positions)) {
                $this->warn("⚠️ No positions returned for {$school->name}");
            } else {
                $this->info("📍 Found " . count($positions) . " positions for {$school->name}");
            }
            
            $this->processPositions($school, $positions);

        } catch (\Throwable $e) {
            $this->error("❌ Error polling {$school->name}: " . $e->getMessage());
            Log::error('School Polling Error:', [
                'school_id' => $school->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get schools with active trips
     */
    private function getSchoolsWithActiveTrips()
    {
        $schoolIds = [];
        
        $schools = School::whereNotNull('traccar_phone')
            ->where('status', 1)
            ->get();

        $this->info("🔍 Checking " . $schools->count() . " schools for active trips...");

        foreach ($schools as $school) {
            try {
                $this->switchToSchoolDatabase($school);
                
                $hasActiveTrips = RouteVehicleHistory::where('tracking', 1)
                    ->where('status', 'inprogress')
                    ->whereDate('date', today())
                    ->exists();

                if ($hasActiveTrips) {
                    $tripCount = RouteVehicleHistory::where('tracking', 1)
                        ->where('status', 'inprogress')
                        ->whereDate('date', today())
                        ->count();
                    $this->info("✅ School {$school->name} (ID: {$school->id}) has {$tripCount} active trip(s)");
                    $schoolIds[] = $school->id;
                } else {
                    $this->info("⏭️  School {$school->name} (ID: {$school->id}) has no active trips");
                }
            } catch (\Exception $e) {
                Log::warning("Error checking trips for school {$school->id}: " . $e->getMessage());
            }
        }

        DB::setDefaultConnection('mysql');
        
        $this->info("📊 Total schools with active trips: " . count($schoolIds));
        
        return School::whereIn('id', $schoolIds)->get();
    }

    /**
     * Process devices
     */
    private function processDevices($school, $devices)
    {
        foreach ($devices as $device) {
            $name = $device['name'] ?? 'Unknown';
            $imei = $device['uniqueId'] ?? null;
            $status = $device['status'] ?? 'unknown';

            $this->info("📡 [{$school->name}] Device: {$name} (IMEI: {$imei}, Status: {$status})");
        }
    }

    /**
     * Process positions
     */
    private function processPositions($school, $positions)
    {
        $this->info("📦 Processing " . count($positions) . " positions for {$school->name}");
        
        $this->switchToSchoolDatabase($school);

        foreach ($positions as $position) {
            try {
                $deviceId = $position['deviceId'] ?? null;
                $positionId = $position['id'] ?? null;

                // Get device info
                $deviceResponse = Http::withHeaders([
                    'Cookie' => "JSESSIONID=" . $school->getTraccarSession()
                ])->get("{$this->baseUrl}/api/devices/{$deviceId}");

                if (!$deviceResponse->successful()) {
                    continue;
                }

                $device = $deviceResponse->json();
                $imei = $device['uniqueId'] ?? null;

                if (!$imei) {
                    continue;
                }

                // Extract position data
                $latitude = $position['latitude'];
                $longitude = $position['longitude'];
                $speed = round(($position['speed'] ?? 0) * 1.852, 2); // knots to km/h
                $deviceTime = Carbon::parse($position['deviceTime'])->format('Y-m-d H:i:s');
                $attributes = $position['attributes'] ?? [];

                $this->info("📍 [{$school->name}] {$device['name']} - Lat: {$latitude}, Lng: {$longitude}, Speed: {$speed} km/h");

                // Find GPS device
                $gps = GPS::where('imei_no', $imei)->first();

                if (!$gps) {
                    continue;
                }

                // Find active trip
                $activeTrip = RouteVehicleHistory::where('vehicle_id', $gps->assigned_to)
                    ->where('tracking', 1)
                    ->where('status', 'inprogress')
                    ->whereDate('date', today())
                    ->first();

                if (!$activeTrip) {
                    continue;
                }

                $this->info("🚌 [{$school->name}] Active Trip ID: {$activeTrip->id}");

                // Check if we should broadcast (based on time interval, not position change)
                $tripKey = "{$school->id}_{$activeTrip->id}";
                $now = time();
                $interval = (int) $this->option('interval');
                
                if (isset($this->lastBroadcastTime[$tripKey])) {
                    $timeSinceLastBroadcast = $now - $this->lastBroadcastTime[$tripKey];
                    if ($timeSinceLastBroadcast < $interval) {
                        $this->info("⏭️  Skipping broadcast (last broadcast {$timeSinceLastBroadcast}s ago, interval: {$interval}s)");
                        continue;
                    }
                }
                
                // Update last broadcast time
                $this->lastBroadcastTime[$tripKey] = $now;

                // IMPORTANT: Ensure we're on the school database before calling processGPSData
                $this->switchToSchoolDatabase($school);
                
                // Process through trip tracking service
                $this->tripService->processGPSData(
                    $activeTrip->id,
                    $latitude,
                    $longitude,
                    $speed,
                    $deviceTime,
                    $attributes
                );
                
                $this->info("✅ [{$school->name}] Broadcasted update for Trip {$activeTrip->id}");

            } catch (\Throwable $e) {
                $this->error("❌ Position processing error: " . $e->getMessage());
            }
        }

        DB::setDefaultConnection('mysql');
    }

    /**
     * Switch to school database
     */
    private function switchToSchoolDatabase($school)
    {
        DB::setDefaultConnection('school');
        Config::set('database.connections.school.database', $school->database_name);
        DB::purge('school');
        DB::connection('school')->reconnect();
        DB::setDefaultConnection('school');
    }
}
