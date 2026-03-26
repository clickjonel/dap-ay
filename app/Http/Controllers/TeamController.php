<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $userAccessLevel = $user->accessLevels?->access_level ?? 2;
        $userTeamIDs = $user->teams->pluck('id')->toArray() ?? [];

        $teams = Team::query()
            ->with(['members','barangays'])

            //pdoho access
            ->when($userAccessLevel === 3, function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->whereHas('barangays', function ($query) use ($user) {
                        $query->where('province_id', $user->accessLevels->pdoho_access_id);
                    })
                    ->orWhereDoesntHave('barangays')
                    ->orWhere('created_by', $user->id);
                });
            })

            //hrh access
            ->when($userAccessLevel === 2, function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->whereHas('members', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->orWhereDoesntHave('members');
                });
            })
            
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->with([
                
            ])
            ->orderBy('id','desc')
            ->paginate(20)
            ->withQueryString();

        return inertia('team/teams', [
            'teams' => $teams,
            'filters'   => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'pk_kit' => 'required|boolean',
            'eo_link' => 'nullable',
        ]);

        Team::create(array_merge($validated, [
            'created_by' => $request->user()->id,
        ]));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $team = $team->load([
            'members',
        ]);
        $users = User::get();

        return inertia('team/updateTeam', [
            'team' => $team,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'pk_kit' => 'required|boolean',
            'eo_link' => 'nullable',
        ]);

        $team->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }

}
