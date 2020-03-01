<?php

namespace App\Models;

use App\Models\BlindStep;
use Illuminate\Database\Eloquent\Model;

class Blind extends Model
{
    //
    public function torneio(){
        if (!$this->tournament_id) return 'Não cadastrado';

        $cad = Tournament::whereid($this->tournament_id)->first();
        if (!$cad) return 'Não cadastrado';

        return '<a href="/club/torn/show/'.$cad->id.'" class="btn btn-sm btn-default p-5 mr-10" title="Ver torneio" target="_blank"> <i class="fa fa-eye"></i></a> '.$cad->name;
    }

    public function players(){
        return $this->hasMany(BlindPlayer::class,'blind_id','id');
    }

    public function rounds(){
        return $this->hasMany(BlindRound::class,'blind_id','id');
    }

    public function awards(){
        return $this->hasMany(BlindAward::class,'blind_id','id');
    }

    public function tournament(){
        return $this->hasOne(Tournament::class,'id','tournament_id');
    }
}
