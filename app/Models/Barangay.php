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
        'municipality_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function pkProfile()
    {
        return $this->hasOne(BarangayPKProfile::class,'barangay_id');
    }

    public function organizationalIndicators()
    {
        return $this->hasMany(BarangayOrganizationalIndicator::class,'barangay_id');
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


}
