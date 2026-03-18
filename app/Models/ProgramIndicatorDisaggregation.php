<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramIndicatorDisaggregation extends Model
{
    protected $fillable = [
        'program_indicator_id',
        'disaggregation_id',
    ];
    public $timestamps = false;
}
