<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activity\CreateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityBarangay;
use App\Models\ActivityProgram;
use App\Models\ActivityResource;
use App\Models\ActivityResources;
use App\Models\ActivityValue;
use App\Models\ActivityValueBreakdown;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    
    public function createActivity(CreateActivityRequest $request)
    {
        $validated = $request->validated();

        $activity = Activity::create($validated);

        return response()->json('Activity Created Successfully, Add barangay participants and programs to the activity by clicking the elipses then click Update Activity Button on the selection.',201);
    }

    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Activity::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                 $query->where('activity_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('activity_venue', 'LIKE', "%{$keyword}%");
                })
                // ->with(['subPrograms'])
                ->simplePaginate(10);

        return response()->json($list,200);
    }

    public function findActivity(Request $request)
    {
        $activity_id = $request->activity_id;
        $relationships = $request->relationships ?? [];

        $activity = Activity::with($relationships)->findOrFail($activity_id);

        return response()->json($activity,200);
    }

    public function updateActivity(Request $request)
    {
        $activity = Activity::findOrFail($request->activity_id)->update($request->all());

        return response()->json($activity,200);
    }

    public function addProgram(Request $request)
    {
        $activityProgram = ActivityProgram::create($request->all());

        return response()->json($activityProgram,200);
    }

    public function deleteActivityProgram(Request $request)
    {
        ActivityProgram::find($request->activity_program_id)->delete();

        return response()->json('Deleted Successfully',201);
    }

    public function addBarangay(Request $request)
    {
        $activityProgram = ActivityBarangay::create($request->all());

        return response()->json($activityProgram,200);
    }

    public function deleteActivityBarangay(Request $request)
    {
        ActivityBarangay::find($request->activity_barangay_id)->delete();

        return response()->json('Deleted Successfully',201);
    }

    public function addResource(Request $request)
    {
        $resource = ActivityResource::create($request->all());

        return response()->json($resource,200);
    }

    public function deleteActivityResource(Request $request)
    {
        ActivityResource::find($request->activity_resource_id)->delete();

        return response()->json('Deleted Successfully',201);
    }

    public function populateIndicators(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:pk_activities,activity_id',
            'indicators' => 'required|array',
            'indicators.*.barangay_id' => 'required|exists:pk_barangays,barangay_id',
            'indicators.*.sub_program_id' => 'required|exists:pk_sub_programs,sub_program_id',
            'indicators.*.program_indicator_id' => 'required|exists:pk_program_indicators,program_indicator_id',
            'indicators.*.total_served' => 'required|integer',
            'indicators.*.breakdown' => 'nullable|array',
        ]);

        foreach($validated['indicators'] as $indicatorValue){
           $activityValueCreated = ActivityValue::create([
                'activity_id' => $validated['activity_id'],
                'barangay_id' => $indicatorValue['barangay_id'],
                'sub_program_id' => $indicatorValue['sub_program_id'],
                'program_indicator_id' => $indicatorValue['program_indicator_id'],
                'total_served' => $indicatorValue['total_served'],
           ]);

           if(!empty($indicatorValue['breakdown'])){
               foreach($indicatorValue['breakdown'] as $breakdown){
                     ActivityValueBreakdown::create([
                        'activity_value_id' => $activityValueCreated->activity_value_id,
                        'key' => $breakdown['key'],
                        'value' => $breakdown['value'],
                    ]);
               }
           }
        }

        Activity::find($validated['activity_id'])->update([
            'date_populated' => now()
        ]);

        return response()->json('Successfully Added Activities on Activity.', 200);
    }

    public function getActivityReportData(Request $request)
    {
        $activity = Activity::with([
            'activityPrograms.subProgram.program',
            'activityPrograms.subProgram.indicators',
            'activityBarangays.barangay',
            'activityResources',
            'activityValues.barangay',
            'activityValues.subProgram',
            'activityValues.programIndicator',
            'activityValues.breakdown',
        ])->findOrFail($request->activity_id);

        $values = $activity->activityValues
            ->groupBy('barangay_id')
            ->mapWithKeys(fn($vals, $id) => [
                ($vals->first()->barangay?->barangay_name ?? 'Unknown') =>
                 
                $vals->groupBy('sub_program_id')->mapWithKeys(fn($v, $sid) => [
                    ($v->first()->subProgram?->sub_program_name ?? 'Unknown') => $v
                ])
            ]);

        return response()->json([
            'activity' => $activity,
            'programs' => $activity->activityPrograms,
            'barangays' => $activity->activityBarangays,
            'resources' => $activity->activityResources,
            'values' => $values
        ]);

    }

}
