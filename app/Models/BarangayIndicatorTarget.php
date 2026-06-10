<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Attributes\Connection;

#[Fillable([
    'barangay_id',
    'program_indicator_id',
    'program_id',
    'served',
    'target'
])]
#[Table('barangay_indicator_targets')]
class BarangayIndicatorTarget extends Model
{
    //
}
