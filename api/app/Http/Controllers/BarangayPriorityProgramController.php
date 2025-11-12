<?php

namespace App\Http\Controllers;

use App\Models\BarangayPriorityProgram;
use Illuminate\Http\Request;

class BarangayPriorityProgramController extends Controller
{
    public function update(Request $request) 
    {
        $validated = $request->validate([
            'id' => 'required|exists:barangay_priority_programs,id',
            'baseline' => 'nullable|numeric',
            'order' => 'nullable|numeric'
        ]);

        BarangayPriorityProgram::find($validated['id'])->update($validated);

        return response()->json('Updated Priority Program',201);
    }

    public function create(Request $request) 
    {
        $validated = $request->validate([
            'sub_program_id' => 'required|exists:sub_programs,id',
            'baseline' => 'nullable|numeric',
            'order' => 'nullable|numeric',
            'barangay_id' => 'required|exists:barangays,id'
        ]);

        BarangayPriorityProgram::create($validated);

        return response()->json('Updated Priority Program',201);
    }

}
