<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamBarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $barangays = Barangay::where('province_id', Auth::user()->accessLevels->pdoho_access_id)->get();

        return inertia('team/manageBarangays', [
            'team'      => $team->load('barangays.municipality'),
            'barangays' => $barangays,
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
    public function store(Team $team,Request $request)
    {
        TeamBarangay::create([
            'team_id' => $team->id,
            'barangay_id' => $request->barangay_id,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamBarangay $teamBarangay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamBarangay $teamBarangay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamBarangay $teamBarangay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, Barangay $barangay)
    {
        TeamBarangay::where('team_id', $team->id)
            ->where('barangay_id', $barangay->id)
            ->delete();

        return back();
    }
}
