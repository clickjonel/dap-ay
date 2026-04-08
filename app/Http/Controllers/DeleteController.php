<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Team;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function deleteTeam($id)
    {
        $team = Team::findOrFail($id); //find team
        $team->barangays()->detach(); //delete related barangays
        $team->members()->delete(); //delete related members
        $team->delete(); //delete team

        return back(); //redirect back to the previous page

    }

    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        $report->users()->detach(); 

        $reportValues = $report->values;

        $reportValues->disaggregations()->delete();
        $reportValues->delete();
    }


}

