<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Indicator;
use App\Models\ProgramIndicator;
use App\Models\Province;
use App\Models\ReportValue;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getAdminDashboardData(){
        //teams data
       $teamPerProvince = Province::leftJoin('barangays', 'provinces.id', '=', 'barangays.province_id')
        ->leftJoin('team_barangays', 'barangays.id', '=', 'team_barangays.barangay_id')
        ->selectRaw('provinces.id, provinces.name as province, COUNT(DISTINCT team_barangays.team_id) as total')
        ->groupBy('provinces.id', 'provinces.name')
        ->orderBy('provinces.name')
        ->get();

        $teamTotal = Team::count();

        // pk site data
        $barangayGrouped = Barangay::select('status')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('status')
        ->get();

        // Indicators Total
        $indicatorsValueSum = Indicator::withSum('reportValues as values_sum','value')->get();

        
       return response()->json([
            'team' => [
                'countPerProvince' => $teamPerProvince,
                'total' => $teamTotal,
            ],
            'barangayData' => [
                'barangayByPKStatus' => $barangayGrouped ,
                'barangayTotal' => Barangay::count()
            ],
            'indicatorsValueSum' => $indicatorsValueSum,
            'demographics' => $this->getDemographics(),
            'indicatorTotalsPerProvince' => $this->getIndicatorTotalPerProvince()
       ]);
    }

    public function getDemographics()
    {
        $provinces = Province::with(['reports.barangay'])->withCount(['municipalities', 'barangays'])
        ->get()
        ->map(function ($province) {
            $province->pk_statuses = $province->barangays->groupBy('status')->map(fn($group) => $group->count());
            // $province->reports_by_municipality = $province->reports->groupBy(function($report) {
            //     return $report->barangay->municipality->name;
            // })->map(fn($group) => $group->count());
            return $province;
        });

        return [
            'total_provinces' => $provinces->count(),
            'total_municipalities' => $provinces->sum('municipalities_count'),
            'total_barangays' => $provinces->sum('barangays_count'),
            'provinces' => $provinces
        ];
    }

    public function getIndicatorTotalPerProvince()
    {
        $provinces = Province::with(['reports.values.indicator'])->get();

        return $provinces;
    }

}
