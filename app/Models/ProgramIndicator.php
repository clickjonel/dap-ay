<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramIndicator extends Model
{
    protected $fillable = [
        'indicator_name',
        'is_active',
        'program_id'
    ];
    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function disaggregations()
    {
        return $this->belongsToMany(Disaggregation::class, 'program_indicator_disaggregations');
    }

    public function reportValues()
    {
        return $this->hasMany(ReportValue::class,'indicator_id', 'id');
    }
    
}
