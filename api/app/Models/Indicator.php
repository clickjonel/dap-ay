<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_indicators';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'type',
    ];
}
