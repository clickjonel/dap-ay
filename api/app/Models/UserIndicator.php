<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIndicator extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'user_indicators';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'indicator_id'
    ];
}
