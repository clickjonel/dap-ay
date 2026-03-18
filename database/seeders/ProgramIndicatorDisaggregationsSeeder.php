<?php

namespace Database\Seeders;

use App\Models\ProgramIndicator;
use App\Models\ProgramIndicatorDisaggregation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramIndicatorDisaggregationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = ProgramIndicator::get();
        foreach ($indicators as $indicator) {
            ProgramIndicatorDisaggregation::create([
                'program_indicator_id' => $indicator->id,
                'disaggregation_id'    => 1,
            ]);

            ProgramIndicatorDisaggregation::create([
                'program_indicator_id' => $indicator->id,
                'disaggregation_id'    => 2,
            ]);

            ProgramIndicatorDisaggregation::create([
                'program_indicator_id' => $indicator->id,
                'disaggregation_id'    => 3,
            ]);
        }
    }
}
