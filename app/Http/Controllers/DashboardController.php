<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\Province;
use App\Models\PurokalusuganActivity;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function accessLevelOneDashboard(Request $request)
    {
        $teamQuery = Team::query();
        $teamData = [
            'total'       => (clone $teamQuery)->count(),
            'activeTeams' => (clone $teamQuery)->where('is_active', true)->count(),
            'withKits'    => (clone $teamQuery)->where('pk_kit', true)->count(),
            'withoutKits' => (clone $teamQuery)->where('pk_kit', false)->count(),
            'withEO' => (clone $teamQuery)->where('eo_link', '!=', null)->count(),
            'provinceBreakdown' => Province::get()->map(fn($province) => [
                'province'   => $province->name,
                'totalTeams' => Team::whereHas('barangays', fn($q) => $q->where('province_id', $province->id))->count(),
            ]),
        ];

        $programs = Program::with(['baseline','indicators'])->get();
        $programData = [
            'total' => $programs->count(),
            'active' => $programs->where('is_active', true)->count(),
            'inactive' => $programs->where('is_active', false)->count(),
            'withBaseline' => $programs->whereNotNull('baseline')->count(),
            'programs' => $programs
        ];

        $programIndicators = ProgramIndicator::with(['reportValues.disaggregations.disaggregation'])->get()->map(function($indicator) {
            // 1. Flatten all disaggregations from all report values into one list
            $allDisaggs = $indicator->reportValues->flatMap->disaggregations;

            // 2. Group by the ID and sum the values
            $groupedDisaggs = $allDisaggs->groupBy('disaggregation_id')
                ->map(function ($group) {
                    return [
                        'disaggregation_id' => $group->first()->disaggregation_id,
                        'name' => $group->first()->disaggregation->disaggregation_name,
                        'total_value' => $group->sum('value')
                    ];
                })->values();

            return [
                'indicator_name'  => $indicator->indicator_name,
                'total'           => $indicator->reportValues->sum('total'),
                'disaggregations' => $groupedDisaggs
            ];
        });

        $pkActivities = [
            'total' => PurokalusuganActivity::count(),
            'small' => PurokalusuganActivity::where('type', 'small')->count(),
            'large' => PurokalusuganActivity::where('type', 'large')->count(),
            
            'activities' => Province::get()->map(function($province) {
                // Query the activities for this specific province
                $query = PurokalusuganActivity::whereHas('barangays', function($q) use ($province) {
                    $q->where('province_id', $province->id);
                });

                // Clone the query to get specific type counts without re-running the heavy 'whereHas'
                return [
                    'province' => $province->name,
                    'total'    => (clone $query)->count(),
                    'small'    => (clone $query)->where('type', 'small')->count(),
                    'large'    => (clone $query)->where('type', 'large')->count(),
                ];
            })
        ];

        return inertia('dashboard/accessLevelOneDashboard', [
            'team' => $teamData,
            'program' => $programData,
            'programIndicators' => $programIndicators,
            'pkActivities' => $pkActivities,
        ]);
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
        $userHandledMunicipalities = $user->handledMunicipalities()->pluck('municipality_id')->toArray();
        $reports = Report::query()
                    ->with([
                        'users',
                        'barangay.municipality',
                        'values.disaggregations.disaggregation',
                        'values.indicator',
                    ])
                    ->whereHas('barangay', function($query) use ($userHandledMunicipalities) {
                        $query->whereIn('municipality_id', $userHandledMunicipalities);
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
