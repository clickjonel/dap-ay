<?php

namespace App\Http\Controllers;
use App\Models\AnnouncementViewer;
use Illuminate\Http\Request;

class AnnouncementViewerController extends Controller
{
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

    public function deleteAnnouncementViewer(Request $request)
    {
        try {
            $record = AnnouncementViewers::findOrFail($request->id);
            $record->delete();
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
