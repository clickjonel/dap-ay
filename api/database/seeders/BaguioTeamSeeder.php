<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamBarangay;
use App\Models\TeamMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaguioTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                "name" => "Asin",
                "barangays" => [313,386,385],
                "members" => []
            ],
            [
                "name" => "Atab",
                "barangays" => [315,316,436],
                "members" => []
            ],
            [
                "name" => "Atok Trail",
                "barangays" => [351],
                "members" => []
            ],
            [
                "name" => "Aurora Hill",
                "barangays" => [413,320,319,370,372],
                "members" => []
            ],
            [
                "name" => "Campo Filipino",
                "barangays" => [324,429,344],
                "members" => []
            ],
            [
                "name" => "City Camp",
                "barangays" => [318,381,416],
                "members" => []
            ],
            [
                "name" => "Engineer's Hill",
                "barangays" => [387,367,379],
                "members" => []
            ],
            [
                "name" => "Irisan",
                "barangays" => [349],
                "members" => []
            ],
            [
                "name" => "Loakan",
                "barangays" => [325,426,353],
                "members" => []
            ],
            [
                "name" => "Lucban",
                "barangays" => [346,373,420],
                "members" => []
            ],
            [
                "name" => "Mines View",
                "barangays" => [341,361,374],
                "members" => []
            ],
            [
                "name" => "Pacdal",
                "barangays" => [375,396,330],
                "members" => []
            ],
            [
                "name" => "Pinsao",
                "barangays" => [378],
                "members" => []
            ],
            [
                "name" => "Quezon Hill",
                "barangays" => [337,434,400],
                "members" => []
            ],
            [
                "name" => "Quirino Hill",
                "barangays" => [335,366,334],
                "members" => []
            ],
            [
                "name" => "Scout Bario",
                "barangays" => [317,425],
                "members" => []
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
