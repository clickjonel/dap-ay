<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProgram extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_sub_programs';
    protected $primaryKey = 'sub_program_id';

    protected $fillable = [
        'program_id',
        'sub_program_name',
        'sub_program_code',
        'sub_program_active'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function indicators()
    {
        return $this->hasMany(ProgramIndicator::class, 'sub_program_id', 'sub_program_id');
    }
}
