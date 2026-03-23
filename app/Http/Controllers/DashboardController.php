<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function accessLevelOneDashboard()
    {
        return inertia('dashboard/accessLevelOneDashboard');
    }

    public function accessLevelTwoDashboard(Request $request)
    {
        $user = $request->user();

        $teams = $user->teams()->with(['barangays.municipality'])->get();

        $barangays = $teams->flatMap->barangays->unique('id')->values();
        $reports = Report::with(['users','barangay.municipality'])->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $totalReports = $reports->count();
        $totalThisMonth = $reports->where('created_at', '>=', now()->startOfMonth())->count();
        $totalPending = $reports->where('status', 'pending')->count() ?? 0;
        $totalApproved = $reports->where('status', 'approved')->count() ?? 0;

        return inertia('dashboard/accessLevelTwoDashboard', [
            'teams'     => $teams,
            'barangays' => $barangays,
            'reports'   => $reports,
            'stats' => [
                'totalReports' => $totalReports,
                'totalThisMonth' => $totalThisMonth,
                'totalPending' => $totalPending,
                'totalApproved' => $totalApproved,
            ],
        ]);
    }

    public function accessLevelThreeDashboard()
    {
        return inertia('dashboard/accessLevelThreeDashboard');
    }
}
