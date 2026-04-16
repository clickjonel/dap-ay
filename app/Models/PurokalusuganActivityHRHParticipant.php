<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['pk_activity_id', 'user_id'])]
#[Table('purokalusugan_activity_hrh_participants')]
#[WithoutTimestamps]

class PurokalusuganActivityHRHParticipant extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
