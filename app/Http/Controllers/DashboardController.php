<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\OrganizationalIndicator;
use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\Province;
use App\Models\PurokalusuganActivity;
use App\Models\PurokalusuganActivityBarangay;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
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

    public function accessLevelThreeDashboard(Request $request)
    {
        $user = $request->user()->load(['accessLevels']);

        $province = Province::with([
            'barangays.pkProfile',
            'barangays.organizationalIndicators',
            'barangays.geography',
            'barangays.population',
            'barangays.priorityPrograms',
            'barangays.municipality'
        ])
        ->withCount([
            'barangays',
            'municipalities'
        ])
        ->find($user->accessLevels->pdoho_access_id);

        $teams = Team::with([
                        'barangays',
                        'members'
                    ])
                    ->whereHas('barangays',function($query) use ($province) {
                        $query->where('province_id',$province->id);
                    })
                    ->withCount([
                        'barangays',
                        'members',
                        'members as doh_deployed' => function($query){
                            $query->whereNotNull('user_id');
                        },
                    ])
                    ->distinct()
                    ->get()
                    ->map(function($team){
                        $teamData = [
                            'name' => $team->name,
                            'barangay_count' => $team->barangays_count,
                            'member_count' => $team->members_count,
                            'doh_deployed' => $team->doh_deployed,
                            'pk_kit' => $team->pk_kit ? 'Given' : 'For Issuance',
                            'eo_link' => $team->eo_link,
                            'active' => $team->is_active
                        ];

                        return $teamData;
                    });

        $provinceDashboardData = [
            'name' => $province->name,

            'barangays' => $province->barangays->map(function($brgy){
                $brgyData = [
                    'name' => $brgy->name,
                    'org_indicator' => $brgy->organizationalIndicators->isNotEmpty(),
                    'pk_profile' => $brgy->pkProfile !== null,
                    'geography' => $brgy->geography !== null,
                    'population' => $brgy->population !== null,
                    'priority_programs' => $brgy->priorityPrograms->isNotEmpty(),
                    'municipality' => $brgy->municipality->name
                ];

                return $brgyData;
            })

        ];


        $totals = [
            'municipalities' => $province->municipalities_count,
            'barangays' => $province->barangays_count,
            'hrh' => User::whereHas('accessLevels', function($query) use ($province){
                                $query->where('pdoho_access_id',$province->id);
                            })
                            ->distinct()
                            ->count(),
            'teams' => Team::whereHas('barangays', function($query) use ($province){
                                $query->where('province_id',$province->id);
                            })
                            ->distinct()
                            ->count(),
        ];

        return inertia('dashboard/accessLevelThreeDashboard', [
            'province' => $provinceDashboardData,
            'totals' => $totals,
            'teams' => $teams
        ]);
    }

    public function accessLevelFourDashboard(Request $request)
    {
        $user = $request->user();
        $userProvinceID = $user->accessLevels->pdoho_access_id;
        $userHandledMunicipalities = $user->handledMunicipalities()->pluck('municipality_id')->toArray();
    
        $baseQuery = Report::whereHas('barangay', function($query) use ($userHandledMunicipalities) {
            $query->whereIn('municipality_id', $userHandledMunicipalities);
        });
    
        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'pending'  => (clone $baseQuery)->whereNull('status')->count(),
            'approved' => (clone $baseQuery)->where('status', 'Approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'Rejected')->count(),
        ];
    
        $reports = (clone $baseQuery)
            ->with([
                'users',
                'barangay.municipality',
                'values.disaggregations.disaggregation',
                'values.indicator',
            ])
            ->whereNull('status')
            ->latest('date')
            ->paginate();
    
        $geoCoverage = [
            'municipalities' => Municipality::where('province_id', $userProvinceID)->count(),
            'barangays'      => Barangay::where('province_id', $userProvinceID)->count(),
            'gida'           => Barangay::where('province_id', $userProvinceID)
                                    ->whereHas('geography', fn($q) => $q->where('is_gida', true))
                                    ->count(),
        ];
    
        return inertia('dashboard/accessLevelFourDashboard', [
            'reports'     => $reports,
            'stats'       => $stats,
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

    public function barangayPriorityProgramsMonitoring()
    {
        $provinces = Province::with([
            'municipalities.barangays.priorityPrograms',
        ])->get();

        $programs = Program::get();


        return Inertia::render('dashboard/barangayPriorityProgramsMonitoring',[
            'provinces' => $provinces,
            'programs' => $programs
        ]);
    }

    public function barangayOrganizationalIndicatorsMonitoring()
    {
        $provinces = Province::with([
            'municipalities.barangays.organizationalIndicators',
        ])->get();

        $indicators = OrganizationalIndicator::get();


        return Inertia::render('dashboard/barangayOrganizationalIndicatorsMonitoring',[
            'provinces' => $provinces,
            'indicators' => $indicators
        ]);
    }

    public function barangayPKProfileMonitoring()
    {
        $provinces = Province::with([
            'municipalities.barangays.pkProfile',
        ])->get();

        return Inertia::render('dashboard/barangayPKProfileMonitoring',[
            'provinces' => $provinces,
        ]);
    }

    public function barangayGeographyMonitoring()
    {
        $provinces = Province::with([
            'municipalities.barangays.geography',
        ])->get();

        return Inertia::render('dashboard/barangayGeographyMonitoring',[
            'provinces' => $provinces,
        ]);
    }

    public function barangayPopulationMonitoring()
    {
        $provinces = Province::with([
            'municipalities.barangays.population',
        ])->get();

        return Inertia::render('dashboard/barangayPopulationMonitoring',[
            'provinces' => $provinces,
        ]);
    }

    public function dmoReportApprovalsMonitoring()
    {
        $users = User::query()
            // ->with(['actionedReports'])
            ->whereHas('accessLevels', function ($query) {
                $query->where('access_level', 4);
            })
            ->withCount([
                'actionedReports as rejected' => function ($query) {
                    $query->where('status', 'Rejected'); // was a comma here
                },
                'actionedReports as approved' => function ($query) {
                    $query->where('status', 'Approved');
                },
            ])
            ->get();

        return Inertia::render('dashboard/dmoReportsApprovalMonitoring', [
            'users' => $users,
        ]);
    }


    public function barangayPKActivitiesMonitoring()
    {
        $provinces = Province::with([
                        'municipalities.barangays.pkActivities.programs'
                    ])
                    ->get();

        $programs = Program::get();

        return Inertia::render('dashboard/barangayPKActivitiesMonitoring', [
            'provinces' => $provinces,
            'programs' => $programs
        ]);
    }




}
