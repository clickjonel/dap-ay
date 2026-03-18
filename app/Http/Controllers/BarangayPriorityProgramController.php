<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayPriorityProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barangay = Barangay::with(['province','municipality','priorityPrograms'])->findOrFail($request->barangay_id);
        $programs = Program::whereNotIn('id', $barangay->priorityPrograms->pluck('program_id'))->get();

        return Inertia::render('barangay/manageBarangayPriorityPrograms', [
            'barangay' => $barangay,
            'programs' => $programs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'program_id' => 'required|exists:programs,id',
            'target' => 'required|numeric',
            'order' => 'required|integer',
        ]);

        BarangayPriorityProgram::create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangayPriorityProgram $barangayPriorityProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangayPriorityProgram $barangayPriorityProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangayPriorityProgram $priorityProgram)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'target' => 'required|numeric',
            'order' => 'required|integer',
        ]);

        $priorityProgram->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangayPriorityProgram $priorityProgram)
    {
        $priorityProgram->delete();

        return back();
    }
}
