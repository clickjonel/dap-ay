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

        // Delete disaggregations for each value first (child records)
        foreach ($report->values as $value) {
            $value->disaggregations()->delete();
        }

        // Then delete the values
        $report->values()->delete();

        // Detach pivot records
        $report->users()->detach();

        // Finally delete the report
        $report->delete();
    }


}

