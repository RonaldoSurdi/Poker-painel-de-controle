<?php

namespace App\Http\Controllers;

use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFollowController extends Controller
{
    public function followClub(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
            'club_id' => 'required|exists:clubs,id',
        ],
            [
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
                'club_id.required'=>"Informe o Clube",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );

        /***se tem algum erro de campo***/
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /** pega os dados vindos do app */
        $user_id = $request['user_id'];
        $club_id = $request['club_id'];

        /**** procura o follow ****/
        $cad = UserFollow::whereuser_app_id($user_id)
            ->whereclub_id($club_id)
            ->first();

        /***** Se veio pela API ****/
        if ($request->has('follow')){
            if ($request['follow']=='S'){
                if (!$cad){
                    $cad = new UserFollow();
                    $cad->user_app_id = $user_id;
                    $cad->club_id = $club_id;
                    $cad->save();
                }
                //
                $message = 'Clube adicionado em seus favoritos!';
                $status = 1;
            }else{
                if ($cad)
                    $cad->delete();
                //
                $message = 'Clube removido de seus favoritos!';
                $status = 0;
            }
        }else{
            /******** veio pelo site ******/
            if (!$cad){
                $cad = new UserFollow();
                $cad->user_app_id = $user_id;
                $cad->club_id = $club_id;
                $cad->save();
                //
                $message = 'Clube adicionado em seus favoritos!';
                $status = 1;
            }else{
                $cad->delete();
                //
                $message = 'Clube removido de seus favoritos!';
                $status = 0;
            }
        }

        /** retorno */
        return ["result"=>"S","status"=>$status,"message"=>$message];
    }
}
