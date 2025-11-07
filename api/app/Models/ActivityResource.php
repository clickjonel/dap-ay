<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityResource extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_activity_resources';
    protected $primaryKey = 'activity_resource_id';
    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'resource_name',
        'resource_type',
        'resource_total_beneficiary',
        'resource_amount'
    ];
}
