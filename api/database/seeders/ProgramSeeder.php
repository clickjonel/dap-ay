<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramIndicator;
use App\Models\SubProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PDO;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $programs = [
            [
                'program_name' => 'Nutrition Program',
                'program_code' => 'NP',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Immunization Program',
                'program_code' => 'IP',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Water, Sanitation and Hygiene (WASH) Program',
                'program_code' => 'WASH',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Tuberculosis (TB) Program',
                'program_code' => 'TB',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'HIV and AIDS  Program',
                'program_code' => 'HIV',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Road Safety Program',
                'program_code' => 'RSP',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Maternal Health Program',
                'program_code' => 'MHP',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_name' => 'Non-Communicable Diseases Program',
                'program_code' => 'NCD',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'sub_program_name' => 'NCD-Cancer',
                        'sub_program_code' => 'NCD-SUB-CANCER',
                        'sub_program_active' => true,
                    ],
                    [
                        'sub_program_name' => 'NCD-Women',
                        'sub_program_code' => 'NCD-SUB-WOMEN',
                        'sub_program_active' => true,
                    ],
                ]
            ],
            [
                'program_name' => 'Mental Health',
                'program_code' => 'MH',
                'program_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach($programs as $program){
            $programCreated = Program::create([
                'program_name' => $program['program_name'],
                'program_code' => $program['program_code'],
                'program_active' => $program['program_active'],
                'created_at' => $program['created_at'],
                'updated_at' => $program['updated_at'],
            ]);

            $subPrograms = $program['sub_programs'] ?? [];

            if(empty($subPrograms)){
                $subProgramCreated = SubProgram::create([
                    'program_id' => $programCreated->program_id,
                    'sub_program_name' => $program['program_name'],
                    'sub_program_code' => $program['program_code'],
                    'sub_program_active' => $program['program_active'],
                ]);

                $indicators = [
                    'Total Provided Health Promotion Services',
                    'Total Individuals Reached Through Health Promotion',
                    "Total Individuals Provided with PK Services ({$subProgramCreated->sub_program_name})",
                    "Total Households Provided with PK Services ({$subProgramCreated->sub_program_name})",
                    "Total Individual FPE",
                    "Total Household FPE",
                    "Total Individuals Reffered to Higher Health Facility",
                ];

                foreach($indicators as $index => $indicatorName) {
                    $indicatorCode = sprintf(
                        "%s-%s-IND-%03d",
                        $program['program_code'],
                        $subProgramCreated->sub_program_code,
                        $index + 1
                    );
                    
                    ProgramIndicator::create([
                        'indicator_name' => $indicatorName,
                        'indicator_code' => $indicatorCode,
                        'sub_program_id' => $subProgramCreated->sub_program_id,
                        'indicator_active' => true
                    ]);
                }
            }
    
            foreach($subPrograms as $subProgramData){
                $subProgramCreated = SubProgram::create([
                    'program_id' => $programCreated->program_id,
                    'sub_program_name' => $subProgramData['sub_program_name'],
                    'sub_program_code' => $subProgramData['sub_program_code'],
                    'sub_program_active' => $subProgramData['sub_program_active'],
                ]);

                $indicators = [
                    'Total Provided Health Promotion Services',
                    'Total Individuals Reached Through Health Promotion',
                    "Total Individuals Provided with PK Services ({$subProgramCreated->sub_program_name})",
                    "Total Households Provided with PK Services ({$subProgramCreated->sub_program_name})",
                    "Total Individual FPE",
                    "Total Household FPE",
                    "Total Individuals Reffered to Higher Health Facility",
                ];

                foreach($indicators as $index => $indicatorName) {
                    $indicatorCode = sprintf(
                        "%s-%s-IND-%03d",
                        $program['program_code'],
                        $subProgramData['sub_program_code'],
                        $index + 1
                    );
                    
                    ProgramIndicator::create([
                        'indicator_name' => $indicatorName,
                        'indicator_code' => $indicatorCode,
                        'sub_program_id' => $subProgramCreated->sub_program_id,
                        'indicator_active' => true
                    ]);
                }
            }
        }
    }
}