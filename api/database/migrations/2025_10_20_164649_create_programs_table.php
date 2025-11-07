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
        Schema::create('pk_programs', function (Blueprint $table) {
            $table->id('program_id');
            $table->string('program_name');
            $table->string('program_code')->unique();
            $table->boolean('program_active');
            $table->timestamps();
        });

        Schema::create('pk_sub_programs', function (Blueprint $table) {
            $table->id('sub_program_id');
            $table->foreignId('program_id')->constrained('pk_programs','program_id');
            $table->string('sub_program_name');
            $table->string('sub_program_code')->unique();
            $table->boolean('sub_program_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
