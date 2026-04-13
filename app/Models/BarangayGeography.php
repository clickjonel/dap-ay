<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayGeography extends Model
{
    protected $fillable = [
        'barangay_id',
        'longitude',
        'latitude',
        'is_gida',
    ];

    protected $casts = [
        'is_gida' => 'boolean'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
