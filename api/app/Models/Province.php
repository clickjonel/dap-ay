<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'provinces';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function municipalities()
    {
        return $this->hasMany(Municipality::class,'province_id','id');
    }
    public function reports()
    {
        return $this->hasManyThrough(
            Report::class,
            Barangay::class,
            'province_id',
            'barangay_id',
            'id',
            'id'
        );
    }
}
