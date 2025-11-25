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
        $provinceID = $request->user()->pdoho_province_id ?? null;
        $keyword = $request->keyword ?? '';

        $list = Team::query()
            ->when($provinceID, function($query) use ($provinceID) {
                $query->whereHas('barangays', function($q) use ($provinceID) {
                    $q->where('province_id', $provinceID);
                })
                // Also filter the loaded barangays to only show those from this province
                ->with(['barangays' => function($q) use ($provinceID) {
                    $q->where('province_id', $provinceID);
                }]);
            }, function($query) {
                // If no province filter, load all barangays normally
                $query->with('barangays.municipality.province');
            })
            ->when($keyword, function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->with('members')
            ->simplePaginate(20);

        return response()->json($list, 200);
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
