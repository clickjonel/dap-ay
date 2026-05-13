<?php

namespace App\Http\Middleware;

use App\Models\Announcement;
use App\Models\Glossary;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user()?->load(['accessLevels']),
            ],
            'glossaries' => Glossary::orderBy('term')->get(),
            'announcements' => function () use ($request) {
                $user = $request->user()?->load(['accessLevels']);
                $query = Announcement::orderBy('created_at');

                if ($user?->accessLevels?->access_level !== 1) {
                    $query->where('target_access_level', $user?->accessLevels?->access_level);
                }

                return $query->get();
            },
        ];
    }
}
