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
                'user_level' => 5
            ]);
        }
    }
}
