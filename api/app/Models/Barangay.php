<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'barangays';
    public $timestamps = false;
    protected $fillable = [
        'province_id',
        'municipality_id',
        'name',
        'pk_status',
        'is_gida',
        'is_pk_site',
        'deployed_hrh',
        'latitude',
        'longitude',
        'total_purok',
        'target_purok',
        'target_population'
    ];

    public function municipality()
    {
         return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function province()
    {
         return $this->belongsTo(Province::class, 'province_id');
    }

    // public function prioritySubPrograms()
    // {
    //     return $this->hasMany(BarangayPriorityProgram::class, 'barangay_id', 'id');
    // }

    public function subPrograms()
    {
        return $this->belongsToMany(
            SubProgram::class,
            'barangay_priority_programs',
            'barangay_id',
            'sub_program_id'
        )
        ->withPivot(['id','baseline', 'order'])
         ->orderBy('barangay_priority_programs.order', 'asc');
    }

}
