<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;


#[Fillable(['user_id','municipality_id','dcpo_no'])]
#[Table('user_handled_municipalities')]
#[WithoutTimestamps]

class UserHandledMunicipality extends Model
{
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
