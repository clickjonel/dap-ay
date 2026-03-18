<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayGeography;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayGeographyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $barangay = Barangay::with(['pkProfile', 'organizationalIndicators'])->find($request->barangay_id);

        return Inertia::render('barangay/createBarangayGeography', [
            'barangay' => $barangay,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'longitude'   => 'required|numeric',
            'latitude'    => 'required|numeric',
            'is_gida'     => 'required|boolean',
        ]);

        $barangay = Barangay::findOrFail($validated['barangay_id']);
        $barangay->geography()->create($validated);

        return redirect('/barangays');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangayGeography $barangayGeography)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $geography = BarangayGeography::with(['barangay.province', 'barangay.municipality'])->findOrFail($id);

        return inertia('barangay/updateBarangayGeography', [
            'barangay'  => $geography->barangay,
            'geography' => $geography,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'longitude' => 'required|numeric',
            'latitude'  => 'required|numeric',
            'is_gida'   => 'required|boolean',
        ]);

        BarangayGeography::findOrFail($id)->update($validated);

        return redirect('/barangays');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangayGeography $barangayGeography)
    {
        //
    }
}
