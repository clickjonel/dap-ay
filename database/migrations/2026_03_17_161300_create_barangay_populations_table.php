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
        Schema::create('barangay_populations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangay_id');
            $table->integer('total_population')->nullable();
            $table->integer('target_population')->nullable();
            $table->integer('total_puroks')->nullable();
            $table->integer('target_puroks')->nullable();
            $table->integer('total_households')->nullable();
            $table->integer('target_households')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_populations');
    }
};
