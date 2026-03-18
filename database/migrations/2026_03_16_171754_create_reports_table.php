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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_clients');
            $table->integer('total_returning_clients');
            $table->unsignedBigInteger('barangay_id');

            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('report_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::create('report_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('indicator_id');
            $table->unsignedBigInteger('program_id');
            $table->integer('total');

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('restrict');
            $table->foreign('indicator_id')->references('id')->on('program_indicators')->onDelete('restrict');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('restrict');
        });

        Schema::create('report_value_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_value_id');
            $table->unsignedBigInteger('disaggregation_id');
            $table->integer('value');

            $table->foreign('report_value_id')->references('id')->on('report_values')->onDelete('restrict');
            $table->foreign('disaggregation_id')->references('id')->on('disaggregations')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_users');
        Schema::dropIfExists('report_values');
        Schema::dropIfExists('report_value_disaggregations');
    }
};
