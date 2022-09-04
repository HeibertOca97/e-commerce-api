<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'isAdmin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createAndReturnToken($user){
        $currentDate = date('Y-m-d H:i:s');
        $new_timer = strtotime('+'. config('sanctum.expiration') .'minute', strtotime($currentDate));
        $date =  date('Y-m-d H:i:s', $new_timer);
        $token = $user->createToken(strtotime($currentDate))->plainTextToken;
        return [
            'token' => $token,
            'expiration' => $date
        ];
    }

    public function cart(){
        return $this->hasOne('App/Models/Cart');
    }

    public function orders(){
        return $this->hasMany('App/Models/Order');
    }

}
