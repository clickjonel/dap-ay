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
        Schema::create('barangay_priority_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('program_id');
            $table->integer('target');
            $table->integer('order');

            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('restrict');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_priority_programs');
    }
};
