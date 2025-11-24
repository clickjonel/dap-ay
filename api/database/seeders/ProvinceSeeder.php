<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
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
        ];

        foreach($provinces as $province){
            $provinceID = DB::connection('dap-ay')->table('provinces')->insertGetId([
                                'name' => $province['province_name'],
                            ]);

            $municipalities = DB::connection('pkpulse')->table('pkp_municipality')->where('province_id',$province['dohis_province_id'])->get();
            
            foreach($municipalities as $mun){
                $municipality = Municipality::create([
                    'province_id' => $provinceID,
                    'name'  => $mun->municipality_name,
                ]);

                $barangays = DB::connection('pkpulse')->table('pkp_barangay')->where('municipality_id',$mun->municipality_id)->get();

                foreach($barangays as $brgy){
                    Barangay::create([
                        'province_id' => $provinceID,
                        'municipality_id' => $municipality->id,
                        'name'  => $brgy->barangay_name,
                        'status' => 'Not Implementing',
                        'latitude' => null,
                        'longitude' => null,
                        'total_purok' => null,
                        'target_purok' => null,
                        'target_population' => null
                    ]);
                    
                }
            }
        }
    }
}
