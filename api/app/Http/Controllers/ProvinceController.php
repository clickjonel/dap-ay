<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function selection()
    {
        $provinces = Province::get();

        return response()->json($provinces);
    }
}
