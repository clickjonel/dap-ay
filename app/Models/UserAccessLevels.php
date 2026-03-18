<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessLevels extends Model
{
    protected $fillable = [
        'user_id',
        'access_level',
    ]; 
    public $timestamps = false;
}
