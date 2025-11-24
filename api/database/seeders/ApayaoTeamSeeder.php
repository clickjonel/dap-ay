<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApayaoTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Calanasan - Cadaclan',
                'members' => [],
                'barangays' => [1045]
            ],
            [
                'name' => 'Calanasan - Eleazar',
                'members' => [],
                'barangays' => [1057]
            ],
            [
                'name' => 'Calanasan - Langnao',
                'members' => [],
                'barangays' => [1046]
            ],
            [
                'name' => 'Conner - Malama',
                'members' => [],
                'barangays' => [1071]
            ],
            [
                'name' => 'Conner - Padaoan',
                'members' => [],
                'barangays' => [1074]
            ],
            [
                'name' => 'Conner - Sacpil',
                'members' => [],
                'barangays' => [1077]
            ],
            [
                'name' => 'Flora - Allig',
                'members' => [],
                'barangays' => [1083]
            ],
            [
                'name' => 'Flora - Mallig',
                'members' => [],
                'barangays' => [1093]
            ],
            [
                'name' => 'Flora - Poblacion West',
                'members' => [],
                'barangays' => [1095]
            ],
            [
                'name' => 'Kabugao - Dagara',
                'members' => [],
                'barangays' => [1102]
            ],
            [
                'name' => 'Kabugao - Madatag',
                'members' => [],
                'barangays' => [1111]
            ],
            [
                'name' => 'Kabugao - Maragat',
                'members' => [],
                'barangays' => [1114]
            ],
            [
                'name' => 'Luna - Cagandungan',
                'members' => [],
                'barangays' => [1134]
            ],
            [
                'name' => 'Luna - Marag',
                'members' => [],
                'barangays' => [1124]
            ],
            [
                'name' => 'Luna - Salvacion',
                'members' => [],
                'barangays' => [1127]
            ],
            [
                'name' => 'Pudtol - Cacalaggan',
                'members' => [],
                'barangays' => [1145]
            ],
            [
                'name' => 'Pudtol - Mataguisi',
                'members' => [],
                'barangays' => [1149]
            ],
            [
                'name' => 'Pudtol - Swan',
                'members' => [],
                'barangays' => [1152]
            ],
            [
                'name' => 'Sta Marcela - Consuelo',
                'members' => [],
                'barangays' => [1165]
            ],
            [
                'name' => 'Sta Marcela - San Juan',
                'members' => [],
                'barangays' => [1175]
            ],
            [
                'name' => 'Sta Marcela - Barococ',
                'members' => [],
                'barangays' => [1164]
            ],
            
        ];

        foreach($teams as $team){

            $teamCreated = Team::create(['name' => $team['name']]);

            foreach($team['barangays'] as $brgy){

                Barangay::find($brgy)->update(['status' => 'Implementing PK']);

                TeamBarangay::create([
                    'team_id' => $teamCreated->id,
                    'barangay_id' => $brgy,
                ]);

            }

        }
    }
}
