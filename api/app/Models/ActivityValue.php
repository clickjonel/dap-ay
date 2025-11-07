<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityValue extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_activity_values';
    protected $primaryKey = 'activity_value_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'barangay_id',
        'sub_program_id',
        'program_indicator_id',
        'total_served'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class,'barangay_id','barangay_id');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class,'sub_program_id','sub_program_id');
    }

    public function programIndicator()
    {
        return $this->belongsTo(ProgramIndicator::class,'program_indicator_id','program_indicator_id');
    }

    public function breakdown()
    {
        return $this->hasMany(ActivityValueBreakdown::class,'activity_value_id','activity_value_id');
    }
}
