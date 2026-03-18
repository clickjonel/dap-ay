<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayPopulation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayPopulationController extends Controller
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
    public function create(Request $request)
    {
        $barangay = Barangay::with(['municipality','province'])->find($request->barangay_id);

        return Inertia::render('barangay/createBarangayPopulation', [
            'barangay' => $barangay
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,id',
            'total_population'  => 'nullable|integer',
            'target_population' => 'nullable|integer',
            'total_puroks'      => 'nullable|integer',
            'target_puroks'     => 'nullable|integer',
            'total_households'  => 'nullable|integer',
            'target_households' => 'nullable|integer',
        ]);

        Barangay::findOrFail($validated['barangay_id'])
            ->population()
            ->create($validated);

        return redirect('/barangays');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangayPopulation $barangayPopulation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $population = BarangayPopulation::with(['barangay.province', 'barangay.municipality'])->findOrFail($id);

        return Inertia::render('barangay/updateBarangayPopulation', [
            'barangay'   => $population->barangay,
            'population' => $population,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'total_population'  => 'nullable|integer',
            'target_population' => 'nullable|integer',
            'total_puroks'      => 'nullable|integer',
            'target_puroks'     => 'nullable|integer',
            'total_households'  => 'nullable|integer',
            'target_households' => 'nullable|integer',
        ]);

        BarangayPopulation::findOrFail($id)->update($validated);

        return redirect('/barangays');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangayPopulation $barangayPopulation)
    {
        //
    }
}
