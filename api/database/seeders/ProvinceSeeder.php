<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Site;
use App\Models\SiteProfile;
use App\Models\SiteProgram;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $provinces = [
            [
                'dohis_province_id'   => 1401,
                'province_name' => 'Abra',
            ],
            [
                'dohis_province_id'   => 1411,
                'province_name' => 'Benguet',
            ],
            [
                'dohis_province_id'   => 1427,
                'province_name' => 'Ifugao',
            ],
            [
                'dohis_province_id'   => 1432,
                'province_name' => 'Kalinga',
            ],
            [
                'dohis_province_id'   => 1444,
                'province_name' => 'Mountain Province',
            ],
            [
                'dohis_province_id'   => 1481,
                'province_name' => 'Apayao',
            ],
            [
                'dohis_province_id'   => 1430,
                'province_name' => 'City of Baguio',
            ],
        ];

        foreach($provinces as $province){
            $provinceID = DB::connection('dap-ay')->table('pk_provinces')->insertGetId([
                                    'province_name' => $province['province_name'],
                                ]);

            $municipalities = DB::connection('pkpulse')->table('pkp_municipality')->where('province_id',$province['dohis_province_id'])->get();
            
            foreach($municipalities as $mun){
                $municipality = Municipality::create([
                    'province_id' => $provinceID,
                    'municipality_name'  => $mun->municipality_name,
                ]);

                $barangays = DB::connection('pkpulse')->table('pkp_barangay')->where('municipality_id',$mun->municipality_id)->get();

                foreach($barangays as $brgy){
                    $barangay = Barangay::create([
                        'province_id' => $provinceID,
                        'municipality_id' => $municipality->municipality_id,
                        'barangay_name'  => $brgy->barangay_name,
                    ]);

                    // create team
                    // $provinceName = Province::find($provinceID)->province_name;
                    // Team::create([
                    //     'team_name' => "{$provinceName}-{$municipality->municipality_name}-{$barangay->barangay_name} Purokalusugan Team",
                    //     'barangay_id' => $barangay->barangay_id,
                    //     'team_active' => true,
                    // ]);

                    // //create site if present
                    // $site = DB::connection('pkpulse')->table('pkp_site')->where('barangay_id',$brgy->barangay_id)->first() ?? null;
                    
                    // if(!is_null($site)){
                    //     $siteCreated = Site::create([
                    //         'barangay_id' => $barangay->barangay_id,
                    //         'latitude' => $site->latitude,
                    //         'longitude' => $site->longitude
                    //     ]);

                    //     $siteProfile = SiteProfile::create([
                    //         'site_id' => $siteCreated->site_id,
                    //         'total_purok' => $site->no_purok,
                    //         'total_sitio' => $site->no_sitio,
                    //         'total_target_purok' => $site->target_purok,
                    //         'total_target_sitio' => $site->target_sitio,
                    //         'total_target_population' => $site->population
                    //     ]);

                    //     SiteProgram::create([
                    //         'program_id' => 1,
                    //         'profile_id' => $siteProfile->profile_id,
                    //         'target_baseline' => 1000,
                    //         'priority_order' => 1
                    //     ]);

                    // }
                    
                }
            }
        }
    }
}
