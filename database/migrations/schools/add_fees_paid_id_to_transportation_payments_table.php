<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transportation_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('fees_paid_id')->nullable()->after('session_year_id');
        });
    }

    public function down(): void
    {
        Schema::table('transportation_payments', function (Blueprint $table) {
            $table->dropColumn('fees_paid_id');
        });
    }
};
