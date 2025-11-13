<?php

namespace App\Http\Controllers;

use App\Models\ServerLog;
use Illuminate\Http\Request;

class ServerLogsController extends Controller
{
    public function readAllServerLogs()
    {
        // 1. This is your straight forward query code
        // $records = ServerLog::query()->simplePaginate(10);
        // 2. This is your laravel built in laravel query builder code
        // $records = ServerLog::query()
        //     ->select(
        //         'server_logs.id',
        //         'server_logs.user_id',
        //         'server_logs.action_done',
        //         'server_logs.table_name',
        //         'server_logs.column_id',
        //         'server_logs.created_at',
        //         User::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name")
        //     )
        //     ->leftJoin('users', 'users.id', '=', 'server_logs.user_id')
        //     ->simplePaginate(10);
        //3. This is your eloquent laraway way of code
        $records = ServerLog::query()->with('user')->simplePaginate(1);
        return response()->json($records, 200);
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
