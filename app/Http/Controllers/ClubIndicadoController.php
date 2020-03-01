<?php

namespace App\Http\Controllers;

use App\Models\ClubIndicado;
use App\Models\UserApp;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClubIndicadoController extends Controller
{
    public function save(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'proprietario' => 'required|string|max:255',
            'telefone' => 'required',
        ],
            [
                //'endereco.required'=>"Informe um endereço",
            ]
        );

        /***se tem algum erro de campo***/
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $cad = new ClubIndicado();
        $cad->name = $request['nome'];
        $cad->responsible = $request['proprietario'];
        $cad->phone = $request['telefone'];
        if ($request->has('user_id'))
            if ($request['user_id']<>'')
                $cad->userapp_id =$request['user_id'];
        $cad->save();

        return ["result"=>"S","message"=>'ok'];
    }

}
