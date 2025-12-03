<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\ReportValueBreakdown;
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
        'values' => 'required|array',
        'values.*.id' => 'required|numeric|exists:indicators,id',
        'values.*.value' => 'required|numeric',
    ]);

    $report = Report::create([
        'created_by' => $validated['created_by'],
        'start' => $validated['start'],
        'end' => $validated['end'],
        'barangay_id' => $validated['barangay_id'],
    ]);

        foreach($validated['values'] as $value){
            $reportValue = ReportValue::create([
                'report_id' => $report->id,
                'indicator_id' => $value['id'],
                'value' => $value['value'],
            ]);
        }

        return response()->json('Created Report Successfully', 201);
    }

    public function list(Request $request)
    {
        $query = Report::with(['barangay.municipality.province', 'createdBy']);

        if($request->user()->user_level === 4){
            $query->where('created_by', $request->user()->id);
        }

        else{
            $provinceID = $request->user()->pdoho_province_id ?? null;
    
            // If user has a province assigned, filter reports by that province
            if ($provinceID) {
                $query->whereHas('barangay', function($q) use ($provinceID) {
                    $q->where('province_id', $provinceID);
                });
            }

        }

        $list = $query->orderBy('created_at','desc')->simplePaginate(15); 
        
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

    public function getMonthlyProvincialLevelReport(Request $request)
    {
         $validated = $request->validate([
            'province_id' => 'required|numeric|exists:provinces,id'
        ]);

        $provinceId = $validated['province_id'];
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $province = Province::with([
            'municipalities' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereHas('reports', function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('start', '<=', $endOfMonth)
                    ->where('end', '>=', $startOfMonth);
                });
            },
            'municipalities.reports' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('start', '<=', $endOfMonth)
                    ->where('end', '>=', $startOfMonth)
                    ->with(['values.indicator']);
            }
        ])
        ->find($provinceId);

        // Aggregate indicators for each municipality
        $province->municipalities->each(function($municipality) {
            // Collect all values from all reports in this municipality
            $allValues = $municipality->reports->flatMap(function($report) {
                return $report->values;
            });
            
            // Group by indicator_id and sum
            $aggregatedIndicators = $allValues->groupBy('indicator_id')->map(function($values) {
                $indicator = $values->first()->indicator;
                return [
                    'indicator_id' => $indicator->id,
                    'indicator_name' => $indicator->name,
                    'total_value' => $values->sum('value'),
                    'count' => $values->count()
                ];
            })->values();
            
            // Replace the reports with aggregated data
            $municipality->aggregated_indicators = $aggregatedIndicators;
            $municipality->total_reports = $municipality->reports->count();
            // $municipality->total_value = $municipality->reports->sum('valueSum');
            
        });

        return response()->json([
            'month' => Carbon::now()->format('F'),
            'province' => $province
        ]);
    }

    public function getUserMonthlyReport(Request $request)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $reports = $request->user()
            ->reports()
            ->with(['createdBy', 'barangay','values.indicator'])
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start', [$startOfMonth, $endOfMonth])
                ->orWhereBetween('end', [$startOfMonth, $endOfMonth]);
            })
            ->get();

        return response()->json([
            'reports' => $reports,
            'month' => Carbon::now()->format('F Y')
        ], 200);
    }

}
