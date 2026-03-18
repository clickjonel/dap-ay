<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'psgc_code',
        'name',
        'province_id'
    ];

    public function barangays()
    {
        return $this->hasMany(Barangay::class);
    }
    
}
