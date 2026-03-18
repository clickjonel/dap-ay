<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disaggregation extends Model
{
    protected $fillable = [
        'disaggregation_name',
        'is_active',
        'group'
    ];
    public $timestamps = false;

}
