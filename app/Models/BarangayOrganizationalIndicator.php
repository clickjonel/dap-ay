<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOrganizationalIndicator extends Model
{
    protected $fillable = [
        'barangay_id',
        'org_indicator_id',
        'value',
        'facility_based',
        'community_based',
    ];
    public $timestamps = false;

    // public function barangay()
    // {
    //     return $this->belongsTo(Barangay::class);
    // }

    // public function organizationalIndicator()
    // {
    //     return $this->belongsTo(OrganizationalIndicator::class);
    // }
}
