<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RankingPlayer extends Model
{
    //
    public function photo(){
        $cad = $this;

        /*** Caso de consulta sql que não venha todos os campos ***/
        if (!$cad->ranking_id)
            $cad = RankingPlayer::find($this->id);

        /** Se nao tem foto sai */
        if (!$cad->photo_ext)
            return asset('my/images/sem_avatar.png');

        /** monta a url do arquivo ****/
        $arq = $cad->ranking_id.'/'.$cad->id.'.'.$cad->photo_ext;

        //procurar no storage
        if (Storage::disk('rankings')->exists($arq))
            return asset('storage/rankings/'.$arq);
        else
            return asset('my/images/sem_avatar.png');


    }

    public function Point($etapa){
        $cad = RankingPoint::whereplayer_id($this->id)
            ->wherestep($etapa)
            ->first();

        if (!$cad)
            return 0;
        else
            return $cad->point;
    }

    public function total(){
        return RankingPoint::whereplayer_id($this->id)->sum('point');
    }
}
