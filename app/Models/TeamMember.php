<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'user_id',
        'role',
        'position',
        'pk_oriented',
    ];

    public $timestamps = false;
    protected $casts = [
        'pk_oriented' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
