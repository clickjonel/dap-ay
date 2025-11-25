<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValueBreakdown extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'report_value_breakdowns';
    public $timestamps = false;

    protected $fillable = [
        'report_value_id',
        'name',
        'value',
    ];
}
