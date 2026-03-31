<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'parent_program_id', 'is_active'])]

class Program extends Model
{
    public $timestamps = false;
    
    public function group()
    {
        return $this->belongsTo(ProgramGroup::class, 'parent_program_id');
    }

    public function baseline()
    {
        return $this->hasOne(ProgramBaseline::class,'program_id', 'id');
    }

    public function indicators()
    {
        return $this->hasMany(ProgramIndicator::class,'program_id', 'id');
    }
}
