<?php

namespace App\Http\Controllers;

use App\Models\ProgramIndicator;
use App\Models\ProgramIndicatorDisaggregation;
use Illuminate\Http\Request;

class ProgramIndicatorDisaggregationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'program_indicator_id' => 'required|exists:program_indicators,id',
            'disaggregation_ids' => 'required|array',
            'disaggregation_ids.*' => 'exists:disaggregations,id',
        ]);

        $programIndicator = ProgramIndicator::find($request->program_indicator_id);
        $programIndicator->disaggregations()->sync($request->disaggregation_ids);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramIndicatorDisaggregation $programIndicatorDisaggregation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramIndicatorDisaggregation $programIndicatorDisaggregation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramIndicatorDisaggregation $programIndicatorDisaggregation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramIndicatorDisaggregation $programIndicatorDisaggregation)
    {
        //
    }
}
