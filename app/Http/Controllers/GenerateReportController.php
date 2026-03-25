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
        $quarterStart = $now->copy()->firstOfQuarter();
        $quarterEnd   = $now->copy()->lastOfQuarter();
        $programs     = Program::get();

        $barangays = PurokalusuganActivityBarangay::query()
            ->with(['barangay.province','barangay.municipality', 'pkActivity.programs'])
            ->whereHas('pkActivity', function ($query) use ($quarterStart, $quarterEnd) {
                $query->whereBetween('date_start', [$quarterStart, $quarterEnd])
                    ->where('type', 'large');
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

}
