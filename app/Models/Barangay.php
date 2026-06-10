<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'psgc_code',
        'name',
        'province_id',
        'municipality_id',
        'mov_link'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function team()
    {
        return $this->belongsToMany(Team::class, 'team_barangays', 'barangay_id', 'team_id');
    }

    public function pkProfile()
    {
        return $this->hasOne(BarangayPKProfile::class,'barangay_id');
    }

    public function organizationalIndicators()
    {
        return $this->hasMany(BarangayOrganizationalIndicator::class,'barangay_id');
    }

    public function indicatorTargets()
    {
        return $this->hasMany(BarangayIndicatorTarget::class,'barangay_id');
    }

    public function geography()
    {
        return $this->hasOne(BarangayGeography::class,'barangay_id');
    }

    public function population()
    {
        return $this->hasOne(BarangayPopulation::class,'barangay_id');
    }

    public function priorityPrograms()
    {
        return $this->hasMany(BarangayPriorityProgram::class,'barangay_id')->with('program');
    }

    public function reports()
    {
        return $this->hasMany(Report::class,'barangay_id','id');
    }

    public function pkActivities()
    {
        return $this->belongsToMany(
            PurokalusuganActivity::class,
            'pk_activity_barangays',  // pivot table (matches $table in your model)
            'barangay_id',            // FK for this model on pivot
            'pk_activity_id'          // FK for related model on pivot
        );
    }


}
