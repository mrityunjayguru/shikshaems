<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fees_class_types', function (Blueprint $table) {
            $table->tinyInteger('number_of_months')->nullable()->after('optional');
            $table->json('applicable_months')->nullable()->after('number_of_months'); // [1,2,3...]
        });
    }

    public function down(): void
    {
        Schema::table('fees_class_types', function (Blueprint $table) {
            $table->dropColumn(['number_of_months', 'applicable_months']);
        });
    }
};
