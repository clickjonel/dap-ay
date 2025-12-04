<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\IndicatorDisaggregation;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    public function create(Request $request)
    {   
        Indicator::create($request->all());

        return response()->json('Indicator has been created.',201);
    }

    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = Indicator::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->with(['subProgram'])
                ->simplePaginate(15);

        return response()->json($list);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'active' => 'required|boolean',
            'type' => 'required|string',
        ]);

        Indicator::find($validated['id'])->update($validated);

        return response()->json('Updated Indicator Successfully',201);


    }

    public function getActiveIndicators(Request $request)
    {
        $indicators = Indicator::with(['subProgram'])->whereActive(1)->get();

         return response()->json($indicators,200);
    }

    public function findIndicator(Request $request)
    {
        $validated = $request->validate([
            'indicator_id' => 'required|numeric',
            'relationships' => 'nullable|array'
        ]);

        $query = Indicator::query();
        $query = $query->when(isset($validated['relationships']), function($query) use ($validated) {
            $query->with($validated['relationships']);
        });
        $indicator = $query->find($validated['indicator_id']);

        return response()->json($indicator,200);
    }

    public function updateDisaggregation(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'totalable' => 'required|boolean',
            'active' => 'required|boolean',
        ]);

        IndicatorDisaggregation::find($validated['id'])->update($validated);

        return response()->json('Updated Indicator Disaggregation Successfully',201);

    }

    public function createDisaggregation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'totalable' => 'required|boolean',
            'active' => 'required|boolean',
            'indicator_id' => 'required|numeric',
        ]);

        IndicatorDisaggregation::create($validated);

        return response()->json('Created Indicator Disaggregation Successfully',201);

    }

}
