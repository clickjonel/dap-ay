<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayPriorityProgram extends Model
{
    protected $fillable = [
        'barangay_id',
        'program_id',
        'target',
        'order',
    ];
    public $timestamps = false;

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
