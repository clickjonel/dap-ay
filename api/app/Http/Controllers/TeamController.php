<?php

namespace App\Http\Controllers;

use App\Models\ServerLog;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Team::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                    $query->where('team_name', 'LIKE', "%{$keyword}%");
                })
                ->with(['members.user','barangay.municipality','barangay.province'])
                ->simplePaginate(10);

        return response()->json($list,200);
    }

    public function createTeam(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string',
            'barangay_id' => 'required|numeric',
            'members' => 'required|array'
         ]);

         $team = Team::create([
            'team_name' => $validated['team_name'],
            'barangay_id' => $validated['barangay_id'],
            'team_active' => true,
         ]);

         foreach($validated['members'] as $member){
            TeamMember::create([
                'team_id' => $team->team_id,
                'member_name' => $member['member_name'],
                'member_position' => $member['member_position'],
                'member_oriented' => $member['member_oriented'],
                'member_trained' => $member['member_trained'],
                'pk_kit_receipt' => $member['pk_kit_receipt'],
                'site_name' => $member['site_name'],
                'user_id' => $member['user_id'],
            ]);
         }

         return response()->json('Team Created Successfully',201);
    }

    public function updateTeam(Request $request)
    {
        Team::find($request->team_id)->update(['team_name' => $request->team_name]);

        return response()->json('Updated Team Successfully',201);
    }

    public function deactivateTeam()
    {

    }

    public function addTeamMember(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|numeric|exists:pk_teams,team_id',
            'member_name' => 'required|string',
            'member_role' => 'required|string' ,
            'user_id' => 'nullable|numeric|exists:pk_users,user_id',
        ]);

        TeamMember::create($validated);

        return response()->json('Added Member Successfully',201);
    }

    public function removeTeamMember(Request $request)
    {
        TeamMember::find($request->team_member_id)->delete();

        return response()->json('Removed Member Successfully',201);
    }

    public function findTeam(Request $request)
    {
        $relationships = $request->relationships ?? [];

        $team = Team::with($relationships)->find($request->team_id);

        return response()->json($team,200);
    }

    public function updateMemberDetails(Request $request)
    {
        TeamMember::find($request->team_member_id)->update([
            'member_position' => $request->member_position,
            'member_oriented' => $request->member_oriented,
            'member_trained' => $request->member_trained,
            'pk_kit_receipt' => $request->is_pk_receipt,
            'site_name' => $request->site_name,
        ]);

        return response()->json('Team Member Updated.',200);
    }

    public function updateTeamDetails(Request $request)
    {
        Team::find($request->team_id)->update([
            'team_name' => $request->team_name,
            'barangay_id' => $request->barangay_id,
        ]);

        return response()->json('Team Updated.',200);
    }

    public function deleteMember(Request $request)
    {
        TeamMember::find($request->team_member_id)->delete();

        return response()->json('Remove Member from the Team',201);
    }

}
