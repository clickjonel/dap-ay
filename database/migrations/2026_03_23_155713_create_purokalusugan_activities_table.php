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
        Schema::create('purokalusugan_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('activity_name');
            $table->string('type');
            $table->integer('total_clients');
            $table->timestamps();
        });

        Schema::create('pk_activity_barangays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pk_activity_id');
            $table->unsignedBigInteger('barangay_id');

            $table->foreign('pk_activity_id')->references('id')->on('purokalusugan_activities');
            $table->foreign('barangay_id')->references('id')->on('barangays');
        });

        Schema::create('pk_activity_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pk_activity_id');
            $table->unsignedBigInteger('program_id');

            $table->foreign('pk_activity_id')->references('id')->on('purokalusugan_activities');
            $table->foreign('program_id')->references('id')->on('programs');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purokalusugan_activities');
    }
};
