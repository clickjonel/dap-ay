<?php

namespace App\Http\Controllers;
use App\Models\AnnouncementViewer;
use Illuminate\Http\Request;

class AnnouncementViewerController extends Controller
{
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
