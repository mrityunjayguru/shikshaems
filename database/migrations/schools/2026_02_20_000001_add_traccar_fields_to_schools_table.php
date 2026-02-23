<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Traccar fields to schools table in school databases
        if (Schema::hasTable('schools') && !Schema::hasColumn('schools', 'traccar_phone')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('traccar_phone')->nullable()->after('database_name');
                $table->string('traccar_session_id')->nullable()->after('traccar_phone');
                $table->timestamp('traccar_session_expires_at')->nullable()->after('traccar_session_id');
            });
        }

        // Add tracking field to route_vehicle_histories table
        if (Schema::hasTable('route_vehicle_histories') && !Schema::hasColumn('route_vehicle_histories', 'tracking')) {
            Schema::table('route_vehicle_histories', function (Blueprint $table) {
                $table->boolean('tracking')->default(0)->after('end_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if (Schema::hasColumn('schools', 'traccar_phone')) {
                    $table->dropColumn(['traccar_phone', 'traccar_session_id', 'traccar_session_expires_at']);
                }
            });
        }

        if (Schema::hasTable('route_vehicle_histories')) {
            Schema::table('route_vehicle_histories', function (Blueprint $table) {
                if (Schema::hasColumn('route_vehicle_histories', 'tracking')) {
                    $table->dropColumn('tracking');
                }
            });
        }
    }
};
