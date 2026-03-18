<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use App\Models\Program;
use App\Models\ProgramIndicator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programIndicators = ProgramIndicator::with(['program','disaggregations'])->get();

        return Inertia::render('indicator/program/programIndicators', [
            'programIndicators' => $programIndicators,
            'programs' => Inertia::lazy(fn () => Program::orderBy('name')->get()),
            'disaggregations' => Inertia::lazy(fn () => Disaggregation::orderBy('group')->get()),
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
            'indicator_name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'is_active' => 'nullable|boolean',
        ]);

        ProgramIndicator::create($request->all());
        return back()->with('success', 'Program Indicator created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'indicator_name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'is_active' => 'nullable|boolean',
        ]);

        $indicator = ProgramIndicator::findOrFail($id);
        $indicator->update($request->all());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $indicator = ProgramIndicator::findOrFail($id);
        
        $indicator->update([
            'is_active' => !$indicator->is_active,
        ]);

        return back()->with('success', 'Program Indicator updated successfully.');
    }
}
