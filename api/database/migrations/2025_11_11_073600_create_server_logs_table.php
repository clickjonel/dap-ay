<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pk_server_logs')) {
            echo "Table `pk_server_logs` already exists!\n";
            return;
        }
        Schema::create('pk_server_logs', function (Blueprint $table) {
            $table->id('server_log_id');
            $table->foreignId('user_id')->constrained('pk_users', 'user_id');
            $table->foreign('user_id', 'fk_server_logs_user')
                ->references('user_id')->on('pk_users')
                ->nullable();
            $table->string('action_done')->nullable();
            $table->string('table_name')->nullable();
            $table->string('column_id')->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    // this is the same as your usual SQL query file
    /*
    CREATE TABLE dohis_activity_logs(
	activity_log_id bigint(20) NOT NULL AUTO_INCREMENT,
	user_id bigint(20) DEFAULT NULL COMMENT 'foreign to dohis_user action done by',
	action_done varchar(255) DEFAULT NULL,
	involved_table varchar(255) DEFAULT NULL,
	involved_field_id varchar(255) DEFAULT NULL,
	created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (activity_log_id),
	FOREIGN KEY (user_id) REFERENCES dohis.dohis_user(user_id)
    */

};
