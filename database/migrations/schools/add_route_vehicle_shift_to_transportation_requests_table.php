<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transportation_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('route_vehicle_id')->nullable()->after('pickup_point_id');
            $table->unsignedBigInteger('shift_id')->nullable()->after('route_vehicle_id');
        });
    }

    public function down(): void
    {
        Schema::table('transportation_requests', function (Blueprint $table) {
            $table->dropColumn(['route_vehicle_id', 'shift_id']);
        });
    }
};
