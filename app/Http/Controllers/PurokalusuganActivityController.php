<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurokalusuganActivityRequests\StorePurokalusuganActivityRequest;
use App\Http\Requests\PurokalusuganActivityRequests\UpdatePurokalusuganActivityRequest;
use App\Models\Barangay;
use App\Models\Program;
use App\Models\PurokalusuganActivity;
use App\Models\PurokalusuganActivityBarangay;
use App\Models\PurokalusuganActivityProgram;
use App\Models\User;
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
            // ->with(['programs:id,name'])
            ->withCount(['barangays','participants','programs'])
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
    public function create(Request $request)
    {
        $user        = $request->user();
        $accessLevel = $user->accessLevels;

        $programs = Program::select('id', 'name')->orderBy('name')->get();

        $barangays = Barangay::query()
            ->select('id', 'name', 'municipality_id')
            ->with(['municipality:id,name'])
            ->when(
                in_array($accessLevel->access_level, [2, 3]),
                fn($q) => $q->where('province_id', $accessLevel->pdoho_access_id)
            )
            ->orderBy('name')
            ->get()
            ->map(fn($b) => [
                'id'   => $b->id,
                'name' => "{$b->name} — {$b->municipality->name}",
            ]);

        $hrh = User::query()
            ->select('id', 'name')
            ->when(in_array($accessLevel->access_level, [2, 3]), function ($query) use ($accessLevel) {
                $query->whereHas('accessLevels', function($query) use ($accessLevel) {
                    $query->where('pdoho_access_id', $accessLevel->pdoho_access_id);
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('activity/createPKActivity', [
            'programs'  => $programs,
            'barangays' => $barangays,
            'hrh' => $hrh
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurokalusuganActivityRequest $request)
    {
        $validated = $request->validated();

        $activity = PurokalusuganActivity::create([
            'activity_name' => $validated['activity_name'],
            'type'          => $validated['type'],
            'date_start'    => $validated['date_start'],
            'date_end'      => $validated['date_end'],
            'total_clients' => $validated['total_clients'],
        ]);

        $activity->programs()->attach($validated['programs']);
        $activity->barangays()->attach($validated['barangays']);
        $activity->participants()->attach($validated['hrh'] ?? []);

        redirect()->route('pk-activities.index');

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
    public function edit(Request $request, int $id)
    {
        $user = $request->user();
        $accessLevel = $user->accessLevels;

        $activity = PurokalusuganActivity::with(['participants','barangays','programs'])->find($id);

        $programs = Program::select('id', 'name')->orderBy('name')->get();

        $barangays = Barangay::query()
            ->select('id', 'name', 'municipality_id')
            ->with(['municipality:id,name'])
            ->when(
                in_array($accessLevel->access_level, [2, 3]),
                fn($q) => $q->where('province_id', $accessLevel->pdoho_access_id)
            )
            ->orderBy('name')
            ->get()
            ->map(fn($b) => [
                'id'   => $b->id,
                'name' => "{$b->name} — {$b->municipality->name}",
            ]);

        $hrh = User::query()
            ->select('id', 'name')
            ->when(in_array($accessLevel->access_level, [2, 3]), function ($query) use ($accessLevel) {
                $query->whereHas('accessLevels', function($query) use ($accessLevel) {
                    $query->where('pdoho_access_id', $accessLevel->pdoho_access_id);
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('activity/editPKActivity', [
            'programs'  => $programs,
            'barangays' => $barangays,
            'hrh' => $hrh,
            'activity' => $activity
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurokalusuganActivityRequest $request, PurokalusuganActivity $pkActivity)
    {
        $validated = $request->validated();

        $pkActivity->update([
            'activity_name' => $validated['activity_name'],
            'type'          => $validated['type'],
            'date_start'    => $validated['date_start'],
            'date_end'      => $validated['date_end'],
            'total_clients' => $validated['total_clients'],
        ]);

        $pkActivity->programs()->sync($validated['programs']);
        $pkActivity->barangays()->sync($validated['barangays']);
        $pkActivity->participants()->sync($validated['hrh']);

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
