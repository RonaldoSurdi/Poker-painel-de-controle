<?php

namespace App\Http\Controllers;

use App\Models\UserApp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Socialite;

class FacebookController extends Controller
{
    public function login()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function pageFacebook(Request $request)
    {
        if ($request->has('error_message')){
            Session::flash('Saviso', 'Não foi possivel fazer o login usando o Facebook.');
            Log::warning('Facebook Login: '.$request['error_message']);
            return redirect('/');
        }
        try{
            $face = Socialite::with('facebook')->user();
            $cad = UserApp::whereface_id($face->id)->first();
        } catch (Exception $e) {
            Log::warning('Facebook Except: '.$e->getMessage());
            Session::flash('Saviso', 'Ops! Sem retorno do Facebook.\nTente novamente!');
            return redirect('/');
        }

        /*** Procura o usuário ****/
        $cad = UserApp::whereface_id($face->id)->first();
        if (!$cad)
            //procura pelo e-mail
            if ($face->email<>''){
                $cad = UserApp::whereemail($face->email)->first();
                if ($cad)
                    $cad->face_id = ($face->id);
            }

        //se não achou nem pelo e-mail nem pelo face-id
        if (!$cad) {
            /*** criar usuario do app***/
            $cad = new UserApp();
            $cad->face_id = ($face->id);
            $cad->confirmed = 1;
        }
        $cad->name = trim($face->name);
        $cad->email = trim( $face->email );
        $cad->save();


        /*** fazer o login e abrir o mapa ***/
        Session::put('userapp_id',$cad->id);
        if (in_array(env('APP_ENV'), ['stage', 'production'])) {
            return redirect()->secure('/map');
        }else{
            return redirect('/map');
        }
    }


    public function loginApp(Request $request)
    {
        //Gerar log do login
//        $input = $request->all();
//        $log = json_encode($input);
//        Log::info('LoginFace: '.$log);


        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'face_id' => 'required|string|max:255',
//            'dados' => 'required',
        ],
            [
                'face_id.required'=>"Informe o UID do Facebook",
                //'dados.required'=>"Informe os dados do Facebook",
            ]
        );
        /***se tem algum erro de campo***/
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /***** procura pelo face_id ****/
        $cad = UserApp::whereface_id($request['face_id'])->first();
        if (!$cad)
            //procura pelo e-mail
            if ($request['email']<>'') {
                $cad = UserApp::whereemail($request['email'])->first();
                if ($cad) //se achou salva o face ID
                    $cad->face_id = trim($request['face_id']);
            }

        //se não achou nem pelo e-mail nem pelo face-id
        if (!$cad){
            /*** criar usuario do app***/
            $cad = new UserApp();
            $cad->confirmed = 1;
            $cad->face_id = trim($request['face_id']);
        }
        $cad->name = trim($request['nome']);
        $cad->name = str_replace('undefined','',$cad->name);
        $cad->email = trim( $request['email']);
        $cad->birth_at = ( $request['datanascimento']);
        $cad->nickname = ( $request['apelido']);
        //Atualiza o OneSignal
        if ($request->has('one_signal'))
            $cad->onesignal_uid = $request['one_signal'];
        if ($request->has('one_signal_token'))
            $cad->onesignal_token = $request['one_signal_token'];
        if ($request->has('unic_id'))
            $cad->deviceid = $request['unic_id'];
        $cad->save();

        /** retorno ok com ID */
        return ["result"=>"S","user_id"=>$cad->id];
    }

}
