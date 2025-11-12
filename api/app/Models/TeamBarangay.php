<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamBarangay extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'team_barangays';
    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'barangay_id',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class,'barangay_id','id');
    }

}
