<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'barangay_id',
        'total_clients',
        'total_returning_clients',
        'date',
        'remarks',
        'status',
        'action_by',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'report_users');
    }

    public function values()
    {
        return $this->hasMany(ReportValue::class);
    }
}
