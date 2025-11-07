<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIndicatorTarget extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'user_indicator_targets';
    protected $fillable = [
        'user_id',
        'indicator_id'
    ];
}
