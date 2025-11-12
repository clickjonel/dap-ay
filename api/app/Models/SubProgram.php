<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProgram extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'sub_programs';
    protected $fillable = [
        'program_id',
        'name',
        'code',
        'active'
    ];
     protected $casts = [
        'active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'sub_program_id');
    }
}
