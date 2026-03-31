<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps]
#[Fillable(['program_id', 'target'])]
class ProgramBaseline extends Model
{
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
