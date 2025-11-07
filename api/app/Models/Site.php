<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_sites';
    protected $primaryKey = 'site_id';
    public $timestamps = false;
    protected $fillable = [
        'barangay_id',
        'latitude',
        'longitude'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function profile()
    {
        return $this->hasOne(SiteProfile::class, 'site_id', 'site_id');
    }
}
