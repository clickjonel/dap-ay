<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Program;
use App\Models\PurokalusuganActivity;
use App\Models\PurokalusuganActivityBarangay;
use App\Models\PurokalusuganActivityProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurokalusuganActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $accessLevel = $user->accessLevels;
    
        $activities = PurokalusuganActivity::query()
            ->with(['programs:id,name'])
            ->withCount('barangays')
            ->when(
                $accessLevel->access_level === 2,
                fn($query) => $query->whereHas('barangays', fn($q) =>
                    $q->where('province_id', $accessLevel->pdoho_access_id)
                )
            )
            ->when(
                $request->filled('search'),
                fn($q) => $q->where('activity_name', 'like', "%{$request->search}%")
            )
            ->orderBy('date_start', 'desc')
            ->paginate(10)
            ->withQueryString();
    
        return Inertia::render('activity/purokalusuganActivities', [
            'activities' => $activities,
            'filters'    => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::get();
        $barangays = Barangay::get();

        return Inertia::render('activity/createPKActivity', [
            'programs' => $programs,
            'barangays' => $barangays,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'type'          => 'required|in:small,large',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
            'total_clients' => 'required|integer|min:0',
            'program_ids'   => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
            'barangay_ids'  => 'required|array|min:1',
            'barangay_ids.*'=> 'exists:barangays,id',
        ]);

        $activity = PurokalusuganActivity::create([
            'activity_name' => $validated['activity_name'],
            'type'          => $validated['type'],
            'date_start'    => $validated['date_start'],
            'date_end'      => $validated['date_end'],
            'total_clients' => $validated['total_clients'],
        ]);

        foreach ($validated['program_ids'] as $programId) {
            PurokalusuganActivityProgram::create([
                'pk_activity_id' => $activity->id,
                'program_id' => $programId,
            ]);
        }

        foreach ($validated['barangay_ids'] as $barangayId) {
            PurokalusuganActivityBarangay::create([
                'pk_activity_id' => $activity->id,
                'barangay_id' => $barangayId,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurokalusuganActivity $purokalusuganActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurokalusuganActivity $pkActivity)
    {
         return Inertia::render('activity/editPKActivity', [
            'activity' => $pkActivity->load(['programs:id,name', 'barangays:id,name']),
            'programs' => Program::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurokalusuganActivity $pkActivity)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'type'          => 'required|in:small,large',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date|after_or_equal:date_start',
            'total_clients' => 'required|integer|min:0',
            'program_ids'   => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
            'barangay_ids'  => 'required|array|min:1',
            'barangay_ids.*'=> 'exists:barangays,id',
        ]);

        $pkActivity->update([
            'activity_name' => $validated['activity_name'],
            'type'          => $validated['type'],
            'date_start'    => $validated['date_start'],
            'date_end'      => $validated['date_end'],
            'total_clients' => $validated['total_clients'],
        ]);

        $pkActivity->programs()->sync($validated['program_ids']);
        $pkActivity->barangays()->sync($validated['barangay_ids']);

        return redirect('/pk-activities');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurokalusuganActivity $purokalusuganActivity)
    {
        //
    }
}
