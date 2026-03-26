<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramGroup;
use App\Http\Requests\Program\CreateProgramRequest;
use Inertia\Inertia;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::with(['group'])->get();
        $groups = ProgramGroup::all();

        return Inertia::render('program/programs', [
            'programs' => $programs,
            'groups' => $groups,
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
    public function store(CreateProgramRequest $request)
    {
        Program::create($request->validated());

        return redirect()->route('program.index')->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProgramRequest $request, Program $program)
    {
        $program->update($request->validated());

        return redirect()->route('program.index')->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->update([
            'is_active' => !$program->is_active,
        ]);

        return redirect()->route('program.index')->with('success', 'Program updated successfully.');
    }
}
