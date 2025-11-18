<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'dap-ay';
    protected $table = 'users';
    protected $appends = ['full_name'];

    protected $fillable = [
        'user_code',
        'username',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'prefix',
        'nickname',
        'account_status',
        'user_level' 
    ];

    public function getFullNameAttribute():string
    {
        $middleInitial = '';
        if (!empty($this->middle_name)) {
            $middleInitial = $this->middle_name[0] . '.';
        }

        $fullName = trim($this->first_name . ' ' . $middleInitial . ' ' . $this->last_name);

        if (!empty($this->suffix)) {
            $fullName .= ', ' . $this->suffix;
        }

        if (!empty($this->prefix)) {
            $fullName = $this->prefix . ' ' . $fullName;
        }

        return $fullName;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')->withPivot('doh_deployed', 'name', 'position');
    }

    

}
