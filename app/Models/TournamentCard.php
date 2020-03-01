<?php

namespace App\Models;

use App\Http\Controllers\TournamentSubscriptionController;
use Illuminate\Database\Eloquent\Model;

class TournamentCard extends Model
{
    //
    public function carta(){
        if ($this->card==1) return 'Dez';
        if ($this->card==2) return 'Valete';
        if ($this->card==3) return 'Dama';
        if ($this->card==4) return 'Rei';
        if ($this->card==5) return 'Ás';
        if ($this->card==6) return 'Curinga';
        return '--';
    }

    public function saldo(){
        return $this->qtd - TournamentSubscription::wheretournament_card_id($this->id)->count() ;
    }
}
