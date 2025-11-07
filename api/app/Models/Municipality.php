<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_municipalities';
    protected $primaryKey = 'municipality_id';
    public $timestamps = false;
    protected $fillable = [
        'province_id',
        'municipality_name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','province_id');
    }
}
