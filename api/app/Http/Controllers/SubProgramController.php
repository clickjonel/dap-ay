<?php

namespace App\Http\Controllers;

use App\Models\SubProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubProgramController extends Controller
{
    public function create(Request $request)
    {   
        $validated = $request->validate([
            'program_id' => 'required|numeric|exists:programs,id',
            'name' => 'required|string',
            'code' => 'required|string|unique:sub_programs,code',
            'active' => 'required|boolean'
        ]);

        $subProgram = SubProgram::create($validated);

        return response()->json('Created Sub Program Successfully',201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'program_id' => 'required|numeric|exists:programs,id',
            'name' => 'required|string',
            'code' => Rule::unique('sub_programs', 'code')->ignore($request->input('id'), 'id'),
            'active' => 'required|boolean'
        ]);

            SubProgram::find($validated['id'])->update($validated);

        return response()->json('Updated Sub Program Successfully',201);
    }

    public function list()
    {

    }

    public function selection()
    {
        $saubPrograms = SubProgram::where('active',1)->get();

        return response()->json($saubPrograms);
    }
}
