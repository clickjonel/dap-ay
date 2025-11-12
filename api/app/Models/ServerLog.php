<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    protected $connection ='dap-ay';
    protected $table = 'pk_server_logs';
    protected $primaryKey = 'server_log_id';

    protected $fillable = [
        'user_id',
        'action_done',
        'table_name',
        'column_id'
    ];
}
