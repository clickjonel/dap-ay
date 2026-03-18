<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayPopulation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'barangay_id',
        'total_population',
        'target_population',
        'total_puroks',
        'target_puroks',
        'total_households',
        'target_households'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
