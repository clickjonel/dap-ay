<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
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
}
