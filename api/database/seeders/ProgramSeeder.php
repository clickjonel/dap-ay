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
                'name' => 'Nutrition Program',
                'code' => 'NP',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Nutrition Program',
                        'code' => 'NP',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(), 
                    ]
                ]
            ],
            [
                'name' => 'Immunization Program',
                'code' => 'IP',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Immunization Program',
                        'code' => 'IP',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Water, Sanitation and Hygiene (WASH) Program',
                'code' => 'WASH',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Water, Sanitation and Hygiene (WASH) Program',
                        'code' => 'WASH',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Tuberculosis (TB) Program',
                'code' => 'TB',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Tuberculosis (TB) Program',
                        'code' => 'TB',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'HIV and AIDS  Program',
                'code' => 'HIV',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'HIV and AIDS  Program',
                        'code' => 'HIV',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Road Safety Program',
                'code' => 'RSP',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Road Safety Program',
                        'code' => 'RSP',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Maternal Health Program',
                'code' => 'MHP',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Maternal Health Program',
                        'code' => 'MHP',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Non-Communicable Diseases Program',
                'code' => 'NCD',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Diabetes',
                        'code' => 'NCD-Diabetes',
                        'active' => true,
                    ],
                    [
                        'name' => 'Hypertension',
                        'code' => 'NCD-Hypertension',
                        'active' => true,
                    ],
                    [
                        'name' => 'Breast Cancer',
                        'code' => 'NCD-Breast Cancer',
                        'active' => true,
                    ],
                    [
                        'name' => 'Cervical Cancer',
                        'code' => 'NCD-Cervical Cancer',
                        'active' => true,
                    ],
                ]
            ],
            [
                'name' => 'Mental Health',
                'code' => 'MH',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Mental Health',
                        'code' => 'MH',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'name' => 'Health Promotion Program',
                'code' => 'HPP',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'sub_programs' => [
                    [
                        'name' => 'Health Promotion Program',
                        'code' => 'HPP',
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
        ];

        foreach ($programs as $programData) {
            // Store sub_programs before creating the program
            $subPrograms = $programData['sub_programs'];
            
            // Create the program
            $program = Program::create([
                'name' => $programData['name'],
                'code' => $programData['code'],
                'active' => $programData['active']
            ]);

            // Create the sub-programs
            $program->subPrograms()->createMany($subPrograms);
        }

    }

    
}