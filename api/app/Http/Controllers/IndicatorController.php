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
                 $query->where('name', 'LIKE', "%{$keyword}%");})
                ->simplePaginate(20);

        return response()->json($list);
    }
}
