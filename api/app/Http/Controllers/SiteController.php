<?php

namespace App\Http\Controllers;

use App\Http\Requests\Site\CreateSiteProfileRequest;
use App\Models\Site;
use App\Models\SiteProfile;
use App\Models\SiteProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiteController extends Controller
{
    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';
    
        $query = Site::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                    $query->whereHas('barangay', function($q) use ($keyword) {
                        $q->where('barangay_name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->with(['barangay.province','barangay.municipality','profile.priorityPrograms.program'])
                ->simplePaginate(10);

        return response()->json($list,200);
    }

    public function createSiteProfile(CreateSiteProfileRequest $request)
    {
        
        try {
            DB::beginTransaction();
            
            $validated = $request->validated();
            
            // Create site
            $site = Site::create($validated['site']);
    
            // Create profile
            $profile = $site->profile()->create($validated['profile']);
            
            // Create programs
           $profile->priorityPrograms()->createMany($validated['programs']);
            
            DB::commit();
            
            return response()->json('Site profile created successfully', 201);
        
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to create site profile: ' . $e->getMessage(),
                'data' =>  $validated['profile']
            ], 500);
        }
    }

    public function updateSite($site_id,Request $request)
    {
        Site::findOrFail($site_id)->update($request->all());

        return response()->json('Successfully Updated Site Details',201);
    }

    public function updateSiteProfile($site_profile_id,Request $request)
    {
        $profile = SiteProfile::findOrFail($site_profile_id);
        if($profile) $profile->update($request->all());


        return response()->json('Successfully Updated Site Profile',201);
    }

    public function updateSitePrograms(Request $request)
    {
        $programs = $request->all();
        foreach($programs as $program){
            $prog = SiteProgram::find($program['site_program_id']);
            $prog->update([
                'program_id' => $program['program_id'],
                'profile_id' => $program['profile_id'],
                'target_baseline' => $program['target_baseline'],
                'priority_order' => $program['priority_order']
            ]);
        }


        return response()->json('Successfully Updated Site Programs',201);
    }
}
