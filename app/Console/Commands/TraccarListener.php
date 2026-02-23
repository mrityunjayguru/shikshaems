<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebSocket\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use App\Models\GPS;
use App\Models\RouteVehicleHistory;
use App\Services\SimpleTripTrackingService;
use Carbon\Carbon;

class TraccarListener extends Command
{
    protected $signature = 'traccar:listen {--school_id=}';
    protected $description = 'Multi-school Traccar GPS listener with real-time trip tracking';

    private $socketUrl;
    private $schoolConnections = [];
    private $deviceMap = [];
    private $tripService;

    public function __construct()
    {
        parent::__construct();
        $this->socketUrl = env('TRACCAR_SOCKET_URL', 'wss://trackback.trackroutepro.com/api/socket');
        $this->tripService = app(SimpleTripTrackingService::class);
    }

    public function handle()
    {
        $schoolId = $this->option('school_id');

        if ($schoolId) {
            // Listen for specific school
            $this->listenForSchool($schoolId);
        } else {
            // Listen for all schools with active trips
            $this->listenForAllSchools();
        }
    }

    /**
     * Listen for all schools with active trips
     */
    private function listenForAllSchools()
    {
        $this->info("🚀 Starting Multi-School Traccar Listener...");

        while (true) {
            try {
                // Get all schools with active trips
                $activeSchools = $this->getSchoolsWithActiveTrips();

                if ($activeSchools->isEmpty()) {
                    $this->info("⏳ No active trips found. Waiting 30 seconds...");
                    sleep(30);
                    continue;
                }

                $this->info("📡 Found " . $activeSchools->count() . " schools with active trips");

                // Connect to each school's Traccar
                foreach ($activeSchools as $school) {
                    $this->connectToSchool($school);
                }

                // Listen to all connections
                $this->listenToAllConnections();

            } catch (\Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                Log::error('Multi-School Listener Error:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                sleep(5);
            }
        }
    }

    /**
     * Get schools with active trips
     */
    private function getSchoolsWithActiveTrips()
    {
        $schoolIds = [];
        
        // Query each school database for active trips
        $schools = School::whereNotNull('traccar_phone')
            ->where('status', 1)
            ->get();

        foreach ($schools as $school) {
            try {
                $this->switchToSchoolDatabase($school);
                
                $hasActiveTrips = RouteVehicleHistory::where('tracking', 1)
                    // ->whereNull('end_time')
                    ->exists();

                if ($hasActiveTrips) {
                    $schoolIds[] = $school->id;
                }
            } catch (\Exception $e) {
                Log::warning("Error checking trips for school {$school->id}: " . $e->getMessage());
            }
        }

        // Switch back to main database
        DB::setDefaultConnection('mysql');

        return School::whereIn('id', $schoolIds)->get();
    }

    /**
     * Connect to specific school's Traccar
     */
    private function connectToSchool($school)
    {
        try {
            // Check if already connected
            if (isset($this->schoolConnections[$school->id]) && 
                $this->schoolConnections[$school->id]['connected']) {
                return;
            }

            $this->info("🔐 Connecting to Traccar for School: {$school->name} (ID: {$school->id})");

            // Get or refresh session
            $sessionId = $school->getTraccarSession();

            if (!$sessionId) {
                $this->warn("⚠️ Failed to get Traccar session for school: {$school->name}");
                return;
            }

            // Create WebSocket client
            $client = new Client($this->socketUrl, [
                'headers' => ['Cookie' => "JSESSIONID={$sessionId}"],
                'timeout' => 120
            ]);

            $this->schoolConnections[$school->id] = [
                'school' => $school,
                'client' => $client,
                'session_id' => $sessionId,
                'connected' => true,
                'last_message' => now()
            ];

            $this->info("✅ Connected to Traccar for: {$school->name}");

        } catch (\Throwable $e) {
            $this->error("❌ Connection failed for {$school->name}: " . $e->getMessage());
            Log::error("School Traccar Connection Error:", [
                'school_id' => $school->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Listen to all school connections
     */
    private function listenToAllConnections()
    {
        $this->info("👂 Listening to all school connections...");

        while (true) {
            foreach ($this->schoolConnections as $schoolId => $connection) {
                try {
                    if (!$connection['connected']) {
                        continue;
                    }

                    $client = $connection['client'];
                    $school = $connection['school'];

                    // Non-blocking receive with timeout
                    $message = $client->receive();
                    
                    if ($message) {
                        $this->processMessage($school, $message);
                        $this->schoolConnections[$schoolId]['last_message'] = now();
                    }

                } catch (\WebSocket\TimeoutException $e) {
                    // Timeout is normal, continue
                    continue;
                } catch (\Throwable $e) {
                    $this->error("❌ Error for school {$schoolId}: " . $e->getMessage());
                    $this->schoolConnections[$schoolId]['connected'] = false;
                    
                    // Try to reconnect
                    $this->connectToSchool($connection['school']);
                }
            }

            // Check for stale connections
            $this->checkStaleConnections();

            // Small sleep to prevent CPU overload
            usleep(100000); // 100ms
        }
    }

    /**
     * Process message from Traccar
     */
    private function processMessage($school, $message)
    {
        $data = json_decode($message, true);

        if (!$data) {
            return;
        }

        Log::info("Traccar Data for School {$school->id}:", $data);

        // Process devices
        if (isset($data['devices'])) {
            $this->processDevices($school, $data['devices']);
        }

        // Process positions
        if (isset($data['positions'])) {
            $this->processPositions($school, $data['positions']);
        }
    }

    /**
     * Process devices for specific school
     */
    private function processDevices($school, $devices)
    {
        foreach ($devices as $device) {
            $deviceId = $device['id'];
            $imei = $device['uniqueId'];
            $name = $device['name'] ?? 'Unknown';
            $status = $device['status'] ?? 'unknown';

            // Store in school-specific device map
            $this->deviceMap[$school->id][$deviceId] = [
                'imei' => $imei,
                'name' => $name,
                'status' => $status,
                'phone' => $device['phone'] ?? null
            ];

            $this->info("📡 [{$school->name}] Device: {$name} (IMEI: {$imei}, Status: {$status})");
        }
    }

    /**
     * Process positions for specific school
     */
    private function processPositions($school, $positions)
    {
        // Switch to school database
        $this->switchToSchoolDatabase($school);
        // Log::info("Position Data for School {$school->id}:", $positions);
        foreach ($positions as $position) {
            try {
                $deviceId = $position['deviceId'] ?? null;

                if (!$deviceId || !isset($this->deviceMap[$school->id][$deviceId])) {
                    continue;
                }

                $deviceInfo = $this->deviceMap[$school->id][$deviceId];
                $imei = $deviceInfo['imei'];

                // Extract position data
                $latitude = $position['latitude'];
                $longitude = $position['longitude'];
                $speed = round(($position['speed'] ?? 0) * 1.852, 2); // knots to km/h
                $deviceTime = Carbon::parse($position['deviceTime'])->format('Y-m-d H:i:s');
                $attributes = $position['attributes'] ?? [];

                $this->info("📍 [{$school->name}] {$deviceInfo['name']} - Lat: {$latitude}, Lng: {$longitude}, Speed: {$speed} km/h");

                // Find GPS device in school database
                $gps = GPS::where('imei_no', $imei)->first();

                if (!$gps) {
                    $this->warn("⚠️ GPS not found in school DB: {$imei}");
                    continue;
                }

                // Find active trip
                $activeTrip = RouteVehicleHistory::where('vehicle_id', $gps->assigned_to)
                    ->where('tracking', 1)
                    ->where('status', 'inprogress')
                    ->whereDate('date', today())
                    ->first();

                if (!$activeTrip) {
                    $this->warn("⚠️ No active trip found for vehicle {$gps->assigned_to} (GPS: {$imei})");
                    $this->warn("   Checking: tracking=1, status=inprogress, date=" . today()->toDateString());
                    continue;
                }

                $this->info("🚌 [{$school->name}] Active Trip ID: {$activeTrip->id}");

                // Process through trip tracking service
                $this->tripService->processGPSData(
                    $activeTrip->id,
                    $latitude,
                    $longitude,
                    $speed,
                    $deviceTime,
                    $attributes
                );

            } catch (\Throwable $e) {
                $this->error("❌ Position processing error: " . $e->getMessage());
                Log::error('Position Error:', [
                    'school_id' => $school->id,
                    'position' => $position,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Switch back to main database
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

    /**
     * Check for stale connections
     */
    private function checkStaleConnections()
    {
        foreach ($this->schoolConnections as $schoolId => $connection) {
            // If no message received in 5 minutes, reconnect
            if ($connection['last_message']->diffInMinutes(now()) > 5) {
                $this->warn("⚠️ Stale connection detected for school {$schoolId}. Reconnecting...");
                $this->schoolConnections[$schoolId]['connected'] = false;
                $this->connectToSchool($connection['school']);
            }
        }
    }

    /**
     * Listen for specific school only
     */
    private function listenForSchool($schoolId)
    {
        $school = School::find($schoolId);

        if (!$school) {
            $this->error("School not found: {$schoolId}");
            return;
        }

        $this->info("🚀 Starting listener for: {$school->name}");

        while (true) {
            try {
                $this->connectToSchool($school);
                
                if (!isset($this->schoolConnections[$schoolId])) {
                    $this->error("Failed to connect. Retrying in 10 seconds...");
                    sleep(10);
                    continue;
                }

                $connection = $this->schoolConnections[$schoolId];
                $client = $connection['client'];

                while (true) {
                    $message = $client->receive();
                    if ($message) {
                        $this->processMessage($school, $message);
                    }
                }

            } catch (\Throwable $e) {
                $this->error("❌ Error: " . $e->getMessage());
                sleep(5);
            }
        }
    }
}
