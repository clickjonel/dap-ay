<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteProgram extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_site_programs';
    protected $primaryKey = 'site_program_id';
    public $timestamps = false;
    protected $fillable = [
        'program_id',
        'profile_id',
        'target_baseline',
        'priority_order',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id','program_id');
    }
}
