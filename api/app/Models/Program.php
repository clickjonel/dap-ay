<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_programs';
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'program_name',
        'program_code',
        'program_active'
    ];

    public function subPrograms()
    {
        return $this->hasMany(SubProgram::class,'program_id','program_id');
    }

    public function indicators()
    {
        return $this->hasManyThrough(
            ProgramIndicator::class,
            SubProgram::class,
            'program_id',        // Foreign key on sub_programs table
            'sub_program_id',    // Foreign key on program_indicators table
            'program_id',        // Local key on programs table
            'sub_program_id'     // Local key on sub_programs table
        );
    }
}
