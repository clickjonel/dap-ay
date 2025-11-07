<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramIndicator extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_program_indicators';
    protected $primaryKey = 'program_indicator_id';
    public $timestamps = false;

    protected $fillable = [
        'indicator_name',
        'indicator_code',
        'sub_program_id',
        'indicator_active',
        'indicator_type'
    ];
}
