<?php

namespace App\Http\Controllers;

use App\Models\ProgramBaseline;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramBaselineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programData = Program::with(['baseline'])->findOrFail($request->program);

        return Inertia::render('program/manageProgramBaseline', [
            'program' => $programData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'target' => 'required|numeric',
        ]);

        ProgramBaseline::updateOrCreate(
            ['program_id' => $validatedData['program_id']],
            ['target' => $validatedData['target']]
        );

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramBaseline $programBaseline)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramBaseline $programBaseline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramBaseline $programBaseline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramBaseline $programBaseline)
    {
        //
    }
}
