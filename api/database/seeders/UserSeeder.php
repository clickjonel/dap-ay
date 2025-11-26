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

        // pdoho-abra account
        User::create([
            'user_code' => 'pdoho-abra',
            'username' => 'pdoho-abra',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-abra',
            'middle_name' => 'pdoho-abra',
            'last_name' => 'pdoho-abra',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-abra',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 1
        ]);

         // pdoho-benguet account
        User::create([
            'user_code' => 'pdoho-benguet',
            'username' => 'pdoho-benguet',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-benguet',
            'middle_name' => 'pdoho-benguet',
            'last_name' => 'pdoho-benguet',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-benguet',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 2
        ]);

         // pdoho-ifugao account
        User::create([
            'user_code' => 'pdoho-ifugao',
            'username' => 'pdoho-ifugao',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-ifugao',
            'middle_name' => 'pdoho-ifugao',
            'last_name' => 'pdoho-ifugao',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-ifugao',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 3
        ]);

         // pdoho-kalinga account
        User::create([
            'user_code' => 'pdoho-kalinga',
            'username' => 'pdoho-kalinga',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-kalinga',
            'middle_name' => 'pdoho-kalinga',
            'last_name' => 'pdoho-kalinga',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-kalinga',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 4
        ]);

         // pdoho-mp account
        User::create([
            'user_code' => 'pdoho-mp',
            'username' => 'pdoho-mp',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-mp',
            'middle_name' => 'pdoho-mp',
            'last_name' => 'pdoho-mp',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-mp',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 5
        ]);

         // pdoho-apayao account
        User::create([
            'user_code' => 'pdoho-apayao',
            'username' => 'pdoho-apayao',
            'password' => password_hash('12345',PASSWORD_BCRYPT),
            'first_name' => 'pdoho-apayao',
            'middle_name' => 'pdoho-apayao',
            'last_name' => 'pdoho-apayao',
            'suffix' => null,
            'prefix' => null,
            'nickname' => 'pdoho-apayao',
            'account_status' => 'Active',
            'user_level' => 3,
            'pdoho_province_id' => 6
        ]);
    }
}
