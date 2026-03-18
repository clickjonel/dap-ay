<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Nutrition',
            'Health Promotion',
            'Non-Communicable Diseases',
            'Mental Health',
            'Maternal Health',
            'Road Safety',
            'HIV/AIDS',
            'Tuberculosis',
            'Water, Sanitation, and Hygiene (WASH)',
            'Immunization',
        ];

        foreach ($groups as $group) {
            ProgramGroup::create([
                'name' => $group,
            ]);
        }

        $programs = [
            [
                'name' => 'Nutrition Program',
                'parent_program_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Health Promotion Program',
                'parent_program_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Breast Cancer Program',
                'parent_program_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Cervical Cancer Program',
                'parent_program_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Diabetes Program',
                'parent_program_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Hypertension Program',
                'parent_program_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Maternal Health Program',
                'parent_program_id' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Mental Health Program',
                'parent_program_id' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Road Safety Program',
                'parent_program_id' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'HIV/AIDS Program',
                'parent_program_id' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Tuberculosis Program',
                'parent_program_id' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Water, Sanitation, and Hygiene (WASH) Program',
                'parent_program_id' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Immunization Program',
                'parent_program_id' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
