<?php

namespace App\Http\Controllers;
use App\Models\AnnouncementViewer;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AnnouncementViewerController extends Controller
{
    public function readAllAnnouncementForPosting(){
        $teamIds = [1, 2]; // The team IDs you are filtering for
        $today = Carbon::today();

        // 1. Start with the model that has the WHERE condition (AnnouncementViewer)
        $records = AnnouncementViewer::whereIn('team_id', $teamIds)
            // 2. Eager-load the related 'announcement' records (this performs the JOIN)
            ->with(['announcement' => function ($query) use ($today) {
                // 3. Constrain the joined 'announcement' table by date
                $query->whereDate('date_start', '<=', $today)
                      ->whereDate('date_end', '>=', $today);
            }])
            // 4. Filter out any AnnouncementViewer records where the joined announcement didn't match the date constraint
            ->get()
            // 5. Transform the collection to only show the relevant data (optional, but cleaner)
            ->pluck('announcement')
            // 6. Remove any null entries (from the filtered join) and duplicates
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
