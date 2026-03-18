<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use Illuminate\Http\Request;

class DisaggregationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('disaggregation/disaggregations', [
            'disaggregations' => Disaggregation::all(),
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
            'disaggregation_name' => 'required|string|max:255',
            'group'                => 'required|string|max:255',
            'is_active'           => 'boolean',
        ]);
        Disaggregation::create($request->only('disaggregation_name', 'group', 'is_active'));
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Disaggregation $disaggregation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disaggregation $disaggregation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disaggregation $disaggregation)
    {
        $request->validate([
            'disaggregation_name' => 'required|string|max:255',
            'group'                => 'nullable|string|max:255',
            'is_active'           => 'boolean',
        ]);
        $disaggregation->update($request->only('disaggregation_name', 'group', 'is_active'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disaggregation $disaggregation)
    {
        $disaggregation->update(['is_active' => !$disaggregation->is_active]);
        return back();
    }
}
