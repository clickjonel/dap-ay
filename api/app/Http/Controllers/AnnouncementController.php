<?php

namespace App\Http\Controllers;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function readAllAnnouncements(Request $request)
    {
        $records = Announcement::query()
            ->when(isset($request->keyword), function ($query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->keyword}%");
            })
            ->with('user')
            ->orderBy('id', 'DESC')
            ->simplePaginate(10);
        return response()->json($records, 200);
    }
    public function createAnnouncement(Request $request)
    {
        $validatedData = $request->validate([
            'created_by_id' => 'integer',
            'date_start' => 'nullable|max:255',
            'date_end' => 'nullable|max:255',
            'title' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:255'
        ]);
        $record = Announcement::create($validatedData);
        return response()->json([
            'message' => 'Record created successfully',
            'data' => $record
        ], 201);
    }

    public function deleteAnnouncement(Request $request)
    {
        try {
            $record = Announcement::findOrFail($request->id);
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
