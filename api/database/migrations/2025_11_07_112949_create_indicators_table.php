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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('sub_program_id')->nullable()->constrained('sub_programs');
            $table->string('type');
            $table->boolean('active');
        });

        Schema::create('indicator_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('indicators','id');
            $table->string('name');
            $table->boolean('totalable');
            $table->boolean(column: 'active');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('indicator_disaggregations');
    }
};
