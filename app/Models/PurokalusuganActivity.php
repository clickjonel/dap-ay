<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurokalusuganActivity extends Model
{
    protected $fillable = [
        'date_start',
        'date_end',
        'activity_name',
        'type',
        'total_clients',
    ];

    public function barangays()
    {
        return $this->belongsToMany(Barangay::class, 'pk_activity_barangays', 'pk_activity_id', 'barangay_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'pk_activity_programs', 'pk_activity_id', 'program_id');
    }


}
