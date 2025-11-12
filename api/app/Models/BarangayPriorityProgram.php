<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayPriorityProgram extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'barangay_priority_programs';
    public $timestamps = false;
    protected $fillable = [
        'barangay_id',
        'sub_program_id',
        'baseline',
        'order',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class,'barangay_id');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class,'sub_program_id');
    }
}
