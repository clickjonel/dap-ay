<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSignatory extends Model
{
    protected $connection = 'dap-ay';
    protected $table = 'report_signatories';

    protected $fillable = [
       'report_id',
       'action',
       'name',
       'position'
    ];
}
