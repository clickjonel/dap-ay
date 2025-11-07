<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_barangays';
    protected $primaryKey = 'barangay_id';
    public $timestamps = false;
    protected $fillable = [
        'province_id',
        'municipality_id',
        'barangay_name',
    ];

    public function municipality()
    {
         return $this->belongsTo(Municipality::class, 'municipality_id', 'municipality_id');
    }

    public function province()
    {
         return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

}
