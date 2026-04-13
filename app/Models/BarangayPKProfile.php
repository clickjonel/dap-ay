<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayPKProfile extends Model
{
    protected $fillable = [
        'barangay_id',
        'pk_status',
        'pk_site',
    ];
    protected $table = 'barangay_pk_profiles';
    protected $casts = [
        'pk_site' => 'boolean'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class,'barangay_id');
    }

}
