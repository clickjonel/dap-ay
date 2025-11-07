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
        Schema::create('pk_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
        });

        Schema::create('user_indicator_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('pk_users','user_id');
            $table->foreignId('indicator_id')->constrained('pk_indicators','id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pk_indicators');
    }
};
