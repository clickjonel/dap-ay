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
            $table->foreignId('created_by')->constrained('users','id');
            $table->date('start');
            $table->date('end');
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->timestamps();
        });

        Schema::create('report_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports','id');
            $table->foreignId('indicator_id')->constrained('indicators','id');
            $table->integer('value');
        });

        Schema::create('report_value_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_value_id')->constrained('reports','id');
            $table->string('name');
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_values');
    }
};
