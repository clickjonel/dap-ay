<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityValueBreakdown extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_activity_value_breakdown';
    protected $primaryKey = 'breakdown_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_value_id',
        'key',
        'value',
    ];
}
