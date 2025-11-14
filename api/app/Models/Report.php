<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'reports';

    protected $fillable = [
        'created_by',
        'start',
        'end',
        'barangay_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class,'barangay_id','id');
    }

    public function values()
    {
        return $this->hasMany(ReportValue::class,'report_id','id');
    }

}
