<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayOrganizationalIndicator;
use App\Models\OrganizationalIndicator;
use Illuminate\Http\Request;

class BarangayOrganizationalIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $org_indicators = OrganizationalIndicator::get();
        $barangay = Barangay::with(['province','municipality'])->findOrFail($request->barangay_id);

        return Inertia('barangay/createBarangayOrganizationalIndicators', [
            'org_indicators' => $org_indicators,
            'barangay' => $barangay
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'indicators' => 'required|array',
            'indicators.*.organizational_indicator_id' => 'required|exists:organizational_indicators,id',
            'indicators.*.value' => 'nullable|string',
        ]);

        foreach ($validated['indicators'] as $indicatorData) {
            BarangayOrganizationalIndicator::create([
                'barangay_id' => $validated['barangay_id'],
                'org_indicator_id' => $indicatorData['organizational_indicator_id'],
                'value' => $indicatorData['value'] ?? null,
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(BarangayOrganizationalIndicator $barangayOrganizationalIndicator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($barangayID)
    {
        $barangay = Barangay::with(['province', 'municipality', 'organizationalIndicators'])
            ->findOrFail($barangayID);

        $org_indicators = OrganizationalIndicator::get()->map(function ($indicator) use ($barangay) {
            $existing = $barangay->organizationalIndicators
                ->firstWhere('org_indicator_id', $indicator->id);

            return [
                'id'                         => $indicator->id,
                'indicator_name'             => $indicator->indicator_name,
                'value'                      => $existing?->value ?? '',
                'barangay_indicator_id'      => $existing?->id ?? null, // null = new, has id = existing
            ];
        });

        return Inertia('barangay/updateBarangayOrganizationalIndicators', [
            'barangay'       => $barangay,
            'org_indicators' => $org_indicators,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'                                      => 'required|exists:barangays,id',
            'indicators'                                       => 'required|array',
            'indicators.*.organizational_indicator_id'         => 'required|exists:organizational_indicators,id',
            'indicators.*.barangay_indicator_id'               => 'nullable|exists:barangay_organizational_indicators,id',
            'indicators.*.value'                               => 'nullable|string',
        ]);

        $barangay = Barangay::findOrFail($validated['barangay_id']);

        foreach ($validated['indicators'] as $item) {
            if (!empty($item['barangay_indicator_id'])) {
                BarangayOrganizationalIndicator::where('id', $item['barangay_indicator_id'])
                    ->update(['value' => $item['value'] ?? 0]);
            } else {
                $barangay->organizationalIndicators()->create([
                    'org_indicator_id' => $item['organizational_indicator_id'],
                    'value'            => $item['value'] ?? null,
                ]);
            }
        }

        return redirect('/barangays');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangayOrganizationalIndicator $barangayOrganizationalIndicator)
    {
        //
    }
}
