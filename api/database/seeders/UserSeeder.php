<?php

namespace Database\Seeders;

use App\Models\Indicator;
use App\Models\User;
use App\Models\UserIndicator;
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
        $users = DB::connection('dohis')->table('dohis_hrh_profile')->where('account_status','Active')->get();
        // hrh accounts
        foreach($users as $user){
            $user = User::create([
                'user_code' => $user->user_code,
                'username' => $user->nickname,
                'password' => password_hash('12345',PASSWORD_BCRYPT),
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'suffix' => null,
                'prefix' => null,
                'nickname' => $user->nickname,
                'account_status' => 'Active',
                'user_level' => 4
            ]);
        }

        // admin account
        User::create([
            'user_code' => 'Admin',
            'username' => 'admin',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'admin',
            'middle_name' => 'admin',
            'last_name' => 'admin',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'admin',
            'account_status' => 'Active',
            'user_level' => 1
        ]);

        // secretariat account
        User::create([
            'user_code' => 'Secretariat',
            'username' => 'secretariat',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'secretariat',
            'middle_name' => 'secretariat',
            'last_name' => 'secretariat',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'secretariat',
            'account_status' => 'Active',
            'user_level' => 2
        ]);

        // pdoho account
        User::create([
            'user_code' => 'pdoho',
            'username' => 'pdoho',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho',
            'middle_name' => 'pdoho',
            'last_name' => 'pdoho',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho',
            'account_status' => 'Active',
            'user_level' => 3
        ]);
    }
}
