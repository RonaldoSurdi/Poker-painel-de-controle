<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function club(){
        return $this->hasOne(Club::class,'id','club_id');
    }

    public function vencida(){
        if (!$this->due_date) return true;
        //
        $data_val = date('Ymd', strtotime($this->due_date));
        $data_now = date('Ymd');
        return ($data_val<$data_now);
    }

    public function dias(){
        if ($this->vencida()) {
            $this->status=2;
            $this->save();
            return 0;
        }
        $data_val = date('Y-m-d', strtotime($this->due_date));

        $data_val = Carbon::createFromFormat('Y-m-d', $data_val);
        $data_now = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));

        $dias = $data_val->diffInDays($data_now);
        return $dias;
    }

    public function _type(){
        if ($this->type==1)
            return "Premium";
        elseif ($this->type==2)
            return "30 dias grátis";
        else
            return " não identificado ";
    }

    public function _status(){
        //0-pendente 1-ativa 2-vencida 9-bloqueada
        if ($this->status==1)
            return "Ativa";
        elseif ($this->status==2)
            return "Vencida";
        elseif ($this->status==3)
            return "Cancelada";
        elseif ($this->status==4)
            return "Arquivada";
        elseif ($this->status==9)
            return "Bloqueada";
        elseif ($this->status==0)
            return "Pendente";
        else
            return "situação ".$this->status;
    }
}
