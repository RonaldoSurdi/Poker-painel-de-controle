<?php

namespace App\Http\Controllers;

use App\Models\ClubIndicado;
use App\Models\UserApp;
use App\Models\UserFollow;
use App\Models\UserLocation;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserAppController extends Controller
{

    public function putUser(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:user_app,email',
            'senha' => 'nullable|max:250',
            //'datanascimento' => 'required|date',
            //'telefone' => 'required',
        ],
            [
                'email.required'=>"Informe seu E-mail",
                'email.unique'=>"E-mail já existe, utilize outro para se cadastrar",
                'senha.required'=>"Informe uma Senha para seu acesso",
                'senha.max'=>"Informe uma Senha menor que 250 letras",
                //'email.email'=>"Informe um endereço de e-mail válido ou faça o login usando o facebook",
                //'datanascimento.required'=>"Informe sua data de nascimento",
                //'datanascimento.date'=>"Informe uma data de nascimento válida",
            ]
        );
        /***se tem algum erro de campo***/
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }
        $cad = false;
        /***** Localiza o usuario ****/
        if ($request->has('id'))
            if (($request['id'] !== '')&&($request['id'] !== '1'))
                if ($request->has('one_signal'))
                    if (($request['one_signal'] !== '')) {
                        $cad = UserApp::where('onesignal_uid','=',trim($request['one_signal']))->first();
                    } else if ($request->has('unic_id')) {
                        if (($request['unic_id'] !== ''))
                            $cad = UserApp::where('deviceid','=',trim($request['unic_id']))->first();
                    }

        if (!$cad) $cad = new UserApp();

        $cad->email = trim( $request['email']);
        $cad->name = trim($request['nome']);
        if ($request->has('apelido'))
            if ($request['apelido']!=='') $cad->nickname = trim($request['apelido']);
        if ($request->has('senha'))
            if ($request['senha']!=='') $cad->senha = trim($request['senha']);
        if ($request->has('telefone'))
            if ($request['telefone']!=='') $cad->phone = trim( $request['telefone']);
        if ($request->has('datanascimento'))
            if ($request['datanascimento']!=='') $cad->birth_at = $request['datanascimento'];
        if ($request->has('one_signal'))
            if ($request['one_signal']!=='') $cad->onesignal_uid = $request['one_signal'];
        if ($request->has('one_signal_token'))
            if ($request['one_signal_token']!=='') $cad->onesignal_token = $request['one_signal_token'];
        if ($request->has('unic_id'))
            if ($request['unic_id']!=='') $cad->deviceid = $request['unic_id'];
        $cad->save();

        //disparar e-mail de link para confirmar
//        if (!$cad->confirmed)
//            EmailService::ConfirmarUsuario($cad->id);

        /** retorno ok com ID */
        return ["result"=>"S","user_id"=>$cad->id];
    }

    public function validUser(Request $request)
    {
//        $input = $request->all();
//        $log = json_encode($input);
//        Log::info('validUser: '.$log);

        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:user_app,email',
        ],
            [
                'email.required' => "Informe um E-mail de acesso",
                //'email.email' => "Informe um e-mail válido",
                'email.exists' => "E-mail não cadastrado",
            ]
        );
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                return ["result" => "N", "message" => $message];
            }
        }

        /***** Localiza o usuario ****/
        $cad = UserApp::whereemail(trim($request['email']))->first();

        /*if (!$cad)
            if ($request->has('unic_id'))
                if ($request['unic_id']<>'')
                    $cad = UserApp::where('deviceid','=',trim($request['unic_id']))->first();*/

        /*** se não achou ***/
        if (!$cad)
            return ["result" => "N",'message'=>"Usuário não encontrado"];

        /***** verificar senha ******/
        if ($cad->senha<>'') {
            if ($request->has('senha')) {
                if ($cad->senha <> trim($request['senha']))
                    return ["result" => "N", 'message' => "Usuário e senha não confere"];

            } else
                return ["result" => "N", 'message' => "Informe sua senha para o acesso"];
        }

        //Atualiza o OneSignal
        if ($request->has('one_signal'))
            $cad->onesignal_uid = $request['one_signal'];
        if ($request->has('one_signal_token'))
            $cad->onesignal_token = $request['one_signal_token'];
        if ($request->has('unic_id'))
            $cad->deviceid = $request['unic_id'];

        /*** verifica se confirmou o link ***/
//        if (!$cad->confirmed) {
//            //disparar e-mail de link para confirmar
//            EmailService::ConfirmarUsuario($cad->id);
//            return ["result" => "N", 'message' => "Cadastro não confirmado, você receberá um e-mail para confirmar seu cadastro"];
//        }

        /*** cadastro ok ***/
        //Ultima config de raio
        $raio = 30;
        $Log = UserLocation::whereuser_app_id($cad->id)
            ->orderby('id','desc')
            ->limit(1)
            ->first();
        if ($cad->radius>0)
            $raio = $Log->radius;

        return ["result"=>"S","user_id"=>$cad->id,"raio"=>$raio,"nome"=>$cad->name,"apelido"=>$cad->nickname,"avatar"=>$cad->photo];
    }

    public function getProfileUser(Request $request)
    {
        $cad = UserApp::find(trim($request['id']))->first();

        /*** se não achou ***/
        if (!$cad)
            return ["result" => "N",'message'=>"Usuário não encontrado"];

        $cadProfile = [];

        $cadProfile->push([
                'id' => $cad->id,
                'name' => $cad->name,
                'chat_view' => $cad->chat_view,
                'apelido' => $cad->nickname,
                'avatar' => $cad->photo
            ]);

        return ["result"=>"S","profile"=>$cadProfile];
    }


    public function confirm($idbase){
        $id = LIMPANUMERO( base64_decode($idbase) );
        $cad = UserApp::find($id);
        if (!$cad->id){
            Session::put('aviso', 'Cadastro não encontrado!<br>Cadastre-se no APP');
            return redirect('/msg');
        }

        //Confirmar o cadastro
        $cad->confirmed = 1;
        $cad->save();

        Session::put('ok', 'Cadastro Confirmado!');
        return redirect('/msg');
    }

    public function logar(UserApp $cad)
    {
        Session::put('userapp_id',$cad->id);
        if (in_array(env('APP_ENV'), ['stage', 'production'])) {
            return redirect()->secure('/map');
        }else{
            return redirect('/map');
        }


    }

}
