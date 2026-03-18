<?php

namespace Database\Seeders;

use App\Models\ProgramIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            [
                'indicator_name' => 'Number of children aged 12-59 months who completed 2 doses of Vitamin A Supplementation', 
                'program' => 1,
            ],
            [
                'indicator_name' => 'Number of children vaccinated with MMR2 antigen through PK activities', 
                'program' => 13
            ],
            [
                'indicator_name' => 'Number of pregnant women aged 10-49 years old with high BP or danger signs who were referred to a higher level facility', 
                'program' => 7
            ],
            [
                'indicator_name' => 'Number of households with access to basic safe water supply', 
                'program' => 12
            ],
            [
                'indicator_name' => 'Number of registered DSTB all forms that are cured and completely treated', 
                'program' => 11
            ],
            [
                'indicator_name' => 'Number of pregnant women screened for HIV', 
                'program' => 10
            ],
            [
                'indicator_name' => 'Number of individuals involved in road crash accidents that were provided with first aid', 
                'program' => 9
            ],
            [
                'indicator_name' => 'Number of adults aged 20-59 y/o and elderlies aged 60 y/o and above identified as hypertensive with complete antidiabetic medication', 
                'program' => 6
            ],
            [
                'indicator_name' => 'Number of adults aged 20-59 y/o and elderlies aged 60 y/o and above identified with Type 2 DM and with complete antidiabetic medication', 
                'program' => 5
            ],
            [
                'indicator_name' => 'Number of women aged 30-65 years old screened or assessed for VIA or Pap Smear or HPV DNA', 
                'program' => 10
            ],
            [
                'indicator_name' => 'Number of individuals with mental health concern assessed using mhGAP', 
                'program' => 8
            ],
        ];

        foreach ($indicators as $indicator) {
            ProgramIndicator::create([
                'indicator_name' => $indicator['indicator_name'],
                'is_active' => true,
                'program_id' => $indicator['program']
            ]);
        }
    }
}
