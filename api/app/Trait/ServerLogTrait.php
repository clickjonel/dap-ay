<?php

namespace App\Trait;

use App\Models\ServerLog;
use Illuminate\Support\Facades\Auth;

trait ServerLogTrait
{
    protected static function bootServerLogTrait()
    {
        static::created(function ($model) {
            self::createLog($model, "Created a new {$model->getTable()} record");
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();// Only log if SOMETHING actually changed
            if (!empty($changes)) {
                self::createLog($model, "Updated {$model->getTable()} record");
            }
        });

        static::deleted(function ($model) {
            self::createLog($model, "Deleted {$model->getTable()} record");
        });
    }

    private static function createLog($model, string $action)
    {
        $userId = Auth::id() ?? $model->created_by_id ?? null;

        ServerLog::create([
            'created_by_id' => $userId,
            'action_done' => $action,
            'table_name' => $model->getTable(),
            'model_id' => $model->id,
        ]);
    }
}
