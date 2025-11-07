<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_team_members';
    protected $primaryKey = 'team_member_id';
    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'member_name',
        'member_position',
        'member_oriented',
        'member_trained',
        'pk_kit_receipt',
        'site_name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }
}
