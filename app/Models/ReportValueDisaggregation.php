<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValueDisaggregation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'report_value_id',
        'disaggregation_id',
        'value',
    ];

    public function disaggregation()
    {
        return $this->belongsTo(Disaggregation::class);
    }
}
