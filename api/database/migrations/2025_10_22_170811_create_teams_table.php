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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams','id');
            $table->foreignId('user_id')->nullable()->constrained('users','id');
            $table->boolean('doh_deployed');
            $table->string('name');
            $table->string('position');
            $table->string('role');
            $table->boolean('pk_oriented');
            $table->boolean('received_pk_kit');
        });

        Schema::create('team_barangays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams','id');
            $table->foreignId('barangay_id')->constrained('barangays','id');
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
