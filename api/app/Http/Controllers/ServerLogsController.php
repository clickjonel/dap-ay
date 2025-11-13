<?php

namespace App\Http\Controllers;

use App\Models\ServerLog;
use Illuminate\Http\Request;

class ServerLogsController extends Controller
{
    public function readAllServerLogs()
    {
        $records = ServerLog::query()->with('user')->simplePaginate(10);
        return response()->json($records, 200);
    }

    public function createServerLog(Request $request)
    {
        $validatedData=$request->validate([
            'created_by_id' => 'integer',
            'action_done' => 'string|max:255',
            'table_name' => 'string|max:255',
            'column_id'=>'nullable|string|max:255'
        ]);
        $record = ServerLog::create($validatedData);
        return response()->json([
            'message' => 'Record created successfully',
            'data' => $record
        ], 201);
    }
}
