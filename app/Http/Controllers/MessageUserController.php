<?php

namespace App\Http\Controllers;

use App\Models\MessageUser;
use App\Models\UserApp;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MessageUserController extends Controller
{

    public function calcular(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_type' => 'required',
        ],
            [
                'user_type.required'=>"Selecione o tipo de usuário",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //
        $qtd = 0;
        $valor='***';
        $valor_uni= 0.87;
        //
        if ($request['user_type']==1){
            /**** Seguidores ****/
            $qtd = UserFollow::whereclub_id(Auth::user()->club_id)
                ->count();
            $valor='Gratuito';

        }elseif ($request['user_type']==2){
            /**** Usuario em raio ****/
            $raio = $request['radius'];
            $lat = Auth::user()->club->lat;
            $lng = Auth::user()->club->lng;

            /**** listar por raio ****/
            $lista = DB::select(DB::raw('select id, (6371 *
            acos(
                cos(radians('.$lat.')) *
                cos(radians(lat)) *
                cos(radians('.$lng.') - radians(lng)) +
                sin(radians('.$lat.')) *
                sin(radians(lat))
            )) AS distance
            from user_app 
            group by id,distance
            HAVING distance <= '.$raio.'
            order by distance '
                ));
            
            $qtd = sizeof($lista);
            $valor = $valor_uni * $qtd;
            $valor = 'R$ '.number_format($valor, 2, ',', '.');

        }elseif ($request['user_type']==3){
            /**** Todos ****/
            $qtd = UserApp::count();
            $valor = $valor_uni * $qtd;
            $valor = 'R$ '.number_format($valor, 2, ',', '.');
        }

        $qtd = number_format($qtd, 0, ',', '.');
        return ['result'=>'S','qtd'=>$qtd,'valor'=>$valor];
    }

}
