<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'psgc_code',
        'name',
    ];

    public function municipalities(): HasMany
    {
        return $this->hasMany(Municipality::class);
    }

    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class,'province_id');
    }
    
}
