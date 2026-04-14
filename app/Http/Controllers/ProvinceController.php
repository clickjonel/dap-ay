<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function renderProvinceDashboardData($id)
    {
        $province = Province::with([
            'municipalities.barangays'
        ])->findOrFail($id);


        return response()->json($province);
    }
}
