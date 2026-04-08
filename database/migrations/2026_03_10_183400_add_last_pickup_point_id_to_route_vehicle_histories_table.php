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
        Schema::table('route_vehicle_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('last_pickup_point_id')->nullable()->after('type');
            $table->foreign('last_pickup_point_id')->references('id')->on('pickup_points')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('route_vehicle_histories', function (Blueprint $table) {
            $table->dropForeign(['last_pickup_point_id']);
            $table->dropColumn('last_pickup_point_id');
        });
    }
};
