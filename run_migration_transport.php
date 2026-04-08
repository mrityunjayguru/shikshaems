<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

$schools = \App\Models\School::withTrashed()->get();

foreach ($schools as $school) {
    echo "Processing: {$school->name} ({$school->database_name})\n";
    try {
        Config::set('database.connections.school.database', $school->database_name);
        DB::purge('school');
        DB::connection('school')->reconnect();

        $cols = DB::connection('school')->select("SHOW COLUMNS FROM transportation_requests LIKE 'route_vehicle_id'");
        if (empty($cols)) {
            DB::connection('school')->statement("ALTER TABLE transportation_requests ADD COLUMN route_vehicle_id BIGINT UNSIGNED NULL AFTER pickup_point_id");
            DB::connection('school')->statement("ALTER TABLE transportation_requests ADD COLUMN shift_id BIGINT UNSIGNED NULL AFTER route_vehicle_id");
            echo "  -> Columns added\n";
        } else {
            echo "  -> Already exists\n";
        }

        $exists = DB::connection('school')->table('migrations')
            ->where('migration', 'add_route_vehicle_shift_to_transportation_requests_table')->exists();
        if (!$exists) {
            $batch = DB::connection('school')->table('migrations')->max('batch') + 1;
            DB::connection('school')->table('migrations')->insert([
                'migration' => 'add_route_vehicle_shift_to_transportation_requests_table',
                'batch'     => $batch,
            ]);
        }
    } catch (Exception $e) {
        echo "  -> ERROR: " . $e->getMessage() . "\n";
    }
}
echo "\nDone!\n";
