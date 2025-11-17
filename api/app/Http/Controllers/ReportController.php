<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportValue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'created_by' => 'required|numeric|exists:users,id',
            'start' => 'required|date',
            'end' => 'required|date',
            'barangay_id' => 'required|numeric|exists:barangays,id',
            'values' => 'required|array'
        ]);

        $report = Report::create($validated);

        foreach($validated['values'] as $value){
            ReportValue::create([
                'report_id' => $report->id,
                'indicator_id' => $value['id'],
                'value' => $value['value'],
            ]);
        }

        return response()->json('Created Report Successfully',201);

    }

    public function list(Request $request)
    {
        // $keyword = $request->keyword ?? '';

        // $query = Report::query();
        // $list = $query->when(isset($keyword), function($query) use ($keyword) {
        //             $query->where('name', 'LIKE', "%{$keyword}%");
        //         })
        //         ->with(['subProgram'])
        //         ->simplePaginate(15);

        $list = Report::with(['barangay','createdBy'])->simplePaginate();

        return response()->json($list);
    }

    public function getMonthlyMunicipalLevelReport(Request $request)
    {
        $validated = $request->validate([
        'municipality_id' => 'required|numeric|exists:municipalities,id'
    ]);

    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $municipalityId = $validated['municipality_id'];

    $reports = Report::whereHas('barangay', function($query) use ($municipalityId) {
                $query->where('municipality_id', $municipalityId);
                })
                ->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->where('start', '<=', $endOfMonth)
                        ->where('end', '>=', $startOfMonth);
                })
                ->with(['barangay.municipality', 'createdBy','values.indicator'])
                ->withSum('values as valueSum', 'value')
                ->get();

        return response()->json([
            'month' => Carbon::now()->format('F'),
            'reports' => $reports
        ]);
    }
}
