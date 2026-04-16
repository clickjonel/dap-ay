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
        Schema::create('purokalusugan_activity_hrh_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pk_activity_id')->constrained('purokalusugan_activities','id')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users','id')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purokalusugan_activity_h_r_h_participants');
    }
};
