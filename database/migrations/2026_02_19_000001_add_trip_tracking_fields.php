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
        // Add Traccar fields to schools table
        if (!Schema::hasColumn('schools', 'traccar_phone')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('traccar_phone')->nullable()->after('database_name');
                $table->string('traccar_session_id')->nullable()->after('traccar_phone');
                $table->timestamp('traccar_session_expires_at')->nullable()->after('traccar_session_id');
            });
        }

        // Check if bus_locations table exists and has proper structure
        if (Schema::hasTable('bus_locations')) {
            $hasDeviceId = Schema::hasColumn('bus_locations', 'device_id');
            $hasSpeed = Schema::hasColumn('bus_locations', 'speed');
            $hasCurrentStopId = Schema::hasColumn('bus_locations', 'current_stop_id');
            
            if (!$hasDeviceId || !$hasSpeed) {
                // Table exists but doesn't have proper structure, recreate it
                Schema::dropIfExists('bus_locations');
                Schema::create('bus_locations', function (Blueprint $table) {
                    $table->id();
                    $table->text('device_id')->nullable();
                    $table->string('trip_id')->nullable();
                    $table->text('device_time')->nullable();
                    $table->string('latitude')->nullable();
                    $table->string('longitude')->nullable();
                    $table->string('speed')->nullable();
                    $table->unsignedBigInteger('current_stop_id')->nullable();
                    $table->unsignedBigInteger('next_stop_id')->nullable();
                    $table->integer('eta_minutes')->nullable();
                    $table->timestamps();
                });
            } elseif (!$hasCurrentStopId) {
                // Table has proper structure, just add new columns
                Schema::table('bus_locations', function (Blueprint $table) {
                    $table->unsignedBigInteger('current_stop_id')->nullable()->after('speed');
                    $table->unsignedBigInteger('next_stop_id')->nullable()->after('current_stop_id');
                    $table->integer('eta_minutes')->nullable()->after('next_stop_id');
                });
            }
        } else {
            // Table doesn't exist, create it
            Schema::create('bus_locations', function (Blueprint $table) {
                $table->id();
                $table->text('device_id')->nullable();
                $table->string('trip_id')->nullable();
                $table->text('device_time')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->string('speed')->nullable();
                $table->unsignedBigInteger('current_stop_id')->nullable();
                $table->unsignedBigInteger('next_stop_id')->nullable();
                $table->integer('eta_minutes')->nullable();
                $table->timestamps();
            });
        }

        // Create trip_stop_arrivals table
        if (!Schema::hasTable('trip_stop_arrivals')) {
            Schema::create('trip_stop_arrivals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trip_id');
                $table->unsignedBigInteger('stop_id');
                $table->timestamp('arrival_time');
                $table->timestamp('departure_time')->nullable();
                $table->integer('students_boarded')->default(0);
                $table->timestamps();
                
                $table->index(['trip_id', 'stop_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_stop_arrivals');
        
        if (Schema::hasTable('bus_locations')) {
            Schema::table('bus_locations', function (Blueprint $table) {
                if (Schema::hasColumn('bus_locations', 'current_stop_id')) {
                    $table->dropColumn(['current_stop_id', 'next_stop_id', 'eta_minutes']);
                }
            });
        }

        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if (Schema::hasColumn('schools', 'traccar_phone')) {
                    $table->dropColumn(['traccar_phone', 'traccar_session_id', 'traccar_session_expires_at']);
                }
            });
        }
    }
};
