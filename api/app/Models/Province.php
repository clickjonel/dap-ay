<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_provinces';
    protected $primaryKey = 'province_id';
    public $timestamps = false;
    protected $fillable = [
        'province_name',
    ];
}
