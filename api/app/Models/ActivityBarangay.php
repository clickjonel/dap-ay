<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityBarangay extends Model
{
    
    protected $connection = 'dap-ay';
    protected $table = 'pk_activity_barangays';
    protected $primaryKey = 'activity_barangay_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'barangay_id',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }
}
