<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentSubscription extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
    public function user(){
        return $this->hasOne(UserApp::class,'id','user_app_id');
    }

    public function card(){
        return $this->hasOne(TournamentCard::class,'id','tournament_card_id');
    }
}
