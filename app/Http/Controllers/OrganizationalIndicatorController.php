<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalIndicator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationalIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orgIndicators = OrganizationalIndicator::all();

        return Inertia::render('indicator/organizational/organizationalIndicators', [
            'orgIndicators' => $orgIndicators,
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
            'is_active' => 'nullable|boolean',
        ]);

        OrganizationalIndicator::create($request->all());
        return back()->with('success', 'Organizational Indicator created successfully.');}

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
        $indicator = OrganizationalIndicator::findOrFail($id);

        $request->validate([
            'indicator_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $indicator->update($request->all());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $indicator = OrganizationalIndicator::findOrFail($id);
        
        $indicator->update([
            'is_active' => !$indicator->is_active,
        ]);

        return back()->with('success', 'Organizational Indicator updated successfully.');
    }
}
