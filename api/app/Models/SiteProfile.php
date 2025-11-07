<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteProfile extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'pk_site_profiles';
    protected $primaryKey = 'profile_id';
    public $timestamps = false;
    protected $fillable = [
        'site_id',
        'total_purok',
        'total_sitio',
        'total_target_purok',
        'total_target_sitio',
        'total_target_population'
    ];

    public function priorityPrograms()
    {
        return $this->hasMany(SiteProgram::class,'profile_id','profile_id');
    }
}
