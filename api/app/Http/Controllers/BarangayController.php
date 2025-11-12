<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function selection(Request $request)
    {
        $municipalityID = $request->municipality_id;
        $pkSite = $request->pk_site ?? false;

        $barangays = Barangay::query()
                        ->when(isset($municipalityID),function($query) use ($municipalityID) {
                            $query->where('municipality_id',$municipalityID);
                        })
                        // ->when(isset($pkSite),function($query) use ($pkSite) {
                        //     $query->whereHas('site',$municipalityID);
                        // })
                        ->get();

        return response()->json($barangays);
    }

    public function list(Request $request)
    {
        $barangays = Barangay::query()
                    ->when(isset($request->keyword), function($query) use ($request){
                        $query->where('name','LIKE', "%{$request->keyword}%");
                    })
                    ->with([
                        'province',
                        'municipality'
                    ])
                    ->orderBy('id','DESC')
                    ->simplePaginate(20);

        return response()->json($barangays,200);
    }


    public function find(Request $request)
    {
        $barangay_id = $request->barangay_id;
        $relationships = $request->relationships ?? [];

        $brgy = Barangay::with($relationships)->findOrFail($barangay_id);

        return response()->json($brgy,200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
            'status' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'total_purok' => 'nullable|numeric',
            'target_purok' => 'nullable|numeric',
            'target_population' => 'nullable|numeric'
        ]);

        Barangay::find($validated['id'])->update($validated);

        return response()->json('Updated Barangay Successfully',200);
    }

}
