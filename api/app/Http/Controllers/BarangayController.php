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
}
