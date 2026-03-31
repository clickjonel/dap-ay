<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\UserHandledMunicipality;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserHandledMunicipalityController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $provinceID = $user->accessLevels->pdoho_access_id;
        $municipalities = Municipality::where('province_id', $provinceID)->get();

        return Inertia::render('user/manageHandledMunicipalities', [
            'municipalities'         => $municipalities,
            'assignedMunicipalities' => $user->handledMunicipalities()->with('municipality')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'municipalities' => 'required|array',
            'municipalities.*' => 'exists:municipalities,id',
            'dcpo_no' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        $user->handledMunicipalities()->delete();

        foreach ($validated['municipalities'] as $municipalityID) {
            UserHandledMunicipality::create([
                'user_id' => $user->id,
                'municipality_id' => $municipalityID,
                'dcpo_no' => $validated['dcpo_no'] ?? null
            ]);
        }

        return back();
    }


}
