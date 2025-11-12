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
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Program::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                 $query->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('code', 'LIKE', "%{$keyword}%");
                })
                ->with(['subPrograms'])
                ->simplePaginate(10);

        return response()->json($list,200);
    }

    public function selection()
    {
        $programs = Program::where('active',1)->get();

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
            'program_id' => $program->id,
            'name' => $program->name,
            'code' => $program->code,
            'active' => $program->active,
        ]);

        return response()->json('Program created successfully.',201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'code' => Rule::unique('programs', 'code')->ignore($request->input('id'), 'id'),
            'active' => 'required|boolean'
        ]);

        Program::find($validated['id'])->update($validated);

        return response()->json('Updated Program Successfully',201);
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
