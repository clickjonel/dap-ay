<?php

namespace Database\Seeders;

use App\Models\OrganizationalIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationalIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgIndicators = [
            'Total No. of PK Sites Nationwide',
            'Proportion of PK Sites with Active PK Teams',
            'Percentage of PK Teams with Complete Kits',
            'Percentage of PK Teams Oriented/Trained on PK',
            'Total No. of Clients provided with Health Promotion',
            'Total No. of Clients provided with Health Services',
            'Total No. of First Patient Encounters (FPEs)',
            'Total No. of Referrals Made to Higher Facilities',
            'Total No. of PhilHealth Registrations',
            'Total No. of Large-scale activities conducted',
            'Total No. of PK activities conducted',

        ];

        foreach ($orgIndicators as $indicator) {
            OrganizationalIndicator::create([
                'indicator_name' => $indicator,
                'is_active' => true,
            ]);
        }
    }
}
