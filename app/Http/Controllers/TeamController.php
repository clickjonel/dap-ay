<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamBarangay;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load('accessLevels'); 
        $userAccessLevel = $user->accessLevels?->access_level ?? 2;
    
        $teams = Team::query()
            ->with([
                'members',
                'barangays.municipality',
                'barangays.province',
            ])
    
            ->when($userAccessLevel === 3, function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->whereHas('barangays', fn($q) =>
                        $q->where('province_id', $user->accessLevels->pdoho_access_id)
                    )
                    ->orWhere('created_by', $user->id);
                });
            })
    
            ->when($userAccessLevel === 2, function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->whereHas('members', fn($q) =>
                        $q->where('user_id', $user->id)
                    )
                    ->orWhereDoesntHave('members');
                });
            })
    
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('members',function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('barangays',function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
            })
    
            ->orderBy('id', 'desc')
            ->cursorPaginate(20)
            ->withQueryString();
    
        return inertia('team/teams', [
            'teams'   => $teams,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user()->load('accessLevels'); 
        $barangays = Barangay::query()
                        ->with(['municipality','province'])
                        ->when($user->accessLevels->access_level === 2, function($query) use($user){
                            $query->where('province_id',$user->accessLevels->pdoho_access_id);
                        })
                        ->get();
        $users = User::query()
                        ->select('id', 'name')
                        ->when($user->accessLevels->access_level === 2, function($query) use($user){
                            $query->whereHas('accessLevels', function($query) use ($user){
                                $query->where('pdoho_access_id',$user->accessLevels->pdoho_id);
                            });
                        })
                        ->whereHas('accessLevels', function($query) use ($user){
                            $query->where('access_level',2);
                        })
                        ->orderBy('name','asc')
                        ->get();

        return inertia('team/createTeam',[
            'barangays' => $barangays,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pk_kit' => 'required|boolean',
            'eo_link' => 'nullable|string',
            'barangays' => 'required|array|min:1',
            'members' => 'required|array|min:1',
            'members.*.name' => 'required|string',
            'members.*.role' => 'required|string',
            'members.*.position' => 'required|string',
            'members.*.user_id' => 'nullable|numeric',
            'members.*.pk_oriented' => 'required|boolean',
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'is_active' => true,
            'pk_kit' => $validated['pk_kit'],
            'eo_link' => $validated['eo_link'],
            'created_by' => $request->user()->id,
        ]);

        foreach($validated['barangays'] as $brgy){
            TeamBarangay::create([
                'team_id' => $team->id, 
                'barangay_id' => $brgy
            ]);
        }

        foreach($validated['members'] as $member){
            TeamMember::create([
                'team_id' => $team->id,
                'name' => $member['name'],
                'user_id' => $member['user_id'],
                'role' => $member['role'],
                'position' => $member['position'],
                'pk_oriented' => $member['pk_oriented'],
            ]);
        }

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
    public function edit(Team $team, Request $request)
    {
        $team->load([
            'barangays:id,name',
            'members.user:id,name'
        ]);
        $user = $request->user()->load('accessLevels'); 
        $barangays = Barangay::query()
                        ->with(['municipality','province'])
                        ->when($user->accessLevels->access_level === 2, function($query) use($user){
                            $query->where('province_id',$user->accessLevels->pdoho_access_id);
                        })
                        ->get();
        $users = User::query()
                        ->select('id', 'name')
                        ->when($user->accessLevels->access_level === 2, function($query) use($user){
                            $query->whereHas('accessLevels', function($query) use ($user){
                                $query->where('pdoho_access_id',$user->accessLevels->pdoho_access_id);
                            });
                        })
                        ->whereHas('accessLevels', function($query) use ($user){
                            $query->where('access_level',2);
                        })
                        ->orderBy('name','asc')
                        ->get();

        return inertia('team/manageTeam',[
            'barangays' => $barangays,
            'users' => $users,
            'team' => $team
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'pk_kit'                => 'required|boolean',
            'eo_link'               => 'nullable|string',
            'barangays'             => 'required|array|min:1',
            'members'               => 'required|array|min:1',
            'members.*.id'          => 'nullable|integer|exists:team_members,id',
            'members.*.name'        => 'required|string',
            'members.*.role'        => 'required|string',
            'members.*.position'    => 'required|string',
            'members.*.user_id'     => 'nullable|numeric',
            'members.*.pk_oriented' => 'required|boolean',
        ]);
    
        $team->update([
            'name'    => $validated['name'],
            'pk_kit'  => $validated['pk_kit'],
            'eo_link' => $validated['eo_link'],
        ]);
    
        // sync barangays — delete removed, insert new
        $team->barangays()->sync($validated['barangays']);
    
        // delete members not in the incoming list
        $incomingMemberIds = collect($validated['members'])
            ->pluck('id')
            ->filter()
            ->values();
    
        $team->members()->whereNotIn('id', $incomingMemberIds)->delete();
    
        // upsert each member
        foreach ($validated['members'] as $member) {
            $team->members()->updateOrCreate(
                ['id' => $member['id'] ?? 0],
                [
                    'name'        => $member['name'],
                    'user_id'     => $member['user_id'],
                    'role'        => $member['role'],
                    'position'    => $member['position'],
                    'pk_oriented' => $member['pk_oriented'],
                ]
            );
        }
    
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
