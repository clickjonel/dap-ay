<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Keziah Joy Gannaban Lagundino',
                'email' => 'lagundinokeziahjoy.doh@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Rucel Diwayan Yadan',
                'email' => '09469996916rucel@gmail.com',
                'password' => '12345',
            ],
                [
                'name' => 'Jessica Comot Alawas',
                'email' => 'jalawas123@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Kiana Dinamling Guyguyon',
                'email' => 'khiaguyguyon@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Hio Sufini Umingli Kinnud',
                'email' => 'hiosufinie1995@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Violy Ahorte Baguan',
                'email' => 'violyahorte@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Helen Genevieve Tundagui Daguio',
                'email' => 'Helendee540@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Raiza Allaga',
                'email' => 'allagaraizajoy@gmail.com',
                'password' => '12345',
            ],
             [
                'name' => 'Jennifer Otgalon',
                'email' => 'jennots26@gmail.com',
                'password' => '12345',
            ],
        ];

        foreach ($users as $user) {
            $createdUser = User::firstOrCreate(
                ['email' => $user['email']], // check duplicate
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                ]
            );

            if ($createdUser->wasRecentlyCreated) {
                $createdUser->accessLevels()->create([
                    'pdoho_access_id' => 3,
                    'access_level' => 2,
                ]);
            }
        }
    }
}
