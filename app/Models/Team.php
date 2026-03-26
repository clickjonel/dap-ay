<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'pk_kit',
        'eo_link',
        'created_by',
    ];
    public $timestamps = false;
    public $casts = [
        'is_active' => 'boolean',
        'pk_kit' => 'boolean',
    ];

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function barangays()
    {
        return $this->belongsToMany(Barangay::class, 'team_barangays');
    }

}
