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
        // 1. First, change the column type
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('action_by')->nullable()->change(); 
        });

        // 2. Then, add the foreign key constraint
        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('action_by')->references('id')->on('users')
                ->onDelete('set null'); // Optional: prevents errors if a user is deleted
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['action_by']);
        });

        Schema::table('reports', function (Blueprint $table) {
            // Revert column type to original string
            $table->string('action_by')->nullable()->change();
        });
    }
};
