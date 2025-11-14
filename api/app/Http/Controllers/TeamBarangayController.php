<?php

namespace App\Http\Controllers;

use App\Models\TeamBarangay;
use Illuminate\Http\Request;

class TeamBarangayController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'barangay_id' => 'required|exists:barangays,id',
        ]);

        TeamBarangay::create($validated);

        return response()->json('Team Barangay Added Successfully',201);
    }
}
