<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\BarangayPriorityProgram;
use App\Models\Team;
use App\Models\TeamBarangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbraTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Bangued - Maoay',
                'members' => [],
                'barangays' => [15]
            ],
            [
                'name' => 'Bangued - Sagap',
                'members' => [],
                'barangays' => [20]
            ],
            [
                'name' => 'Bangued - Macray',
                'members' => [],
                'barangays' => [16]
            ],
            [
                'name' => 'Boliney - Amti',
                'members' => [],
                'barangays' => [32]
            ],
            [
                'name' => 'Boliney - Kilong - Olao',
                'members' => [],
                'barangays' => [37]
            ],
            [
                'name' => 'Boliney - Dumagas',
                'members' => [],
                'barangays' => [36]
            ],
            [
                'name' => 'Bucay - Abang',
                'members' => [],
                'barangays' => [40]
            ],
            [
                'name' => 'Bucay - Dugong',
                'members' => [],
                'barangays' => [46]
            ],
            [
                'name' => 'Bucay - Pagala',
                'members' => [],
                'barangays' => [50]
            ],
            [
                'name' => 'Bucloc - Lingey',
                'members' => [],
                'barangays' => [63]
            ],
            [
                'name' => 'Bucloc - Labaan',
                'members' => [],
                'barangays' => [62]
            ],
            [
                'name' => 'Bucloc - Ducligan',
                'members' => [],
                'barangays' => [61]
            ],
            [
                'name' => 'Daguioman - Ableg',
                'members' => [],
                'barangays' => [65]
            ],
            [
                'name' => 'Daguioman - Cabaruyan',
                'members' => [],
                'barangays' => [66]
            ],
            [
                'name' => 'Daguioman - Pikek',
                'members' => [],
                'barangays' => [67]
            ],
            [
                'name' => 'Danglas - Caupasan',
                'members' => [],
                'barangays' => [71]
            ],
            [
                'name' => 'Danglas - Pangal',
                'members' => [],
                'barangays' => [75]
            ],
            [
                'name' => 'Danglas - Nagaparan',
                'members' => [],
                'barangays' => [73]
            ],
            [
                'name' => 'Dolores - Cardona',
                'members' => [],
                'barangays' => [79]
            ],
            [
                'name' => 'Dolores - Mudiit',
                'members' => [],
                'barangays' => [84]
            ],
            [
                'name' => 'Dolores - Libtec',
                'members' => [],
                'barangays' => [82]
            ],
            [
                'name' => 'La Paz - Mudeng',
                'members' => [],
                'barangays' => [97]
            ],
            [
                'name' => 'La Paz - Poblacion',
                'members' => [],
                'barangays' => [99]
            ],
            [
                'name' => 'La Paz - Malabbaga',
                'members' => [],
                'barangays' => [96]
            ],
            [
                'name' => 'Lacub - Buneg',
                'members' => [],
                'barangays' => [104]
            ],
            [
                'name' => 'Lacub - Lan-ag',
                'members' => [],
                'barangays' => [106]
            ],
            [
                'name' => 'Lacub - Bacag',
                'members' => [],
                'barangays' => [103]
            ],
            [
                'name' => 'Lagangilang - Paganao',
                'members' => [],
                'barangays' => [119]
            ],
            [
                'name' => 'Lagangilang - Cayapa',
                'members' => [],
                'barangays' => [112]
            ],
            [
                'name' => 'Lagangilang - San Isidro',
                'members' => [],
                'barangays' => [123]
            ],
            [
                'name' => 'Lagayan - Ba-i',
                'members' => [],
                'barangays' => [126]
            ],
            [
                'name' => 'Lagayan - Collago',
                'members' => [],
                'barangays' => [127]
            ],
            [
                'name' => 'Lagayan - Pang-ot',
                'members' => [],
                'barangays' => [128]
            ],
            [
                'name' => 'Langiden - Poblacion',
                'members' => [],
                'barangays' => [135]
            ],
            [
                'name' => 'Langiden - Mabungtot',
                'members' => [],
                'barangays' => [133]
            ],
            [
                'name' => 'Langiden - Malapaao',
                'members' => [],
                'barangays' => []
            ],
            [
                'name' => 'Licuan-Baay - Mapisla',
                'members' => [],
                'barangays' => [142]
            ],
            [
                'name' => 'Licuan-Baay - Bulbulala',
                'members' => [],
                'barangays' => [138]
            ],
            [
                'name' => 'Licuan-Baay - Nalbuan',
                'members' => [],
                'barangays' => [144]
            ],
            [
                'name' => 'Luba - Barit',
                'members' => [],
                'barangays' => [149]
            ],
            [
                'name' => 'Luba - Ampalioc',
                'members' => [],
                'barangays' => [148]
            ],
            [
                'name' => 'Luba - Gayaman',
                'members' => [],
                'barangays' => [150]
            ],
            [
                'name' => 'Malibcong - Mataragan',
                'members' => [],
                'barangays' => [164]
            ],
            [
                'name' => 'Malibcong - Binasaran',
                'members' => [],
                'barangays' => [157]
            ],
            [
                'name' => 'Malibcong - Lat-ey',
                'members' => [],
                'barangays' => [162]
            ],
            [
                'name' => 'Manabo - Catacdegan Nuevo',
                'members' => [],
                'barangays' => [178]
            ],
            [
                'name' => 'Manabo - Ayyeng',
                'members' => [],
                'barangays' => [170]
            ],
            [
                'name' => 'Manabo - San Ramon East',
                'members' => [],
                'barangays' => [175]
            ],
            [
                'name' => 'Peñarrubia - Lusuac',
                'members' => [],
                'barangays' => [180]
            ],
            [
                'name' => 'Peñarrubia - Santa Rosa',
                'members' => [],
                'barangays' => [186]
            ],
            [
                'name' => 'Peñarrubia - Malamsit',
                'members' => [],
                'barangays' => [183]
            ],
            [
                'name' => 'Pidigan - Alinaya',
                'members' => [],
                'barangays' => [188]
            ],
            [
                'name' => 'Pidigan - Arab',
                'members' => [],
                'barangays' => [189]
            ],
            [
                'name' => 'Pidigan - Poblacion East',
                'members' => [],
                'barangays' => [197]
            ],
            [
                'name' => 'Pilar - Gapang',
                'members' => [],
                'barangays' => [208]
            ],
            [
                'name' => 'Pilar - Daliit',
                'members' => [],
                'barangays' => [206]
            ],
            [
                'name' => 'Pilar - Bolbolo',
                'members' => [],
                'barangays' => [203]
            ],
            [
                'name' => 'Sallapadan - Bazar',
                'members' => [],
                'barangays' => [222]
            ],
            [
                'name' => 'Sallapadan - Ud-udiao',
                'members' => [],
                'barangays' => [230]
            ],
            [
                'name' => 'Sallapadan - Maguyepyep',
                'members' => [],
                'barangays' => [225]
            ],
            [
                'name' => 'San Isidro - San Marcial',
                'members' => [],
                'barangays' => [238]
            ],
            [
                'name' => 'San Isidro - Manayday',
                'members' => [],
                'barangays' => [234]
            ],
            [
                'name' => 'San Isidro - Dalimag',
                'members' => [],
                'barangays' => [232]
            ],
            [
                'name' => 'San Juan - Ba-ug',
                'members' => [],
                'barangays' => [241]
            ],
            [
                'name' => 'San Juan - Supi-il',
                'members' => [],
                'barangays' => [257]
            ],
            [
                'name' => 'San Juan - Quidoen',
                'members' => [],
                'barangays' => [254]
            ],
            [
                'name' => 'San Quintin - Palang',
                'members' => [],
                'barangays' => [260]
            ],
            [
                'name' => 'San Quintin - Pantoc',
                'members' => [],
                'barangays' => [261]
            ],
            [
                'name' => 'San Quintin - Poblacion',
                'members' => [],
                'barangays' => [262]
            ],
            [
                'name' => 'Tayum - Basbasa',
                'members' => [],
                'barangays' => [266]
            ],
            [
                'name' => 'Tayum - Patucannay',
                'members' => [],
                'barangays' => [272]
            ],
            [
                'name' => 'Tayum - Gaddani',
                'members' => [],
                'barangays' => [271]
            ],
            [
                'name' => 'Tineg - Alaoa',
                'members' => [],
                'barangays' => [277]
            ],
            [
                'name' => 'Tineg - Apao',
                'members' => [],
                'barangays' => [279]
            ],
            [
                'name' => 'Tineg - Lapat-Balantay',
                'members' => [],
                'barangays' => [284]
            ],
            [
                'name' => 'Tubo - Amtuagan',
                'members' => [],
                'barangays' => [287]
            ],
            [
                'name' => 'Tubo - Tabacda',
                'members' => [],
                'barangays' => [295]
            ],
            [
                'name' => 'Tubo - Alangtin',
                'members' => [],
                'barangays' => [286]
            ],
            [
                'name' => 'Villaviciosa - Bol-lilising',
                'members' => [],
                'barangays' => [297]
            ],
            [
                'name' => 'Villaviciosa - Tamac',
                'members' => [],
                'barangays' => [302]
            ],
            [
                'name' => 'Villaviciosa - Poblacion',
                'members' => [],
                'barangays' => [301]
            ],
        ];

        foreach($teams as $team){

            $teamCreated = Team::create(['name' => $team['name']]);

            foreach($team['barangays'] as $brgy){

                Barangay::find($brgy)->update(['pk_status' => 'Monitoring PK Implementation']);
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
