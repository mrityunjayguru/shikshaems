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
        Schema::create('g_p_s', function (Blueprint $table) {
            $table->id();
            $table->integer('device_type_id');
            $table->string('imei_no');
            $table->string('sim_no');
            $table->boolean('status')->default(0); //0 - unassigned, 1-assigned
            $table->integer('school_id')->nullable(); 
            $table->boolean('school_assigned')->default(0); //0 - unassigned, 1-assigned
            $table->dateTime('assigned_on')->nullable();
            $table->integer('assigned_to')->nullable(); //vehicle_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_p_s');
    }
};
