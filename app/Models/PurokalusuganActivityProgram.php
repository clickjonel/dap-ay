<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurokalusuganActivityProgram extends Model
{
    protected $table = 'pk_activity_programs';
    protected $fillable = [
        'pk_activity_id',
        'program_id',
    ];
    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
