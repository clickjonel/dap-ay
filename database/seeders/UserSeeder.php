<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@dapay.is',
            'password' => 'admin12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 1,
        ]);


        //PDOHO - Abra
        $adminUser = User::create([
            'name' => 'PDOHO - Abra',
            'email' => 'abra@dapay.is',
            'password' => 'abra12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 1
        ]);

        //PDOHO - Benguet
        $adminUser = User::create([
            'name' => 'PDOHO - Benguet',
            'email' => 'benguet@dapay.is',
            'password' => 'benguet12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 2
        ]);

        //PDOHO - Ifugao
        $adminUser = User::create([
            'name' => 'PDOHO - Ifugao',
            'email' => 'ifugao@dapay.is',
            'password' => 'ifugao12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 3
        ]);

        //PDOHO - Kalinga
        $adminUser = User::create([
            'name' => 'PDOHO - Kalinga',
            'email' => 'kalinga@dapay.is',
            'password' => 'kalinga12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 4
        ]);

        //PDOHO - Mountain Province
        $adminUser = User::create([
            'name' => 'PDOHO - Mountain Province',
            'email' => 'mp@dapay.is',
            'password' => 'mp12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 5
        ]);

        //PDOHO - Apayao
        $adminUser = User::create([
            'name' => 'PDOHO - Apayao',
            'email' => 'apayao@dapay.is',
            'password' => 'apayao12345',
        ]);
        $adminUser->accessLevels()->create([
            'access_level' => 3,
            'pdoho_access_id' => 6
        ]);

    }
}
