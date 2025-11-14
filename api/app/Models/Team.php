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

    public function members()
    {
        return $this->hasMany(TeamMember::class,'team_id','id');
    }

    public function barangays()
    {
        return $this->belongsToMany(Barangay::class, 'team_barangays', 'team_id', 'barangay_id');
    }
}
