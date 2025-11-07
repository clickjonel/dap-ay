<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function selection(Request $request)
    {
        $municipalities = Municipality::where('province_id',$request->province_id)->get();

        return response()->json($municipalities);
    }
}
