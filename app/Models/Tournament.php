<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Tournament extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //

    public function cards(){
        return $this->hasMany(TournamentCard::class,'tournament_id','id');
    }
    public function dates(){
        return $this->hasMany(TournamentDate::class,'tournament_id','id');
    }
    public function Datas(){
        return $this->hasMany(TournamentDate::class,'tournament_id','id');
    }
    public function subscription(){
        //apaga todas as incrições da data que ja aconteceu
        TournamentSubscription::wheretournament_id($this->id)
            ->whereRaw('date(date_event) < current_date')
            ->orWhereRaw('date_event is null')
            ->delete();

        return $this->hasMany(TournamentSubscription::class,'tournament_id','id');
    }

    public function img(){
        if (!$this->img_ext)
            return asset('my/images/sem_imagem.png');

        $arq = $this->id.'/imagem.'.$this->img_ext;

        if (Storage::disk('tournaments')->exists($arq))
            return asset('storage/tournaments/'.$arq);
        else
            return asset('my/images/sem_imagem.png');
    }

    public function WeekCkecked($day){
        if (!str_contains($this->week , $day))
            return '';

        return 'checked="checked"';
    }

    public function data(){
        if ($this->type==1) return 'Semanal';
        if ($this->type==2) return 'Data Agendada';
    }

    public function date_event(){
        //data agendada
        if ($this->type==2){
            foreach ($this->Datas as $item){
                $data_1 = date('Ymd', strtotime($item->data));
                $data_now = date('Ymd');
                //Se a data ainda não aconteceu
                if ($data_1>=$data_now) {
                    return $item->data;
                }
            }
        }else{ //Semanal
            //data de hoje
            $dataI = date('Y-m-d');
            //semana
            for ($i=0; $i<=6; $i++) {
                $date = date('Y-m-d', strtotime($dataI . ' + ' . $i . ' days'));
                $week = date('N', strtotime($date)) + 1;
                if ($week == 8) $week = 1;

                //Verifica se contem o dia da semana
                if (str_contains( $this->week , $week )){
                    return $date;
                }
            }
        }

        return null;
    }

    public function week(){
        $week='';
        for ($i=1; $i<=7; $i++){
            if (str_contains($this->week , $i))
                $week.=' '.diaSemanaEx($i-1);
        }
        return $week;
    }

    public function inscricao(){
        if ($this->insc_app==1)
            return "Sim";
        else
            return "Não";
    }

    public function Promo(){
        if ($this->promo==1)
            return "Sim";
        else
            return "Não";
    }

    public function carta($card,$info){
        $card = TournamentCard::wheretournament_id($this->id)->wherecard($card)->first();
        if (!$card) return '';

        if ($info=='P') return $card->premium;
        if ($info=='Q') return $card->qtd;
        if ($info=='F') return $card->fichas;
    }
}
