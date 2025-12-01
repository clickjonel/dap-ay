<?php

namespace App\Observers;

use App\Models\ServerLog;
use App\Models\Team;
use Illuminate\Container\Attributes\Auth;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        ServerLog::create([
            'created_by_id' => auth()->id ?? 1,
            'action_done' => 'Created Team',
            'table_name' => 'teams',
            'model_id' => $team->id
        ]);
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}
