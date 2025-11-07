<?php

namespace Database\Seeders;

use App\Models\Indicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            [
                'name' => 'Total number of household provided with WASH PK services',
                'type' => 'Target'
            ],
            [
                'name' => 'Total number of household provided with PK services',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for Nutrition',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for Immunization',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for Maternal Health',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for TB',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for  HIV',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for Road Safety',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for NCD(Diabetes)',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for NCD(Hypertension)',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for NCD(Breast Cancer)',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of clients reached for NCD(Cervical Cancer)',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of individuals reached through health promotion',
                'type' => 'Priority'
            ],
            [
                'name' => 'Number of individuals provided with first patient encounter',
                'type' => 'Priority'
            ],
            [
                'name' => 'Number of individuals registered to philhealth',
                'type' => 'Priority'
            ],
            [
                'name' => 'Number of individuals provided with advocacies on First Patient Encounters or Philhealth',
                'type' => 'Target'
            ],
            [
                'name' => 'Number of individuals reffered to higher health facility',
                'type' => 'Priority'
            ],
            [
                'name' => 'Number of Large Scale Activities Conducted',
                'type' => 'Priority'
            ],

        ];

        foreach($indicators as $ind){
            Indicator::create($ind);
        }
    }
}
