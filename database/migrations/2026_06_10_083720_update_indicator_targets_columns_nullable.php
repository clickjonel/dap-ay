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
        Schema::table('barangay_indicator_targets', function (Blueprint $table) {
            $table->integer('target')->nullable()->change();
            $table->integer('served')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_indicator_targets', function (Blueprint $table) {
            $table->integer('target')->nullable(false)->change();
            $table->integer('served')->nullable(false)->change();
        });
    }
};
