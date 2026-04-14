<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\Province;
use App\Models\PurokalusuganActivity;
use App\Models\PurokalusuganActivityBarangay;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function accessLevelOneDashboard(Request $request)
    {
        $provinces = Province::with([
            'municipalities',
            'barangays.pkProfile',
            'barangays.geography',
            'barangays.organizationalIndicators',
        ])
        ->withCount('municipalities')
        ->withCount('barangays')
        ->withCount([
            'barangays as pk_sites_count' => fn($q) =>
                $q->whereHas('pkProfile', fn($q) =>
                    $q->where('pk_site', true)
                ),

            // get barangays with or without barangay org indicators
            'barangays as with_org_indicators' => fn($q) =>
                $q->whereHas('organizationalIndicators'),
            
            'barangays as without_org_indicators' => fn($q) =>
                $q->whereDoesntHave('organizationalIndicators'),

            //get barangays with or without barangay priority programs
            'barangays as with_priority_programs' => fn($q) =>
                $q->whereHas('priorityPrograms'),
            
            'barangays as without_priority_programs' => fn($q) =>
                $q->whereDoesntHave('priorityPrograms'),
        ])

        ->get()
        ->map(function($province){
            $pk_coverage = [
                'barangays' => $province->barangays,
                'municipalities' => $province->municipalities,
                'pk_sites_count' => $province->pk_sites_count,
                'barangays_count' => $province->barangays_count,
                'municipalities_count' => $province->municipalities_count
            ];

            $org_indicators_status = [
                'with_org_indicators' => $province->with_org_indicators,
                'without_org_indicators' => $province->without_org_indicators
            ];

            $priority_programs_status = [
                'with_priority_programs' => $province->with_priority_programs,
                'without_priority_programs' => $province->without_priority_programs
            ];

            return [
                'name' => $province->name,
                'pk_coverage' => $pk_coverage,
                'org_indicator_status' => $org_indicators_status,
                'priority_programs_status' => $priority_programs_status
            ];
        });

        return inertia('dashboard/accessLevelOneDashboard', [
          
            'provinces' => $provinces,
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

    public function getDMOMunicipalityDashboard(Request $request)
    {
        $user = $request->user()->load([
            'accessLevels',
            'handledMunicipalities.municipality.barangays' => function ($query) {
                $query->withCount([
                    'reports',
                    'pkActivities',
                ]);
            },
            'handledMunicipalities.municipality.barangays.pkProfile',
            'handledMunicipalities.municipality.barangays.priorityPrograms',
            'handledMunicipalities.municipality.barangays.geography',
        ]);

        return Inertia::render('dashboard/dmoHandledMunicipalityDashboard',[
            'municipalities' => $user->handledMunicipalities
        ]);
    }


}
