<?php

namespace App\Http\Controllers;

use App\Http\Requests\TournamentRequest;
use App\Models\Tournament;
use App\Models\TournamentCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class TournamentDateController extends Controller
{
    public function newDate(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'qtd' => 'required',
        ],
            [
                'qtd.required'=>"Informe a qtd",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $qtd = $request['qtd'];
        $date = date('Y-m-d');
        $hour = '20:00';
        if ($request->has('date')) {
            $date = $request['date'];
            $hour = $request['hour'];
            $date = date('Y-m-d', strtotime($date.' + 1 day'));
        }
        $qtd++;

        $lista = collect();
        $lista->push([
            'qtd'=>$qtd
            ,'date'=>$date
            ,'hour'=>$hour
        ]);

        $html = View::make('club.torn.dates', compact('lista'))->render();

        return ["result"=>"S",'qtd'=>$qtd,'html'=>$html];
    }

    public function getDates(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'torn_id' => 'required|exists:tournaments,id',
        ],
            [
                'torn_id.required'=>"Informe o torneio",
                'torn_id.exists'=>"Torneio não cadastrado",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = Tournament::whereid($request['torn_id'])
            ->whereclub_id(Auth::user()->club_id)
            ->first();
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não foi encontrado esse torneio no seu clube!'];
        }

        $lista = collect();
        $qtd = 0;
        foreach ($cad->dates as $date){
            $qtd++;
            $lista->push([
                'qtd'=>$qtd
                ,'date'=> date('Y-m-d', strtotime($date->data))
                ,'hour'=> date('H:i', strtotime($date->data))
            ]);
        }

        $html = View::make('club.torn.dates', compact('lista'))->render();
        return ["result"=>"S",'qtd'=>$qtd,'html'=>$html];
    }




}
