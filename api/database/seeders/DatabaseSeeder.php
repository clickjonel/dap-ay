<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProgramSeeder::class,
            ProvinceSeeder::class,

            BaguioTeamSeeder::class,
            BenguetTeamSeeder::class,
            AbraTeamSeeder::class,
            ApayaoTeamSeeder::class,
            IfugaoTeamSeeder::class,
            KalingaTeamSeeder::class,
            MountainProvinceTeamSeeder::class,

            BaguioBarangayProfilesSeeder::class,
            //BaguioInitialReportsSeeder::class,

            //AbraInitialReportsSeeder::class,
            GIDASeeder::class,
            IndicatorSeeder::class,
        ]);
    }
}
