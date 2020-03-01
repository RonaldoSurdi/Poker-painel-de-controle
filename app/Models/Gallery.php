<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    public function photos(){
        return $this->hasMany(GalleryPhoto::class,'gallery_id','id');
    }

    public function club(){
        return $this->hasOne(Club::class,'id','club_id');
    }


    //
    public function qtd_photos(){
        if ($this->photos)
            return $this->photos->count();
        else
            return 0;
    }

    public function foto(){
        if ($this->photos->count()<=0)
            return asset('my/images/sem_imagem.png');

        $cad = $this->photos[0];

        $arq = $cad->gallery_id.'/'.$cad->id.'.'.$cad->ext;

        if (Storage::disk('photos')->exists($arq))
            return asset('storage/photos/'.$arq);
        else
            return asset('my/images/sem_foto.png');
    }
}
