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
        Schema::create('pk_program_indicators', function (Blueprint $table) {
            $table->id('program_indicator_id');
            $table->string('indicator_code')->unique();
            $table->string('indicator_name');
            $table->string('indicator_type')->default('Baseline');
            $table->boolean('indicator_active')->default(true);
            $table->foreignId('sub_program_id')->constrained('pk_sub_programs','sub_program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_program_indicators');
    }
};
