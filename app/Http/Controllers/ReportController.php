<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\ProgramIndicator;
use App\Models\Report;
use App\Models\ReportUser;
use App\Models\ReportValue;
use App\Models\ReportValueDisaggregation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load([
            'accessLevels',
            'handledMunicipalities'
        ]);
        $userHandledMunicipalities = $user->handledMunicipalities->pluck('municipality_id');

        $reports = Report::with([
            'barangay.municipality',
            'barangay.province',
            'users',
            'actionedBy'
        ])
        ->when($user->accessLevels->access_level === 2, function ($query) use ($user) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
        ->when($user->accessLevels->access_level === 3, function ($query) use ($user) {
            $query->whereHas('barangay', function ($q) use ($user) {
                $q->where('province_id', $user->accessLevels->pdoho_access_id);
            });
        })
        ->when($user->accessLevels->access_level === 4, function ($query) use ($userHandledMunicipalities) {
            $query->whereHas('barangay', function ($q) use ($userHandledMunicipalities) {
                $q->whereIn('municipality_id', $userHandledMunicipalities);
            });
        })
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barangay', fn($b) => $b->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('users',   fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        })
        ->paginate(15)
        ->withQueryString(); // preserves ?search= in pagination links

        return inertia('report/reports', [
            'reports' => $reports,
            'filters' => ['search' => $request->search],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $barangays = Barangay::query()
                        ->with(['municipality'])
                        ->when($user->accessLevels->access_level === 2, function($query) use ($user) {
                            $query->where('province_id', $user->accessLevels->pdoho_access_id);
                        })
                        ->get()
                        ->map(fn($b) => [
                            'id'   => $b->id,
                            'name' => $b->name . ' - ' . ($b->municipality->name ?? '—'),
                        ]);
        $users = User::query()
            ->when($user->accessLevels->access_level === 2, function ($query) use ($user) {
                $query->whereHas('accessLevels', function ($q) use ($user) {
                    $q->where('pdoho_access_id', $user->accessLevels->pdoho_access_id);
                });
            })
            ->get();

       $indicators = ProgramIndicator::with(['program', 'disaggregations'])
            ->where('is_active', true)
            ->orderBy('program_id','asc')
            ->get()
            ->map(function ($indicator) {
                return [
                    'id'             => $indicator->id,
                    'indicator_name' => $indicator->indicator_name,
                    'program'        => [
                        'name' => $indicator->program->program_name,
                    ],
                    'disaggregations' => $indicator->disaggregations->map(fn($d) => [
                        'id'                 => $d->id,
                        'disaggregation_name' => $d->disaggregation_name,
                        'group'              => $d->group,
                    ]),
                ];
            });

        return inertia('report/createReport', [
            'barangays'  => $barangays,
            'users'      => $users,
            'indicators' => $indicators,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'                                            => 'required|exists:barangays,id',
            'date'                                                   => 'required|date',
            'total_clients'                                          => 'required|integer|min:0',
            'total_returning_clients'                                => 'required|integer|min:0',
            'user_ids'                                               => 'required|array|min:1',
            'user_ids.*'                                             => 'exists:users,id',
            'indicator_values'                                       => 'required|array',
            'indicator_values.*.indicator_id'                        => 'required|exists:program_indicators,id',
            'indicator_values.*.total'                               => 'nullable|integer|min:0',
            'indicator_values.*.disaggregations'                     => 'nullable|array',
            'indicator_values.*.disaggregations.*.disaggregation_id' => 'required|exists:disaggregations,id',
            'indicator_values.*.disaggregations.*.value'             => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            //create report
            $report = Report::create([
                'barangay_id' => $validated['barangay_id'],
                'date' => $validated['date'],
                'total_clients' => $validated['total_clients'],
                'total_returning_clients' => $validated['total_returning_clients'],
            ]);

            //create report users
            foreach ($validated['user_ids'] as $user_id) {
                ReportUser::create([
                    'report_id' => $report->id,
                    'user_id' => $user_id,
                ]);
            }

            //create report values
            foreach($validated['indicator_values'] as $indicator_value) {
                $indicator = ProgramIndicator::find($indicator_value['indicator_id']);

                $reportValue = ReportValue::create([
                    'report_id' => $report->id,
                    'indicator_id' => $indicator_value['indicator_id'],
                    'program_id' => $indicator->program_id,
                    'total' => $indicator_value['total'] ?? 0,
                ]);

                //create report value disaggregations
                foreach($indicator_value['disaggregations'] ?? [] as $disaggregation) {
                    ReportValueDisaggregation::create([
                        'report_value_id' => $reportValue->id,
                        'disaggregation_id' => $disaggregation['disaggregation_id'],
                        'value' => $disaggregation['value'] ?? 0,
                    ]);
                }

            }
        });

        return redirect('/reports');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = Report::with([
            'barangay.municipality',
            'barangay.province',
            'users',
            'values.indicator',
            'values.disaggregations.disaggregation'
        ])->findOrFail($id);

        $user = Auth::user();

        $barangays = Barangay::query()
                        ->with(['municipality'])
                        ->when($user->accessLevels->access_level === 2, function($query) use ($user) {
                            $query->where('province_id', $user->accessLevels->pdoho_access_id);
                        })
                        ->get()
                        ->map(fn($b) => [
                            'id'   => $b->id,
                            'name' => $b->name . ' - ' . ($b->municipality->name ?? '—'),
                        ]);

        $users = User::query()
            ->when($user->accessLevels->access_level === 2, function ($query) use ($user) {
                $query->whereHas('accessLevels', function ($q) use ($user) {
                    $q->where('pdoho_access_id', $user->accessLevels->pdoho_access_id);
                });
            })
            ->get();

        return Inertia::render('report/updateReport', [
            'report' => $report,
            'barangays'  => $barangays,
            'users'      => $users,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'barangay_id'                                            => 'required|exists:barangays,id',
            'date'                                                   => 'required|date',
            'total_clients'                                          => 'required|integer|min:0',
            'total_returning_clients'                                => 'required|integer|min:0',
            'user_ids'                                               => 'required|array|min:1',
            'user_ids.*'                                             => 'exists:users,id',
            'indicator_values'                                       => 'required|array',
            'indicator_values.*.id'                                  => 'required|exists:report_values,id',
            'indicator_values.*.indicator_id'                        => 'required|exists:program_indicators,id',
            'indicator_values.*.total'                               => 'nullable|integer|min:0',
            'indicator_values.*.disaggregations'                     => 'nullable|array',
            'indicator_values.*.disaggregations.*.id'                => 'required|exists:report_value_disaggregations,id',
            'indicator_values.*.disaggregations.*.disaggregation_id' => 'required|exists:disaggregations,id',
            'indicator_values.*.disaggregations.*.value'             => 'nullable|integer|min:0',
        ]);

        $report = Report::findOrFail($id);

         DB::transaction(function () use ($report, $validated) {

                // Update report
                $report->update([
                    'barangay_id'             => $validated['barangay_id'],
                    'date'                    => $validated['date'],
                    'total_clients'           => $validated['total_clients'],
                    'total_returning_clients' => $validated['total_returning_clients'],
                ]);

                // Sync users
                ReportUser::where('report_id', $report->id)->delete();//delete existing users to set new ones
                foreach ($validated['user_ids'] as $user_id) {
                    ReportUser::create([
                        'report_id' => $report->id,
                        'user_id'   => $user_id,
                    ]);
                }

                // Sync indicator values
                foreach ($validated['indicator_values'] as $indicator_value) {
                    ReportValue::find($indicator_value['id'])->update([
                        'total' => $indicator_value['total'] ?? 0,
                    ]);

                    foreach($indicator_value['disaggregations'] as $disaggregation){
                        ReportValueDisaggregation::find($disaggregation['id'])->update([
                            'value' => $disaggregation['value'] ?? 0,
                        ]);
                    }
                }
            });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        Report::findOrFail($id)->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
            'action_by' => $request->user()->id,
        ]);

        return back();
    }

    public function bulkAction(Request $request)
    {
        Report::whereIn('id', $request->report_ids)->update([
            'status'    => $request->status,
            'remarks'   => $request->remarks,
            'action_by' => $request->user()->id,
        ]);

        return back();
    }
}
