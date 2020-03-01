<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Club extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function city(){
        if (!$this->city_id) return '';

        $cad = DB::table('cities')
            ->selectRaw("id, concat(name,' / ',uf)".' "text" ')
            ->where('id',$this->city_id)
            ->first();
        if (!$cad) return '';
        return $cad->text;
    }

    public function cidade(){
        if (!$this->city_id) return new City();

        $cad = City::find($this->city_id);
        if (!$cad) return new City();

        return $cad;
    }

    public function premium(){
        $cad = License::whereclub_id($this->id)
            ->wherestatus(1)
            ->first();
        if (!$cad)
            return false;
        else
            return true;
    }

    public function license(){
        $cad = License::whereclub_id($this->id)
            ->wherestatus(1)
            ->orderby('type') //listar em primeiro as Anual
            ->first();
        if (!$cad){
            $cad = License::whereclub_id($this->id)
                ->orderby('id','desc')
                ->first();
            if (!$cad)
                return new License();
        }
        return $cad;
    }

    public function obs(){
        $cad = ClubObs::whereclub_id($this->id)->first();
        if (!$cad) return '';
        return $cad->obs;
    }

    public function info(){
        return $this->hasOne(ClubInfo::class,'club_id','id');
    }

    public function AmbChecked($pos){
        if (!$this->info) return '';

        if (!str_contains($this->info->comfortable,'A'.$pos.','))
            return '';
        else
            return 'checked="checked"';
    }

    public function week($day){
        $cad = ClubWeekHour::whereclub_id($this->id)->whereweek_day($day)->first();
        return $cad;
    }

    public function logo(){
        //$arq = asset('my/images/sem_logo.png');
        $arq = '';
        if ($this->info)
            if ($this->info->logo_ext<>''){
                $arq = $this->id . '/logo.' . $this->info->logo_ext;
                if (Storage::disk('logos')->exists($arq))
                    return asset('storage/logos/' . $arq);
                else
                    $arq = '';
                    //$arq = asset('my/images/sem_logo.png');
            }
        //
        return $arq;
    }

    public function gallery(){
        $gal = Gallery::whereclub_id($this->id)
            ->wheretitle('HOME'.$this->id)
            ->first();
        if (!$gal){
            $gal = new Gallery();
            $gal->club_id = $this->id;
            $gal->title = 'HOME'.$this->id;
            $gal->save();
        }
        return $gal;
    }
}
