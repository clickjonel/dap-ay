<?php

namespace App\Http\Controllers;
use App\Models\AnnouncementViewer;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AnnouncementViewerController extends Controller
{
    public function readAllAnnouncementForPostingBasedOnTeams(Request $request){
        $teamIds = $request->team_viewers;
        $today = Carbon::today();        
        $records = AnnouncementViewer::whereIn('team_id', $teamIds)            
            ->with(['announcement' => function ($query) use ($today) {               
                $query->whereDate('date_start', '<=', $today)
                      ->whereDate('date_end', '>=', $today);
            }])
            ->get()
            ->pluck('announcement')            
            ->filter()
            ->unique('id')
            ->values();

        return response()->json([
            'message' => "Active announcements retrieved successfully for teams " . implode(', ', $teamIds),
            'data' => $records
        ], 200);
    }
    public function readAnnouncementViewerByAnnouncementId(Request $request)
    {
        $validated = $request->validate([
            'announcement_id' => 'required|integer|min:1'
        ]);

        $announcementId = $validated['announcement_id'];

        $records = AnnouncementViewer::query()
            ->where('announcement_id', $announcementId)
            ->with('team')
            ->orderBy('id')
            ->get();

        return response()->json($records, 200);
    }    
    public function createAnnouncementViewer(Request $request)
    {
        $validatedData = $request->validate([
            'announcement_id' => 'integer',
            'team_id' => 'integer'
        ]);
        $record = AnnouncementViewer::create($validatedData);

        return response()->json([
            'message' => 'Record created successfully',
            'data' => $record
        ], 201);
    }

    public function deleteAnnouncementViewerByAnnouncementId(Request $request)
    {
        try {
            AnnouncementViewer::where('announcement_id', $request->announcement_id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Records deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting records',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
