<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayPKProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayPKProfileController extends Controller
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
        $barangay = Barangay::with(['province','municipality'])->find($request->barangay_id);

        return Inertia::render('barangay/createBarangayPKProfile', [
            'barangay' => $barangay,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'pk_status'   => 'required|string',
            'pk_site'     => 'required|boolean',
        ]);

        BarangayPKProfile::create($request->only('barangay_id', 'pk_status', 'pk_site'));

        return back();
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
        $pk_profile = BarangayPKProfile::with(['barangay.province','barangay.municipality'])->findOrFail($id);

        return inertia('barangay/updateBarangayPKProfile', [
            'pk_profile' => $pk_profile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangayPkProfile $pkProfile)
    {
        $request->validate([
            'pk_status' => 'required|string',
            'pk_site'   => 'required|boolean',
        ]);

        $pkProfile->update([
            'pk_status' => $request->pk_status,
            'pk_site'   => $request->pk_site,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
