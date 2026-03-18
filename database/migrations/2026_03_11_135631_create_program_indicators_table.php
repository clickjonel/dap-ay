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
        Schema::create('program_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('indicator_name');
            $table->boolean('is_active');
            $table->unsignedBigInteger('program_id');
            
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_indicators');
    }
};
