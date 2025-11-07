<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityProgram extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_activity_programs';
    protected $primaryKey = 'activity_program_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'sub_program_id',
    ];

    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class, 'sub_program_id', 'sub_program_id');
    }

}
