<?php

namespace App\Models;

use App\Http\Controllers\MessageErrorController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function users(){
        return $this->hasMany( MessageUser::class,'message_id','id');
    }

    public function club(){
        return $this->hasOne( Club::class,'id','club_id');
    }

    public function img(){
        if (!$this->img_ext)
            return '';//asset('my/images/sem_imagem.png');

        $arq = $this->id.'/imagem.'.$this->img_ext;

        if (Storage::disk('messages')->exists($arq))
            return asset('storage/messages/'.$arq);
        else
            return '';//asset('my/images/sem_imagem.png');
    }

    public function date_hour(){
        if ($this->date_send)
            return date('H:i',strtotime($this->date_send));
        else
            return '19:00';
    }

    public function date_day(){
        if ($this->date_send)
            return date('Y-m-d',strtotime($this->date_send));
        else
            return date('Y-m-d',strtotime(  date('Y-m-d').' + 1 day' ) );
    }

    public function qtd($status){
        return MessageUser::wheremessage_id($this->id)->wherestatus($status)->count();
    }

    public function UserType(){
        if ($this->user_type==2)
            return "Usuários próximos há ".$this->radius.' Km';
        elseif ($this->user_type==3)
            return "Todos Usuários";
        else
            return "Seguidores do Clube";
    }

    public function _status(){
        if ($this->status==1)
            return "Agendada";
        elseif ($this->status==2)
            return "Enviada";
        elseif ($this->status==3)
            return "Erro";
        elseif ($this->status==9)
            return "Cancelada";
        else{
            if ($this->approved==0)
                return "Aguardando aprovação";
            else
                return "Aprovada"; //Pendente /
        }

    }

    public function Valor(){
        if ($this->price>0){
            return $this->price;
        }else
            return 'Gratuito';
    }

    public function read($user_id){
        $cad = MessageUser::wheremessage_id($this->id)
            ->whereuser_app_id($user_id)
            ->get();
        //
        if ($cad->status==3)
            return 'S';
        else
            return 'N';
    }

    public function OneQtd(){
        $cad = MessageOneSignal::wheremessage_id($this->id)->orderby('id','desc')->first();
        if (!$cad)
            return 0;
        return $cad->qtd;
    }

    public function OneErro(){
        $cad = MessageError::wheremessage_id($this->id)->orderby('id','desc')->first();
        if (!$cad)
            return 0;
        return $cad->error;
    }
}
