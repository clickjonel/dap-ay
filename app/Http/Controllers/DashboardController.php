<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
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

    public function accessLevelFourDashboard(Request $request)
    {
        $user = $request->user();
        $userProvinceID = $user->accessLevels->pdoho_access_id;
        $reports = Report::query()
                    ->with([
                        'users',
                        'barangay.municipality',
                        'values.disaggregations.disaggregation',
                        'values.indicator',
                    ])
                    ->whereHas('barangay', function($query) use ($userProvinceID) {
                        $query->where('province_id', $userProvinceID);
                    })
                    ->orderBy('date', 'asc')
                    ->get();
            
        $geoCoverage = [
            'municipalities' => Municipality::where('province_id',$userProvinceID)->count(),
            'barangays' => Barangay::where('province_id',$userProvinceID)->count(),
            'gida' => Barangay::where('province_id',$userProvinceID)
                        ->whereHas('geography',function($query) {
                            $query->where('is_gida', true);
                        })->count(),
        ];

        return inertia('dashboard/accessLevelFourDashboard', [
            'reports' => $reports,
            'geoCoverage' => $geoCoverage,
        ]);
    }
}
