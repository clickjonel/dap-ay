<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\OrganizationalIndicator;
use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userBarangayIDs = $request->user()
            ->teams()
            ->with([
                'barangays:id',
            ])
            ->get()
            ->pluck('barangays')
            ->flatten()
            ->pluck('id')
            ->unique();

        $barangays = Barangay::query()
            ->when($request->user()->accessLevels->access_level === 2, function ($query) use ($userBarangayIDs) {
                $query->whereIn('id', $userBarangayIDs);
            })
            ->when($request->user()->accessLevels->access_level === 3, function ($query) use ($request) {
                $query->where('province_id', $request->user()->accessLevels->pdoho_access_id);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('psgc_code', 'like', "%{$search}%")
                    ->orWhereHas('municipality', function($query) use ($search){
                        $query->where('name', 'like', "%{$search}%");
                    });
            });

        // ── JSON response for async search (activity form, etc.) ──
        if ($request->boolean('only_search')) {
            return response()->json(
                $barangays->select('id', 'name', 'municipality_id', 'province_id')
                    ->with(['municipality:id,name', 'province:id,name'])
                    ->limit(20)
                    ->get()
            );
        }

        // ── Normal Inertia response ──
        $paginated = $barangays
            ->with(['province', 'municipality', 'pkProfile', 'organizationalIndicators', 'geography', 'population'])
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return inertia('barangay/barangays', [
            'barangays' => $paginated,
            'filters'   => $request->only('search'),
            'provinces' => Inertia::lazy(fn () => Province::with(['municipalities'])->orderBy('name')->get()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('barangay/createBarangay', [
            'provinces' => Province::with('municipalities:id,name,province_id')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'psgc_code'       => 'nullable|string|unique:barangays,psgc_code,' . ($barangay->id ?? 'NULL'),
            'name'            => 'required|string|max:255',
            'province_id'     => 'required|exists:provinces,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'mov_link' => 'nullable',
        ]);

        Barangay::create($request->only('psgc_code', 'name', 'province_id', 'municipality_id','mov_link'));

        return redirect()->route('barangays.index')->with('success', 'Barangay created successfully.');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barangay = Barangay::findOrFail($id)->load([
            'pkProfile',
            'organizationalIndicators.organizationalIndicator',
            'indicatorTargets',
            'geography',
            'population',
            'priorityPrograms'
        ]);

        $allIndicators = OrganizationalIndicator::all();

        // map all indicators, merging existing barangay values if connected
        $indicators = $allIndicators->map(function ($indicator) use ($barangay) {
            $existing = $barangay->organizationalIndicators
                ->firstWhere('org_indicator_id', $indicator->id);

            return [
                'id'          => $indicator->id,
                'name'        => $indicator->indicator_name,
                'value'       => $existing ? (int) $existing->value : null,
            ];
        });
        $usedProgramIds = $barangay->priorityPrograms->pluck('program_id');
        $programs = Program::whereNotIn('id', $usedProgramIds)->get();

        $program_indicators = ProgramIndicator::with(['program'])->get()->map(function($ind) use ($barangay) {
            $existing = $barangay->indicatorTargets->firstWhere('program_indicator_id', $ind->id);

            return [
                'id'     => $ind->id,
                'program_id' => $ind->program?->id,
                'name'   => $ind->indicator_name,
                'target' => $existing?->target ?? null,
                'served' => $existing?->served ?? null,
            ];
        });

        return inertia('barangay/manageBarangay',[
            'barangay' => $barangay,
            'indicators' => $indicators,
            'programs' => $programs,
            'program_indicators' => $program_indicators
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barangay = Barangay::with(['province.municipalities', 'municipality'])->findOrFail($id);

        return inertia('barangay/updateBarangay', [
            'barangay' => $barangay,
            'provinces' => Province::with(['municipalities'])->orderBy('name')->get()
        ]);

    }

    public function update(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'name'      => 'required|string',
            'psgc_code' => 'nullable|string',
            'mov_link'  => 'nullable|string',

            'geography'           => 'required|array',
            'geography.longitude' => 'nullable|numeric',
            'geography.latitude'  => 'nullable|numeric',
            'geography.is_gida'   => 'required|boolean',

            'pk_profile'            => 'required|array',
            'pk_profile.pk_status'  => 'required|string',
            'pk_profile.pk_site'    => 'required|boolean',

            'indicators'                   => 'required|array',
            'indicators.*.org_indicator_id' => 'required|integer|exists:organizational_indicators,id',
            'indicators.*.value'            => 'nullable|numeric',

            'priority_programs'             => 'required|array',
            'priority_programs.*.id'        => 'nullable|integer|exists:barangay_priority_programs,id',
            'priority_programs.*.program_id' => 'required|integer|exists:programs,id',
            'priority_programs.*.target'    => 'nullable|numeric',
            'priority_programs.*.order'     => 'required|integer',

            'indicator_targets'                          => 'required|array',
            'indicator_targets.*.program_indicator_id'   => 'required|integer|exists:program_indicators,id',
            'indicator_targets.*.program_id'             => 'required|integer|exists:programs,id',
            'indicator_targets.*.target'                 => 'nullable|numeric',
            'indicator_targets.*.served'                 => 'nullable|numeric',
        ]);

        // Update barangay
        $barangay->update([
            'name'      => $validated['name'],
            'psgc_code' => $validated['psgc_code'],
            'mov_link'  => $validated['mov_link'],
        ]);

        // Update or create geography (one-to-one)
        $barangay->geography()->updateOrCreate(
            ['barangay_id' => $barangay->id],
            $validated['geography']
        );

        // Update or create pk_profile (one-to-one)
        $barangay->pkProfile()->updateOrCreate(
            ['barangay_id' => $barangay->id],
            $validated['pk_profile']
        );

        // Sync indicators — upsert by barangay_id + org_indicator_id
        foreach ($validated['indicators'] as $ind) {
            $barangay->organizationalIndicators()->updateOrCreate(
                ['org_indicator_id' => $ind['org_indicator_id']],
                ['value'            => $ind['value']]
            );
        }

       // sync indicator targets
        foreach ($validated['indicator_targets'] as $ind) {
            $barangay->indicatorTargets()->updateOrCreate(
                ['program_indicator_id' => $ind['program_indicator_id']],
                [
                    'program_id' => $ind['program_id'],
                    'target'     => $ind['target'],
                    'served'     => $ind['served'],
                ]
            );
        }

        // Priority programs — delete removed, upsert existing/new
        $incomingIds = collect($validated['priority_programs'])
            ->pluck('id')
            ->filter()
            ->values();

        // Delete programs not in the incoming list
        $barangay->priorityPrograms()
            ->whereNotIn('id', $incomingIds)
            ->delete();

        // Upsert each program
        foreach ($validated['priority_programs'] as $pp) {
            $barangay->priorityPrograms()->updateOrCreate(
                ['id' => $pp['id'] ?? 0],
                [
                    'program_id' => $pp['program_id'],
                    'target'     => $pp['target'],
                    'order'      => $pp['order'],
                ]
            );
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
