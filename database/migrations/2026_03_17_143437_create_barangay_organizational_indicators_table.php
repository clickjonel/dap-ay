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
        Schema::create('barangay_organizational_indicators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('org_indicator_id');
            $table->string('value')->nullable();
            $table->integer('facility_based')->nullable();
            $table->integer('community_based')->nullable();

            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('restrict');
            $table->foreign('org_indicator_id')->references('id')->on('organizational_indicators')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_organizational_indicators');
    }
};
