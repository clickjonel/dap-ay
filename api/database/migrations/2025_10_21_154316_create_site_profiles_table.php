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
        Schema::create('pk_site_profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('site_id')->constrained('pk_sites','site_id');
            $table->bigInteger('total_purok');
            $table->bigInteger('total_sitio');
            $table->bigInteger('total_target_purok');
            $table->bigInteger('total_target_sitio');
            $table->bigInteger('total_target_population');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_site_profiles');
    }
};
