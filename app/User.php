<?php

namespace App;

use App\Models\Administrator;
use App\Models\Club;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function nome1(){
        return So1Nome($this->name);
    }

    public function club(){
        return $this->hasOne(Club::class,'id','club_id');
    }
}
