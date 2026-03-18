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
        Schema::create('program_indicator_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_indicator_id');
            $table->unsignedBigInteger('disaggregation_id');

            $table->foreign('program_indicator_id')->references('id')->on('program_indicators')->onDelete('restrict');
            $table->foreign('disaggregation_id')->references('id')->on('disaggregations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_indicator_disaggregations');
    }
};
