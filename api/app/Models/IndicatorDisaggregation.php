<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorDisaggregation extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'indicator_disaggregations';
    public $timestamps = false;
    protected $fillable = [
        'indicator_id',
        'name',
        'totalable',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'totalable' => 'boolean'
    ];
}
