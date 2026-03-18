<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValue extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'report_id',
        'indicator_id',
        'program_id',
        'total',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function indicator()
    {
        return $this->belongsTo(ProgramIndicator::class, 'indicator_id');
    }

    public function disaggregations()
    {
        return $this->hasMany(ReportValueDisaggregation::class);
    }
    
}
