<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

    public function MontarSemana($club_id){
        $dados = collect();

        /**** Pega a data atual e carregar o proximos 7 dias****/
        $dataI = date('Y-m-d');
        for ($i=0; $i<=6; $i++){
            $date = date('Y-m-d',strtotime($dataI.' + '.$i.' days'));


            $week = date('N',strtotime($date))+1;
            if ($week==8) $week=1;

            // cria a lista de torneios
            $items = collect();

            /**** Traz a lista de torneios da semana ****/
            $lista = Tournament::whereclub_id($club_id)
                ->where('week','like','%'.$week.'%')
                ->wheretype(1)
                ->get();
            foreach ($lista as $torn){
                $items->push([
                    'id' => $torn->id,
                    'title' => $torn->name,
                    'hour' => date('H:i', strtotime($torn->week_hour)),
                    //'ring_game' => TratarNull($torn->ring_game),
                    'description' => TratarNull($torn->desc),
                    //'banner' => $torn->img(),
                ]);
            }

            /**** Traz a lista de torneios agendados ****/
            $lista = Tournament::selectraw('tournaments.*,tournament_dates.data')
                ->join('tournament_dates','tournament_dates.tournament_id','tournaments.id')
                ->whereclub_id($club_id)
                ->whereraw("date(tournament_dates.data)='".$date."'")
                ->wheretype(2)
                ->get();

            foreach ($lista as $torn){
                $items->push([
                    'id' => $torn->id,
                    'title' => $torn->name,
                    'hour' => date('H:i', strtotime($torn->data)),
                    //'ring_game' => TratarNull($torn->ring_game),
                    'description' => TratarNull($torn->desc),
                    //'banner' => $torn->img(),
                ]);
            }

            $dados->push([
                'week'=> diaSemana( $week-1 ),
                'date'=>date('d/m/Y', strtotime($date)),
                'items'=>$items,
            ]);
        }

        return $dados;
    }


    public function index(){
        $lista = $this->MontarSemana(Auth::user()->club_id);
        return view('club.sche.list',compact('lista'));
    }


    public function SevenDays(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
            'club_id' => 'required|exists:clubs,id',
        ],
            [
                'club_id.required'=>"Informe o clube",
                'club_id.exists'=>"Clube não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }


        $dados = $this->MontarSemana($request['club_id']);

        /** retorno */
        return ['result'=>'S','items'=>$dados];
    }

}
