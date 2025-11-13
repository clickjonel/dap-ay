<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    protected $connection ='dap-ay';
    protected $table = 'server_logs';
    protected $fillable = [
        'user_id',
        'action_done',
        'table_name',
        'column_id'
    ];

    //Defined relationships
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
