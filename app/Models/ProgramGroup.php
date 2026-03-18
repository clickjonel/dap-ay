<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramGroup extends Model
{
    protected $table = 'program_groups';
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
