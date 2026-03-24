<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurokalusuganActivityBarangay extends Model
{
    protected $table = 'pk_activity_barangays';
    protected $fillable = [
        'pk_activity_id',
        'barangay_id',
    ];
    public $timestamps = false;

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function pkActivity()
    {
        return $this->belongsTo(PurokalusuganActivity::class, 'pk_activity_id');
    }

}
