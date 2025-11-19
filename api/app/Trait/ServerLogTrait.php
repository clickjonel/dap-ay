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
            $table = $model->getTable();
            ServerLog::create([
                'created_by_id' => $userId,
                'action_done' => "Created a new {$table} record",
                'table_name' => $table,
                'model_id' => $model->id,
            ]);
        });
    }
}
