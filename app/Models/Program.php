<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable =[
        'name',
        'parent_program_id',
        'is_active'
    ];
    public $timestamps = false;
    
    public function group()
    {
        return $this->belongsTo(ProgramGroup::class, 'parent_program_id');
    }
}
