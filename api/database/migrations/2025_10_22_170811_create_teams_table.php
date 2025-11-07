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
        Schema::create('pk_teams', function (Blueprint $table) {
            $table->id('team_id');
            $table->string('team_name');
            $table->foreignId('barangay_id')->constrained('pk_barangays','barangay_id');
            $table->boolean('team_active')->default(true);
        });

        Schema::create('pk_team_members', function (Blueprint $table) {
            $table->id('team_member_id');
            $table->string('member_name');
            $table->string('member_position');
            $table->boolean('member_oriented');
            $table->boolean('member_trained');
            $table->boolean('pk_kit_receipt');
             $table->string('site_name');
            $table->foreignId('user_id')->nullable()->constrained('pk_users','user_id');
            $table->foreignId('team_id')->constrained('pk_teams','team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
