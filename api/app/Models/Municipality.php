<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'municipalities';
    public $timestamps = false;
    protected $fillable = [
        'province_id',
        'name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id');
    }

     public function reports()
    {
        return $this->hasManyThrough(
            Report::class,
            Barangay::class,
            'municipality_id',
            'barangay_id',
            'id',
            'id'
        );
    }

}
