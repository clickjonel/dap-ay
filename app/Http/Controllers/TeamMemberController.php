<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team)
    {
        $userAuthenticated = Auth::user();
        $users = User::query()
            ->when($userAuthenticated->accessLevels->access_level === 2 | $userAuthenticated->accessLevels->access_level === 3, function($query) use ($userAuthenticated){
                $query->whereHas('accessLevels', function ($query) use ($userAuthenticated) {
                    $query->where('pdoho_access_id', $userAuthenticated->accessLevels->pdoho_access_id);
                });
            })
            ->whereHas('accessLevels', function ($query) {
                $query->where('access_level', '!=', 1)
                    ->where('access_level', '!=', 3);
            })
            ->get();

        return Inertia::render('team/manageMembers', [
            'team'    => $team,
            'members' => $team->members->load(['user']),
            'users' => $users
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
    public function store(Request $request, Team $team)
    {
        $validated =$request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'role' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'pk_oriented' => 'required|boolean',
        ]);

        $team->members()->create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamMember $teamMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamMember $teamMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, TeamMember $member)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'user_id'     => 'nullable|exists:users,id',
            'role'        => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'pk_oriented' => 'required|boolean',
        ]);

        $member->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, TeamMember $team_member)
    {
        $team_member->delete();

        return back();
    }
}
