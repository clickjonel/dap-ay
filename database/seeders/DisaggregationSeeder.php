<?php

namespace Database\Seeders;

use App\Models\Disaggregation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisaggregationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disaggregations = [
            [
                'disaggregation_name' => 'Male',
                'is_active' => true,
                'group' => 'Sex'
            ],
            [
                'disaggregation_name' => 'Female',
                'is_active' => true,
                'group' => 'Sex'
            ],
            [
                'disaggregation_name' => '4Ps Beneficiary',
                'is_active' => true,
                'group' => 'DSWD'
            ],
        ];

        foreach ($disaggregations as $disaggregation) {
            Disaggregation::create($disaggregation);
        }
    }
}
