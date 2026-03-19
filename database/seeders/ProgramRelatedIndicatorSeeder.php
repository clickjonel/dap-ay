<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramRelatedIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = Program::get();

        foreach ($programs as $program) {
            ProgramIndicator::create([
                'indicator_name' => 'Number of clients who received services related to' . $program->name,
                'is_active' => true,
                'program_id' => $program->id
            ]);
        }
    }
}
