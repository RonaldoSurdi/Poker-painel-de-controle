<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Club;
use App\Models\ClubIndicado;
use App\Models\License;
use App\Models\UserApp;
use App\Models\UserLocation;
use App\Services\EmailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function cities(Request $request){
        $city = $request['search'];

        $lista = City::selectRaw("id, concat(name,' / ',uf)".' "text", lat,lng ')
            ->where('name','like','%'.$city.'%')
            ->orderBy('name')
            ->get();
        return ["result"=>"S",'items'=>$lista];
    }

    public function city($city_id){
        $lista = City::whereid($city_id)->first();
        return ["id"=>$lista->id,'text'=>$lista->name.' / '.$lista->uf];
    }

    public function setLatLng(Request $request){
        $id = $request['city_id'];
        $lat = $request['lat'];
        $lng = $request['lng'];
        $user_id = $request['user_id'];

        $cad = City::whereid($id)->first();
        if ($cad){
            $cad->lat = $lat;
            $cad->lng = $lng;
            $cad->save();

            //update nos logs
            UserLocation::wherecity_id($id)
                ->where('lat',0)
                ->update(['lat' => $lat,'lng' => $lng]);
            return ["result"=>"S"];
        }else{
            return ["result"=>"N","message"=>"Não encontrou cidade com id ".$id];
        }

    }


    public function getBannerPromo(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $banner = asset('my/banner_teste.jpg');
        $banner ='';

        if ($banner<>'')
            return ["result"=>"S","banner"=> $banner];
        else
            return ["result"=>"N"];
    }

    public function qtd_home(){
        $qtd_clubs = Club::count();

        $qtd_ufs = Club::selectRaw('cities.uf')
            ->join('cities','cities.id','clubs.city_id')
            ->groupby('cities.uf')
            ->orderby('cities.uf')
            ->get();
        $qtd_ufs = $qtd_ufs->count();
        $qtd_users = UserApp::count();

        $qtd_premium = License::select('club_id')
            //->where('club_id','<>','3') //Tadeu
            //->where('club_id','<>','356') //Daniel
            ->where('club_id','<>','350') //Saintec
            ->wherestatus(1)->groupby('club_id')->get();
        $qtd_premium = $qtd_premium->count();

        return ["result"=>"S"
            ,"qtd_clubs" => $qtd_clubs
            ,"qtd_ufs" => $qtd_ufs
            ,"qtd_users" => $qtd_users
            ,"qtd_premium" => $qtd_premium
        ];
    }

    public function testeFile(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
//            'chat_id' => 'required',
            'file' => 'required|file',
        ],
            [
                'chat_id.required'=>"Informe o id do chat",
                'file.required'=>"Informe um arquivo",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //dados do arquivo ftp
        $arquivo = $request->file('file');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();
        //$arqName = $nome.'.'.$ext;
        $arqName = $nome;

        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou o arquivo, tente novamente!'];
            Log::debug($e);
        }

        //chat id
        if ($request->has('chat_id'))
            $id = $request['chat_id'];
        else
            $id = 1;
        //
        $new = $id.'/'.$arqName;

        Storage::disk('chat')->put($new , $file );

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Arquivo salvo!'
            ,'url'=>url('/storage/chat/'.$new)
        ];
    }

}
