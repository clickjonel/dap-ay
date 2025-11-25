<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValue extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'report_values';
    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'indicator_id',
        'value',
    ];

    public function breakdowns()
    {
        return $this->hasMany(ReportValueBreakdown::class,'id');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class,'indicator_id','id');
    }
}
