<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BarangayIndicatorTarget;
use Illuminate\Http\Request;

class BarangayIndicatorTargetController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validated([
            'barangay_id' => 'required|exists:barangays,id',
            'program_indicator_id' => 'required|exists:program_indicators,id',
            'program_id' => 'required|exists:programs,id',
            'served' => 'required|numeric',
            'target' => 'required|numeric'
        ]);

        BarangayIndicatorTarget::create($validated);

        return back();

    }
}
