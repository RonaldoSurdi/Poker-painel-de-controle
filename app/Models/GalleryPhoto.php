<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryPhoto extends Model
{
    public $timestamps = false;
    //
    public function gallery(){
        return $this->hasOne(Gallery::class,'id','gallery_id');
    }

    public function img(){
        $arq = $this->gallery_id.'/'.$this->id.'.'.$this->ext;

        if (Storage::disk('photos')->exists($arq))
            return asset('storage/photos/'.$arq);
        else
            return asset('my/images/sem_foto.png');
    }
}
