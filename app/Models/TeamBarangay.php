<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamBarangay extends Model
{
    protected $fillable = ['team_id', 'barangay_id'];

    public $timestamps = false;
}
