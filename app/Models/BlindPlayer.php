<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlindPlayer extends Model
{
    //
    public function photo(){
        $cad = $this;

        /*** Caso de consulta sql que não venha todos os campos ***/
        if (!$cad->blind_id)
            $cad = BlindPlayer::find($this->id);

        if (!$cad->photo_ext) {
            if ($cad->user_app_id > 0) { // && (($cad->user_type==1)||($cad->user_type==2))) {
                $cadapp = UserApp::where('id', '=', $cad->user_app_id)->first();
                if ($cadapp->photo) {
                    $arquser = $cad->user_app_id.'.'.$cadapp->photo;
                    if (Storage::disk('users')->exists($arquser))
                        return asset('storage/users/'.$arquser);
                }
                if ($cadapp->face_id) {
                    return asset('https://graph.facebook.com/' . $cadapp->face_id . '/picture?type=large');
                }
            }
        }

        /** Se nao tem foto sai */
        if (!$cad->photo_ext)
            return asset('my/images/sem_avatar.png');

        /** monta a url do arquivo ****/
        $arq = $cad->blind_id.'/'.$cad->id.'.'.$cad->photo_ext;

        //procurar no storage
        if (Storage::disk('blinds')->exists($arq))
            return asset('storage/blinds/'.$arq);
        else
            return asset('my/images/sem_avatar.png');


    }

}
