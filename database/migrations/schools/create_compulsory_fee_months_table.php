<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compulsory_fee_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compulsory_fee_id');
            $table->tinyInteger('month_number'); // 1=Jan ... 12=Dec
            $table->string('month_name');        // "January", "February", etc.
            $table->decimal('amount', 10, 2);    // full: monthly_fee, partial: remainder
            $table->boolean('is_partial')->default(false);
            $table->timestamps();

            $table->foreign('compulsory_fee_id')->references('id')->on('compulsory_fees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compulsory_fee_months');
    }
};
