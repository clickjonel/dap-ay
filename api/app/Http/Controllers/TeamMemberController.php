<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'nullable|exists:users,id',
            'doh_deployed' => 'required|boolean',
            'name' => 'required|string',
            'position' => 'required|string',
            'role' => 'required|string',
            'pk_oriented' => 'required|boolean',
            'received_pk_kit' => 'required|boolean' 
        ]);

        TeamMember::create($validated);

        return response()->json('Team Member Added Successfully',201);
    }
}
