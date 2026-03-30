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

        // Check if columns already exist
        $cols = DB::connection('school')->select("SHOW COLUMNS FROM fees_class_types LIKE 'number_of_months'");
        if (empty($cols)) {
            DB::connection('school')->statement("ALTER TABLE fees_class_types ADD COLUMN number_of_months TINYINT NULL AFTER optional");
            DB::connection('school')->statement("ALTER TABLE fees_class_types ADD COLUMN applicable_months JSON NULL AFTER number_of_months");
            echo "  -> Columns added\n";
        } else {
            echo "  -> Already exists\n";
        }

        // Migration record
        $exists = DB::connection('school')->table('migrations')
            ->where('migration', 'add_months_to_fees_class_types_table')->exists();
        if (!$exists) {
            $batch = DB::connection('school')->table('migrations')->max('batch') + 1;
            DB::connection('school')->table('migrations')->insert([
                'migration' => 'add_months_to_fees_class_types_table',
                'batch'     => $batch,
            ]);
        }
    } catch (Exception $e) {
        echo "  -> ERROR: " . $e->getMessage() . "\n";
    }
}
echo "\nDone!\n";
