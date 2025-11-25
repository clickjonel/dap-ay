<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementViewer extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'announcement_viewers';
    protected $fillable = [
        'announcement_id',
        'team_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }
}
