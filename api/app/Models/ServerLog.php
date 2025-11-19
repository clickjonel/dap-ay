<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    protected $connection ='dap-ay';
    protected $table = 'server_logs';
    protected $fillable = [
        'created_by_id',
        'action_done',
        'table_name',
        'model_id'
    ];

    //Defined relationships
    public function user(){
        return $this->belongsTo(User::class,'created_by_id','id');
    }
}
