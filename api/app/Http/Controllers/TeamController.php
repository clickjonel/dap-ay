<?php

namespace App\Http\Controllers;

use App\Models\ServerLog;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function create(Request $request)
    {
        Team::create(['name' => $request->name]);

        return response()->json('Created Team Successfully',201);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Team::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->with(['members','barangays'])
                ->simplePaginate(20);

        return response()->json($list,200);
    }

    public function update(Request $request)
    {
        Team::find($request->id)->update(['name' => $request->name]);

        return response()->json('Updated Team Successfully',201);
    }

    public function find(Request $request)
    {
        $relationships = $request->relationships ?? [];

        $team = Team::with($relationships)->find($request->team_id);

        return response()->json($team,200);
    }



}
