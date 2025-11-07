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
        Schema::create('pk_barangays', function (Blueprint $table) {
            $table->id('barangay_id');
            $table->foreignId('province_id')->constrained('pk_provinces','province_id');
            $table->foreignId('municipality_id')->constrained('pk_municipalities','municipality_id');
            $table->string('barangay_name');
            $table->smallInteger('pk_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_barangays');
    }
};
