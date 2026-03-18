<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'report_id',
        'user_id',
    ];
}
