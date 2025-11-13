<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('server_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_id')->constrained('users', 'id');
            $table->string('action_done')->nullable();
            $table->string('table_name')->nullable();
            $table->string('column_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('server_logs');
    }
};
