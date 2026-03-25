<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessLevels extends Model
{
    protected $fillable = [
        'user_id',
        'pdoho_access_id',
        'access_level',
    ]; 
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'pdoho_access_id');
    }
}
