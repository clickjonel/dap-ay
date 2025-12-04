<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'indicators';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'sub_program_id',
        'active',
        'type',
    ];
     protected $casts = [
        'active' => 'boolean',
    ];

    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class,'sub_program_id');
    }

    public function disaggregations()
    {
        return $this->hasMany(IndicatorDisaggregation::class,'indicator_id','id');
    }

    public function reportValues()
    {
        return $this->hasMany(ReportValue::class, 'indicator_id','id');
    }

}
