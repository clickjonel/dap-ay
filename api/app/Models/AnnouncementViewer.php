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
}
