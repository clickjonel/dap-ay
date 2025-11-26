<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MountainProvinceTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Barlig - Gawana',
                'members' => [],
                'barangays' => [909]
            ],
            [
                'name' => 'Barlig - Kaleo',
                'members' => [],
                'barangays' => [902]
            ],
            [
                'name' => 'Barlig - Lias Kanluran',
                'members' => [],
                'barangays' => [904]
            ],
            [
                'name' => 'Bauko - Monamon Norte',
                'members' => [],
                'barangays' => [924]
            ],
            [
                'name' => 'Bauko - Monamon Sur',
                'members' => [],
                'barangays' => [925]
            ],
            [
                'name' => 'Bauko - Mabaay',
                'members' => [],
                'barangays' => [922]
            ],
            [
                'name' => 'Besao - Gueday',
                'members' => [],
                'barangays' => [939]
            ],
            [
                'name' => 'Besao - Laylaya',
                'members' => [],
                'barangays' => [941]
            ],
            [
                'name' => 'Besao - Tamboan',
                'members' => [],
                'barangays' => [945]
            ],
            [
                'name' => 'Bontoc - Caneo',
                'members' => [],
                'barangays' => [952]
            ],
            [
                'name' => 'Bontoc - Dalican',
                'members' => [],
                'barangays' => [953]
            ],
            [
                'name' => 'Bontoc - Mainit',
                'members' => [],
                'barangays' => [956]
            ],
            [
                'name' => 'Natonin - Banawal',
                'members' => [],
                'barangays' => [966]
            ],
            [
                'name' => 'Natonin - Saliok',
                'members' => [],
                'barangays' => [970]
            ],
            [
                'name' => 'Natonin - Tonglayan',
                'members' => [],
                'barangays' => [972]
            ],
            [
                'name' => 'Paracelis - Buringal',
                'members' => [],
                'barangays' => [980]
            ],
            [
                'name' => 'Paracelis - Bantay',
                'members' => [],
                'barangays' => [977]
            ],
            [
                'name' => 'Paracelis - Poblacion',
                'members' => [],
                'barangays' => [982]
            ],
            [
                'name' => 'Sabangan - Napua',
                'members' => [],
                'barangays' => [993]
            ],
            [
                'name' => 'Sabangan - Losad',
                'members' => [],
                'barangays' => [991]
            ],
            [
                'name' => 'Sabangan - Busa',
                'members' => [],
                'barangays' => [985]
            ],
            [
                'name' => 'Sadanga - Anabel',
                'members' => [],
                'barangays' => [998]
            ],
            [
                'name' => 'Sadanga - Betwagan',
                'members' => [],
                'barangays' => [1000]
            ],
            [
                'name' => 'Sadanga - Saclit',
                'members' => [],
                'barangays' => [1004]
            ],
            [
                'name' => 'Sagada - Fidelisan',
                'members' => [],
                'barangays' => [1015]
            ],
            [
                'name' => 'Sagada - Pide',
                'members' => [],
                'barangays' => [1019]
            ],
            [
                'name' => 'Sagada - Tetep-an Norte',
                'members' => [],
                'barangays' => [1024]
            ],
            [
                'name' => 'Tadian - Mabalite',
                'members' => [],
                'barangays' => [1037]
            ],
            [
                'name' => 'Tadian - Duagan',
                'members' => [],
                'barangays' => [1032]
            ],
            [
                'name' => 'Tadian - Dacudac',
                'members' => [],
                'barangays' => [1033]
            ],
        ];

        foreach($teams as $team){

            $teamCreated = Team::create(['name' => $team['name']]);

            foreach($team['barangays'] as $brgy){

                Barangay::find($brgy)->update(['status' => 'Implementing PK']);
                BarangayPriorityProgram::create([
                    'barangay_id' => $brgy,
                    'sub_program_id' => 1,
                    'baseline' => null,
                    'order' => 1,
                ]);

                TeamBarangay::create([
                    'team_id' => $teamCreated->id,
                    'barangay_id' => $brgy,
                ]);

            }

        }

    }
}
