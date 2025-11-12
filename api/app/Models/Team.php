<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'teams';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // public function barangay()
    // {
    //     return $this->belongsTo(Barangay::class,'barangay_id','barangay_id');
    // }

    // public function members()
    // {
    //     return $this->hasMany(TeamMember::class,'team_id','team_id');
    // }
}
