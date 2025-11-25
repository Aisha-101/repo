<?php


namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin | user | guest
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    // JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
    * Add custom claims (we add the role here so it's inside the token)
    */
    public function getJWTCustomClaims()
    {
        return [
        'role' => $this->role,
        ];
    }
}