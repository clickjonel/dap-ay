<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'programs';
    protected $fillable = [
        'name',
        'code',
        'active'
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
    public function subPrograms()
    {
        return $this->hasMany(SubProgram::class,'program_id');
    }

    public function indicators()
    {
        return $this->hasManyThrough(
            Indicator::class,
            SubProgram::class,
            'program_id', 
            'sub_program_id',
            'id',      
            'id'
        );
    }
}
