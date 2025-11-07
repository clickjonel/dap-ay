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
        Schema::create('pk_site_programs', function (Blueprint $table) {
            $table->id('site_program_id');
            $table->foreignId('program_id')->constrained('pk_programs','program_id');
            $table->foreignId('profile_id')->constrained('pk_site_profiles','profile_id');
            $table->bigInteger('target_baseline');
            $table->smallInteger('priority_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_site_programs');
    }
};
