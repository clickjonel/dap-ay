<?php

namespace App\Http\Controllers;

use App\Http\Requests\Program\CreateIndicatorRequest;
use App\Http\Requests\Program\FindProgramRequest;
use App\Http\Requests\Program\CreateProgramRequest;
use App\Http\Requests\Program\CreateSubProgramRequest;
use App\Http\Requests\Program\UpdateIndicatorRequest;
use App\Http\Requests\Program\UpdateProgramRequest;
use App\Http\Requests\Program\UpdateSubProgramRequest;
use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\SubProgram;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Program::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                 $query->where('program_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('program_code', 'LIKE', "%{$keyword}%");
                })
                ->with(['subPrograms'])
                ->simplePaginate(10);

        return response()->json($list,200);
    }

    public function selection()
    {
        $programs = Program::where('program_active',1)->get();

        return response()->json($programs);
    }

    public function subProgramSelection()
    {
        $sub_programs = SubProgram::with(['program'])->where('sub_program_active',1)->get();

        return response()->json($sub_programs);
    }

    public function create(CreateProgramRequest $request)
    {
        $validated = $request->validated();
        $program = Program::create($validated);
        SubProgram::create([
            'program_id' => $program->program_id,
            'sub_program_name' => $program->program_name,
            'sub_program_code' => $program->program_code,
            'sub_program_active' => $program->program_active,
        ]);

        return response()->json('Program created successfully, add sub programs if applicable',201);
    }

    public function update($program_id,UpdateProgramRequest $request)
    {
        $validated = $request->validated();

        $program = Program::findOrFail($program_id);

        if($program) $program->update($validated);

        return response()->json('Program updated successfully.',201);
    }

    public function createSubProgram(CreateSubProgramRequest $request)
    {
        $validated = $request->validated();
        SubProgram::create($validated);

        return response()->json('Sub Program created successfully and synced to selected program',201);
    }

    public function updateSubProgram(UpdateSubProgramRequest $request)
    {
        $validated = $request->validated();
        SubProgram::find($validated['sub_program_id'])->update([
            'sub_program_name' => $validated['sub_program_name'],
            'sub_program_code' => $validated['sub_program_code'],
            'sub_program_active' => $validated['sub_program_active']
        ]);

        return response()->json('Sub Program created successfully and synced to selected program',201);
    }

    public function findProgram(Request $request)
    {
        $program_id = $request->program_id;
        $relationships = $request->relationships ?? [];

        $program = Program::with($relationships)->findOrFail($program_id);

        return response()->json($program,200);
    }

    public function updateIndicator(UpdateIndicatorRequest $request)
    {
        $validated = $request->validated();
        ProgramIndicator::find($validated['program_indicator_id'])->update([
            'indicator_name' => $validated['indicator_name'],
            'indicator_code' => $validated['indicator_code'],
            'indicator_active' => $validated['indicator_active'],
            'sub_program_id' => $validated['sub_program_id']
        ]);

        return response()->json('Indicator updated successfully and synced to selected program',201);
    }

    public function createIndicator(CreateIndicatorRequest $request)
    {
        $validated = $request->validated();
        ProgramIndicator::create([
            'indicator_name' => $validated['indicator_name'],
            'indicator_code' => $validated['indicator_code'],
            'indicator_active' => $validated['indicator_active'],
            'sub_program_id' => $validated['sub_program_id'],
            'indicator_type' => 'Program Specific'
        ]);

        return response()->json('Indicator Created successfully and synced to selected program',201);
    }


}
