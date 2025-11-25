<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KalingaTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Balbalan - Talalang',
                'members' => [],
                'barangays' => [760]
            ],
            [
                'name' => 'Balbalan - Tawang',
                'members' => [],
                'barangays' => [761]
            ],
            [
                'name' => 'Balbalan - Buaya',
                'members' => [],
                'barangays' => [752]
            ],
            [
                'name' => 'Lubuagan - Mabongtot',
                'members' => [],
                'barangays' => [764]
            ],
            [
                'name' => 'Lubuagan - Antonio Canao',
                'members' => [],
                'barangays' => [769]
            ],
            [
                'name' => 'Lubuagan - Tanglag',
                'members' => [],
                'barangays' => [766]
            ],
            [
                'name' => 'Pasil - Balinciagao Norte',
                'members' => [],
                'barangays' => [773]
            ],
            [
                'name' => 'Pasil - Bagtayan',
                'members' => [],
                'barangays' => [784]
            ],
            [
                'name' => 'Pasil - Colayo',
                'members' => [],
                'barangays' => [775]
            ],
            [
                'name' => 'Pinukpuk - Aciga',
                'members' => [],
                'barangays' => [785]
            ],
            [
                'name' => 'Pinukpuk - Malagnat',
                'members' => [],
                'barangays' => [800]
            ],
            [
                'name' => 'Pinukpuk - Pakawit',
                'members' => [],
                'barangays' => [802]
            ],
            [
                'name' => 'Rizal - Kinama',
                'members' => [],
                'barangays' => [810]
            ],
            [
                'name' => 'Rizal - San Francisco',
                'members' => [],
                'barangays' => [820]
            ],
            [
                'name' => 'Rizal - San Pedro',
                'members' => [],
                'barangays' => [821]
            ],
            [
                'name' => 'Tabuk - Laya West',
                'members' => [],
                'barangays' => [840]
            ],
            [
                'name' => 'Tabuk - Dilag',
                'members' => [],
                'barangays' => [834]
            ],
            [
                'name' => 'Tabuk - Magnao',
                'members' => [],
                'barangays' => [842]
            ],
            [
                'name' => 'Tabuk - Nambucayan',
                'members' => [],
                'barangays' => [847]
            ],
            [
                'name' => 'Tabuk - Gobgob',
                'members' => [],
                'barangays' => [836]
            ],
            [
                'name' => 'Tabuk - Bantay',
                'members' => [],
                'barangays' => [828]
            ],
            [
                'name' => 'Tabuk - Balawag',
                'members' => [],
                'barangays' => [826]
            ],
            [
                'name' => 'Tabuk - Agbannawag',
                'members' => [],
                'barangays' => [822]
            ],
            [
                'name' => 'Tabuk - Lacnog East',
                'members' => [],
                'barangays' => [858]
            ],
            [
                'name' => 'Tanudan - Babbanoy',
                'members' => [],
                'barangays' => [865]
            ],
            [
                'name' => 'Tanudan - Dacalan',
                'members' => [],
                'barangays' => [866]
            ],
            [
                'name' => 'Tanudan - Lower Mangali',
                'members' => [],
                'barangays' => [868]
            ],
            [
                'name' => 'Tinglayan - Sumadel 1',
                'members' => [],
                'barangays' => [892]
            ],
            [
                'name' => 'Tinglayan - Loccong',
                'members' => [],
                'barangays' => [888]
            ],
            [
                'name' => 'Tinglayan - Basao',
                'members' => [],
                'barangays' => [882]
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
