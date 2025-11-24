<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IfugaoTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Aguinaldo - Itab',
                'members' => [],
                'barangays' => [706]
            ],
            [
                'name' => 'Aguinaldo - Ubao',
                'members' => [],
                'barangays' => [713]
            ],
            [
                'name' => 'Aguinaldo - Chalalo',
                'members' => [],
                'barangays' => [702]
            ],
            [
                'name' => 'Alfonso Lista - Kiling',
                'members' => [],
                'barangays' => [685]
            ],
            [
                'name' => 'Alfonso Lista - San Marcos',
                'members' => [],
                'barangays' => [692]
            ],
            [
                'name' => 'Alfonso Lista - San Quintin',
                'members' => [],
                'barangays' => [693]
            ],
            [
                'name' => 'Asipulo - Camandag',
                'members' => [],
                'barangays' => [741]
            ],
            [
                'name' => 'Asipulo - Cawayan',
                'members' => [],
                'barangays' => [139]
            ],
            [
                'name' => 'Asipulo - Namal',
                'members' => [],
                'barangays' => [744]
            ],
            [
                'name' => 'Banaue - Amganad',
                'members' => [],
                'barangays' => [573]
            ],
            [
                'name' => 'Banaue - Pula',
                'members' => [],
                'barangays' => [590]
            ],
            [
                'name' => 'Banaue - Poblacion',
                'members' => [],
                'barangays' => [583]
            ],
            [
                'name' => 'Hingyon - Namulditan',
                'members' => [],
                'barangays' => [720]
            ],
            [
                'name' => 'Hingyon - Umalbong',
                'members' => [],
                'barangays' => [725]
            ],
            [
                'name' => 'Hingyon - O-ong',
                'members' => [],
                'barangays' => [721]
            ],
            [
                'name' => 'Hunduan - Poblacion',
                'members' => [],
                'barangays' => [594]
            ],
            [
                'name' => 'Hunduan - Hapao',
                'members' => [],
                'barangays' => [596]
            ],
            [
                'name' => 'Hunduan - Bokiawan',
                'members' => [],
                'barangays' => [595]
            ],
            [
                'name' => 'Kiangan - Mungayang',
                'members' => [],
                'barangays' => [608]
            ],
            [
                'name' => 'Kiangan - Hucab',
                'members' => [],
                'barangays' => [605]
            ],
            [
                'name' => 'Kiangan - Nagacadan',
                'members' => [],
                'barangays' => [609]
            ],
            [
                'name' => 'Lagawe - Buyabuyan',
                'members' => [],
                'barangays' => [618]
            ],
            [
                'name' => 'Lagawe - Jucbong',
                'members' => [],
                'barangays' => [622]
            ],
            [
                'name' => 'Lagawe - Montabiong',
                'members' => [],
                'barangays' => [624]
            ],
            [
                'name' => 'Lamut - Sanafe',
                'members' => [],
                'barangays' => [650]
            ],
            [
                'name' => 'Lamut - Nayon',
                'members' => [],
                'barangays' => [640]
            ],
            [
                'name' => 'Lamut - Hollowon',
                'members' => [],
                'barangays' => [648]
            ],
            [
                'name' => 'Mayoyao - Balangbang',
                'members' => [],
                'barangays' => [655]
            ],
            [
                'name' => 'Mayoyao - Chaya',
                'members' => [],
                'barangays' => [660]
            ],
            [
                'name' => 'Mayoyao - Tulaed',
                'members' => [],
                'barangays' => [676]
            ],
            [
                'name' => 'Tinoc - Wangwang',
                'members' => [],
                'barangays' => [738]
            ],
            [
                'name' => 'Tinoc - Binablayan',
                'members' => [],
                'barangays' => [729]
            ],
            [
                'name' => 'Tinoc - Ahin',
                'members' => [],
                'barangays' => [727]
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
