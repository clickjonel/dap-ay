<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    public function create(Request $request)
    {   
        Indicator::create($request->all());

        return response()->json('Indicator has been created.',201);
    }
}
