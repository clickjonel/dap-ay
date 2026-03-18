<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            ProvinceSeeder::class,
            ProgramSeeder::class,
            OrganizationalIndicatorSeeder::class,
            ProgramIndicatorSeeder::class,
            DisaggregationSeeder::class,
            ProgramIndicatorDisaggregationsSeeder::class,
            UserSeeder::class,
            AbraUserSeeder::class,
            BenguetUserSeeder::class,
            IfugaoUserSeeder::class,
        ]);
        
    }
}
