a<?php

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
        Schema::create('pk_activities', function (Blueprint $table) {
            $table->id('activity_id');
            $table->string('activity_name');
            $table->string('activity_venue');
            $table->decimal('activity_budget', 15, 2);
             $table->decimal('activity_actual_budget', 15, 2);
            $table->string('activity_fund_source');
            $table->text('activity_proponents');
            $table->text('activity_partners');
            $table->date('activity_date_start');
            $table->date('activity_date_end');
            $table->string('activity_type');
            $table->string('activity_scope');
            $table->date('date_populated')->nullable();
            $table->timestamps();
        });

        Schema::create('pk_activity_barangays', function (Blueprint $table) {
            $table->id('activity_barangay_id');
            $table->foreignId('activity_id')->constrained('pk_activities','activity_id')->onDelete('restrict');
            $table->foreignId('barangay_id')->constrained('pk_barangays','barangay_id')->onDelete('restrict');
        });

        Schema::create('pk_activity_programs', function (Blueprint $table) {
            $table->id('activity_program_id');
            $table->foreignId('activity_id')->constrained('pk_activities','activity_id')->onDelete('restrict');
            $table->foreignId('sub_program_id')->constrained('pk_sub_programs','sub_program_id')->onDelete('restrict');
        });

        Schema::create('pk_activity_resources', function (Blueprint $table) {
            $table->id('activity_resource_id');
            $table->foreignId('activity_id')->constrained('pk_activities','activity_id')->onDelete('restrict');
            $table->string('resource_name');
            $table->string('resource_type');
            $table->integer('resource_total_beneficiary');
            $table->decimal('resource_amount',15,2);
        });

        Schema::create('pk_activity_values', function (Blueprint $table) {
            $table->id('activity_value_id');
            $table->foreignId('activity_id')->constrained('pk_activities','activity_id')->onDelete('restrict');
            $table->foreignId('barangay_id')->constrained('pk_barangays','barangay_id')->onDelete('restrict');
            $table->foreignId('sub_program_id')->constrained('pk_sub_programs','sub_program_id')->onDelete('restrict');
            $table->foreignId('program_indicator_id')->constrained('pk_program_indicators','program_indicator_id')->onDelete('restrict');
            $table->integer('total_served');
        });

        Schema::create('pk_activity_value_breakdown', function (Blueprint $table) {
            $table->id('breakdown_id');
            $table->foreignId('activity_value_id')->constrained('pk_activity_values','activity_value_id')->onDelete('restrict');
            $table->string('key');
            $table->integer('value');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_activities');
    }
};
