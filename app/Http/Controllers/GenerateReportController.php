<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\PurokalusuganActivity;
use App\Models\PurokalusuganActivityBarangay;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GenerateReportController extends Controller
{
    public function generateQuarterlyLargeScaleReport()
    {
        $now          = now();
        $monthStart = $now->copy()->firstOfQuarter();
        $monthEnd   = $now->copy()->lastOfQuarter();
        $programs     = Program::get();

        $barangays = PurokalusuganActivityBarangay::query()
            ->with(['barangay.province','barangay.municipality', 'pkActivity.programs'])
            ->whereHas('pkActivity', function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('date_start', [$monthStart, $monthEnd]);
            })
            ->get()
            ->map(function ($barangay) use ($programs) {
            // Clone to avoid mutating the shared pkActivity instance
            $activityPrograms = $barangay->pkActivity->getRelation('programs');

            $mappedPrograms = $programs->map(function ($program) use ($activityPrograms) {
                return [
                    'id'    => $program->id,
                    'name'  => $program->name,
                    'total' => $activityPrograms->where('id', $program->id)->isNotEmpty() ? 1 : 0,
                ];
            });

            // Set on a cloned activity to avoid mutating the shared instance
            $pkActivity = clone $barangay->pkActivity;
            $pkActivity->setRelation('programs', $mappedPrograms);
            $barangay->setRelation('pkActivity', $pkActivity);

            return $barangay;
        });


        return Inertia::render('generate/quarterlyLargeScaleActivitiesReport', [
            'barangays'   => $barangays,
        ]);

    }

    public function generateApprovedReportSubmissions(Request $request)
    {
        $validated = $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $start = $validated['start'] ?? now()->startOfMonth()->toDateString();
        $end = $validated['end'] ?? now()->endOfMonth()->toDateString();

        $reports = Report::query()
            ->where('status', 'approved')
            ->with([
                'barangay.municipality',
                'barangay.province',
                'users',
                'values.indicator.program',
                'values.disaggregations.disaggregation'
            ])
            ->where('status', 'approved')
            ->whereBetween('date', [$start, $end])
            ->get();

        return Inertia::render('generate/generateApprovedReportSubmissions', [
            'reports' => $reports,
        ]);
    }

    
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $start = $validated['start'] ?? now()->startOfMonth()->toDateString();
        $end = $validated['end'] ?? now()->endOfMonth()->toDateString();

        $reports = Report::query()
            ->where('status', 'Approved')
            ->with([
                'barangay.municipality',
                'barangay.province',
                'users',
                'values.indicator.program',
                'values.disaggregations.disaggregation'
            ])
            ->whereBetween('date', [$start, $end])
            ->get();

        $totals = [
            'total_submissions' => $reports->count(),
            'total_clients' => $reports->sum('total_clients'),
            'total_returning_clients' => $reports->sum('total_returning_clients'),
            'total_barangays' => $reports->pluck('barangay_id')->unique()->count(),
            'total_users' => $reports->pluck('users')->flatten()->pluck('id')->unique()->count(),
        ];

        return Inertia::render('generate/reportGenerator', [
            'reports' => $reports,
            'totals' => $totals
        ]);
    }

}
