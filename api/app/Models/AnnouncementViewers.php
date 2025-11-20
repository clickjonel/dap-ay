<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementViewers extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'announcements';
    protected $fillable = [
        'announcement_id',
        'team_id'
    ];
}
