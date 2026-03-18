<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramGroup;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_program_id' => 'required|exists:program_groups,id',
            'is_active' => 'required|boolean',
        ]);

        Program::create($request->all());

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
    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_program_id' => 'required|exists:program_groups,id',
            'is_active' => 'required|boolean',
        ]);

        $program->update($request->all());

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
