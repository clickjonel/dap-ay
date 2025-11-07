<?php

namespace App\Models;

use App\Casts\ActivityPartners;
use App\Casts\ActivityProponents;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_activities';
    protected $primaryKey = 'activity_id';

    protected $fillable = [
        'activity_name',
        'activity_venue',
        'activity_budget',
        'activity_actual_budget',
        'activity_fund_source',
        'activity_proponents',
        'activity_partners',
        'activity_date_start',
        'activity_date_end',
        'activity_type',      
        'activity_scope',  
        'date_populated'
    ];

    protected $casts = [
        'activity_partners' => ActivityPartners::class,
        'activity_proponents' => ActivityProponents::class,
    ];

    public function activityPrograms()
    {
        return $this->hasMany(ActivityProgram::class, 'activity_id', 'activity_id');
    }

    public function activityBarangays()
    {
        return $this->hasMany(ActivityBarangay::class, 'activity_id', 'activity_id');
    }

    public function activityResources()
    {
        return $this->hasMany(ActivityResource::class, 'activity_id', 'activity_id');
    }

    public function activityValues()
    {
        return $this->hasMany(ActivityValue::class, 'activity_id', 'activity_id');
    }

}
