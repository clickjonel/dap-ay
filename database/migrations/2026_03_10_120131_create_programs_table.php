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
        Schema::create('program_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_program_id')->nullable();
            $table->string('name');
            $table->boolean('is_active')->default(true);

            $table->foreign('parent_program_id')->references('id')->on('program_groups')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
        Schema::dropIfExists('program_groups');
    }
};
