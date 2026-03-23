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
            $table->string('psgc_code')->unique()->nullable();
            $table->string('name');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('municipality_id');
            $table->text('mov_link')->nullable();

            //set province foriegn key
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');
            //set municipality foreign key
            $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('restrict');
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
