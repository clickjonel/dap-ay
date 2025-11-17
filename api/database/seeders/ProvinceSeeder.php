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
            // [
            //     'dohis_province_id'   => 1430,
            //     'province_name' => 'City of Baguio',
            // ],
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
                $bagiuo_pk_barangays = [1049,1121,1122,1052,1051,1172,1087,1108,1105,1056,1060,1165,1080,1054,1117,1152,1123,1115,1103,1078,1085,1061,1089,1162,1109,1156,1082,1109,1155,1077,1110,1097,1132,1066,1111,1114,1136,1073,1170,1070,1071,1102,1053,1161];

                foreach($barangays as $brgy){
                    $barangay = Barangay::create([
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

                    if (in_array($barangay->id, $bagiuo_pk_barangays)) {
                        $barangay->update(['status' => 'Implementing PK']);

                        BarangayPriorityProgram::create([
                            'barangay_id' => $barangay->id,
                            'sub_program_id' => 1,
                            'baseline' => null,
                            'order' => 1,
                        ]);
                    }
                    
                }
            }
        }
    }
}
