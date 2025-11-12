<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'team_members';
    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'user_id',
        'doh_deployed',
        'name',
        'position',
    ];

}
