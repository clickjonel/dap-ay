<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userBarangayIDs = $request->user()
        ->teams()
        ->with('barangays:id')
        ->get()
        ->pluck('barangays')
        ->flatten()
        ->pluck('id')
        ->unique();

        $barangays = Barangay::query()
                        ->when($request->user()->accessLevels->access_level === 2, function ($query) use ($userBarangayIDs) {
                            $query->whereIn('id', $userBarangayIDs);
                        })
                        ->when($request->user()->accessLevels->access_level === 3, function ($query) use ($request) {
                            $query->where('province_id', $request->user()->accessLevels->pdoho_access_id);
                        })
                        ->when($request->search, function ($query, $search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('psgc_code', 'like', "%{$search}%");
                        })
                        ->with([
                            'province',
                            'municipality',
                            'pkProfile',
                            'organizationalIndicators',
                            'geography',
                            'population'
                        ])
                        ->orderBy('id','desc')
                        ->paginate(10)
                        ->withQueryString();

        return inertia('barangay/barangays', [
            'barangays' => $barangays,
            'filters'   => $request->only('search'),
            'provinces'      => Inertia::lazy(fn () => Province::with(['municipalities'])->orderBy('name')->get()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('barangay/createBarangay', [
            'provinces' => Province::with('municipalities:id,name,province_id')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'psgc_code'       => 'nullable|string|unique:barangays,psgc_code,' . ($barangay->id ?? 'NULL'),
            'name'            => 'required|string|max:255',
            'province_id'     => 'required|exists:provinces,id',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        Barangay::create($request->only('psgc_code', 'name', 'province_id', 'municipality_id'));

        return redirect()->route('barangays.index')->with('success', 'Barangay created successfully.');


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
        $barangay = Barangay::with(['province.municipalities', 'municipality'])->findOrFail($id);

        return inertia('barangay/updateBarangay', [
            'barangay' => $barangay,
            'provinces' => Province::with(['municipalities'])->orderBy('name')->get()
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barangay $barangay)
    {
        $request->validate([
            'psgc_code'       => 'nullable|string|unique:barangays,psgc_code,' . $barangay->id,
            'name'            => 'required|string|max:255',
            'province_id'     => 'required|exists:provinces,id',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        $barangay->update($request->only('psgc_code', 'name', 'province_id', 'municipality_id'));

        // return redirect()->route('barangays')->with('success', 'Barangay updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
