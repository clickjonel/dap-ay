<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApayaoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::connection('dohis')
            ->table('dohis_hrh_user as u')
            ->join('dohis_hrh_user_assignment as ua', 'ua.hrh_user_id', '=', 'u.hrh_user_id')
            ->where('ua.section_id', 5)
            ->select(
                'u.email_address as email',
                DB::raw("CONCAT(u.first_name, ' ', LEFT(u.middle_name,1), '. ', u.last_name) as full_name")
            )
            ->get();

        foreach ($users as $user) {
            $createdUser = User::firstOrCreate(
                ['email' => $user->email], // check duplicate
                [
                    'name' => $user->full_name,
                    'password' => '12345',
                ]
            );

            if ($createdUser->wasRecentlyCreated) {
                $createdUser->accessLevels()->create([
                    'pdoho_access_id' => 6,
                    'access_level' => 2,
                ]);
            }
        }
    }
}
