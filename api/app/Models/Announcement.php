<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'announcements';
    protected $fillable = [
        'created_by_id',
        'date_start',
        'date_end',
        'title',
        'details',
        'image_url_source'
    ];

    //Defined relationships
    public function user(){
        return $this->belongsTo(User::class,'created_by_id','id');
    }
}
