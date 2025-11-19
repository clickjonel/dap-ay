<?php

namespace App\Trait;

use App\Models\ServerLog;
use Illuminate\Support\Facades\Auth;

trait ServerLogTrait
{
    protected static function bootServerLogTrait()
    {
        static::created(function ($model) {

            $userId = Auth::id() ?? $model->created_by_id ?? null;

            ServerLog::create([
                'created_by_id' => $userId,
                'action_done' => "Created an announcement",
                'table_name' => "announcements",
                'column_id' => $model->id,
            ]);
        });
    }
}
