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

        Schema::create('barangays', function (Blueprint $table) {
            $table->id();

            // details
            $table->foreignId('province_id')->constrained('provinces','id');
            $table->foreignId('municipality_id')->constrained('municipalities','id');
            $table->string('name');

            //pk status
            $table->string('status');

            //geolocation details
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            //baseline totals
            $table->integer('total_purok')->nullable();
            $table->integer('target_purok')->nullable();
            $table->integer('target_population')->nullable();

        });

        Schema::create('barangay_priority_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->foreignId('sub_program_id')->constrained('sub_programs','id');
            $table->integer('baseline')->nullable();
            $table->integer('order');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};
