<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorDisaggregation extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'indicators';
    public $timestamps = false;
    protected $fillable = [
        'indicator_id',
        'name',
        'totalable',
        'active'
    ];
}
