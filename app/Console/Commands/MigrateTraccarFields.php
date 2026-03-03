<?php

namespace App\Console\Commands;

use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateTraccarFields extends Command
{
    protected $signature = 'migrate:traccar-fields';
    protected $description = 'Add tracking field to route_vehicle_histories table in all school databases';

    public function handle()
    {
        $this->info('🚀 Starting Traccar fields migration for all schools...');
        
        $schools = School::withTrashed()->get();
        $successCount = 0;
        $skipCount = 0;
        $errorCount = 0;

        foreach ($schools as $school) {
            try {
                $this->info("📍 Processing School: {$school->name} (ID: {$school->id})");
                
                // Switch to school database
                Config::set('database.connections.school.database', $school->database_name);
                DB::purge('school');
                DB::connection('school')->reconnect();
                DB::setDefaultConnection('school');

                // Check and add tracking column to route_vehicle_histories
                if (Schema::hasTable('route_vehicle_histories')) {
                    if (!Schema::hasColumn('route_vehicle_histories', 'tracking')) {
                        Schema::table('route_vehicle_histories', function ($table) {
                            $table->boolean('tracking')->default(0)->after('end_time');
                        });
                        $this->info("   ✅ Added 'tracking' column to route_vehicle_histories");
                        $successCount++;
                    } else {
                        $this->warn("   ⏭️  'tracking' column already exists, skipping");
                        $skipCount++;
                    }
                } else {
                    $this->warn("   ⚠️  route_vehicle_histories table not found");
                    $skipCount++;
                }

                // Optional: Add traccar fields to schools table if needed
                if (Schema::hasTable('schools')) {
                    $fieldsAdded = false;
                    
                    if (!Schema::hasColumn('schools', 'traccar_phone')) {
                        Schema::table('schools', function ($table) {
                            $table->string('traccar_phone')->nullable()->after('database_name');
                            $table->string('traccar_session_id')->nullable()->after('traccar_phone');
                            $table->timestamp('traccar_session_expires_at')->nullable()->after('traccar_session_id');
                        });
                        $this->info("   ✅ Added Traccar fields to schools table");
                        $fieldsAdded = true;
                    }
                    
                    if (!$fieldsAdded) {
                        $this->info("   ℹ️  Schools table already has Traccar fields");
                    }
                }

            } catch (\Exception $e) {
                $this->error("   ❌ Error: " . $e->getMessage());
                $errorCount++;
            }
        }

        // Switch back to main database
        DB::setDefaultConnection('mysql');

        $this->info("\n📊 Migration Summary:");
        $this->info("   ✅ Success: {$successCount}");
        $this->info("   ⏭️  Skipped: {$skipCount}");
        $this->info("   ❌ Errors: {$errorCount}");
        $this->info("   📝 Total Schools: " . $schools->count());
        
        $this->info("\n🎉 Traccar fields migration completed!");
        
        return 0;
    }
}
