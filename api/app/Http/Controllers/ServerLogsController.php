<?php

namespace App\Http\Controllers;

use App\Models\ServerLog;
use Illuminate\Http\Request;

class ServerLogsController extends Controller
{
    public function readAllServerLogs()
    {
        $records = ServerLog::all();
        return response()->json([
            'message' => 'Records retrieved successfully',
            'data' => $records
        ], 200);
    }

    public function createServerLog(Request $request)
    {
        $validatedData=$request->validate([
            'user_id' => 'integer',
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
