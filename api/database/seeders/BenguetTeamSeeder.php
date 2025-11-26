<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenguetTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Atok - Poblacion',
                'members' => [],
                'barangays' => [310]
            ],
            [
                'name' => 'Atok - Pasdong',
                'members' => [],
                'barangays' => [309]
            ],
            [
                'name' => 'Atok - Naguey',
                'members' => [],
                'barangays' => [307]
            ],
            [
                'name' => 'Bakun - Dalipey',
                'members' => [],
                'barangays' => [443]
            ],
            [
                'name' => 'Bakun - Bagu',
                'members' => [],
                'barangays' => [442]
            ],
            [
                'name' => 'Bakun - Kayapa',
                'members' => [],
                'barangays' => [445]
            ],
            [
                'name' => 'Bokod - Bobok-bisal',
                'members' => [],
                'barangays' => [450]
            ],
            [
                'name' => 'Bokod - Nawal',
                'members' => [],
                'barangays' => [454]
            ],
            [
                'name' => 'Bokod - Pito',
                'members' => [],
                'barangays' => [455]
            ],
            [
                'name' => 'Buguias - Amlimay',
                'members' => [],
                'barangays' => [460]
            ],
            [
                'name' => 'Buguias - Baculongan Sur',
                'members' => [],
                'barangays' => [469]
            ],
            [
                'name' => 'Buguias - Sebang',
                'members' => [],
                'barangays' => [471]
            ],
            [
                'name' => 'Itogon - Tuding',
                'members' => [],
                'barangays' => [478]
            ],
            [
                'name' => 'Itogon - Ucab',
                'members' => [],
                'barangays' => [479]
            ],
            [
                'name' => 'Itogon - Virac',
                'members' => [],
                'barangays' => [480]
            ],
            [
                'name' => 'Kabayan - Ballay',
                'members' => [],
                'barangays' => [483]
            ],
            [
                'name' => 'Kabayan - Bashoy',
                'members' => [],
                'barangays' => [484]
            ],
            [
                'name' => 'Kabayan - Pacso',
                'members' => [],
                'barangays' => [491]
            ],
            [
                'name' => 'Kapangan - Gasweling',
                'members' => [],
                'barangays' => [501]
            ],
            [
                'name' => 'Kapangan - Sagubo',
                'members' => [],
                'barangays' => [507]
            ],
            [
                'name' => 'Kapangan - Taba-oa',
                'members' => [],
                'barangays' => [508]
            ],
            [
                'name' => 'Kibungan - Badeo',
                'members' => [],
                'barangays' => [509]
            ],
            [
                'name' => 'Kibungan - Lubo',
                'members' => [],
                'barangays' => [510]
            ],
            [
                'name' => 'Kibungan - Tacadang',
                'members' => [],
                'barangays' => [515]
            ],
            [
                'name' => 'La Trinidad - Bahong',
                'members' => [],
                'barangays' => [519]
            ],
            [
                'name' => 'La Trinidad - Shilan',
                'members' => [],
                'barangays' => [529]
            ],
            [
                'name' => 'La Trinidad - Wangal',
                'members' => [],
                'barangays' => [531]
            ],
            [
                'name' => 'Mankayan - Balili',
                'members' => [],
                'barangays' => [520]
            ],
            [
                'name' => 'Mankayan - Guinaoang',
                'members' => [],
                'barangays' => [537]
            ],
            [
                'name' => 'Mankayan - Poblacion',
                'members' => [],
                'barangays' => [540]
            ],
            [
                'name' => 'Sablan - Bagong',
                'members' => [],
                'barangays' => [544]
            ],
            [
                'name' => 'Sablan - Kamog',
                'members' => [],
                'barangays' => [549]
            ],
            [
                'name' => 'Sablan - Pappa',
                'members' => [],
                'barangays' => [550]
            ],
            [
                'name' => 'Tuba - Ansagan',
                'members' => [],
                'barangays' => [552]
            ],
            [
                'name' => 'Tuba - Camp 3',
                'members' => [],
                'barangays' => [554]
            ],
            [
                'name' => 'Tuba - Tadiangan',
                'members' => [],
                'barangays' => [561]
            ],
            [
                'name' => 'Tublay - Ambassador',
                'members' => [],
                'barangays' => [565]
            ],
            [
                'name' => 'Tublay - Caponga',
                'members' => [],
                'barangays' => [570]
            ],
            [
                'name' => 'Tublay - Daclan',
                'members' => [],
                'barangays' => [569]
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
