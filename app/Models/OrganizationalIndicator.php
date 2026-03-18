<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalIndicator extends Model
{
    protected $fillable = [
        'indicator_name',
        'is_active',
    ];
    public $timestamps = false;


}
