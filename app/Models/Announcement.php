<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'announcement',
    'target_access_level',
])]
#[Appends(['target_access_level_label'])]

class Announcement extends Model
{
    public function getTargetAccessLevelLabelAttribute(){
        return match ($this->target_access_level) {
            2 => 'HRH Accounts',
            3 => 'PDOHO Accounts',
            4 => 'DMO Accounts',
            default => 'Unknown',
        };
    }
}
