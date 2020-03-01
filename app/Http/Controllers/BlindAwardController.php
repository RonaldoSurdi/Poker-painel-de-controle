<?php

namespace App\Http\Controllers;

use App\Models\Blind;
use App\Models\BlindAward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;


class BlindAwardController extends Controller
{
    public function add(Request $request)
    {
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'blind_id' => 'required|exists:blinds,id',
//            'name' => 'required|unique:blind_awards',
        ],
            [
                'blind_id.required'=>"Informe o Blind",
                'blind_id.exists'=>"Blind não cadastrado",
//                'name.required'=>"Informe o nome do jogador",
//                'name.unique'=>"Já existe um jogador com este nome",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** se é alteracao de award ***/
        $play_id = 0;
        if ($request->has('award_id'))
            $play_id = $request['award_id'];

        /*** se tem award ***/
        if ($play_id>0){
            $cad = BlindAward::find($play_id);
            //if (!$cad)
                return ["result"=>"N","message"=>'Prêmio não encontrado'];
            //
            //$message = 'Award Alterado';
        }//else {
            $cad = new BlindAward();
            $message = 'Prêmio adicionado';
        //}

        $lista = BlindAward::whereraw('blind_id = '.$request['blind_id'])
            ->orderby('ranking','desc')
            ->first();

        $ranking = 1;
        if ($lista) {
            $ranking = $lista->ranking + 1;
        }

        $cad->blind_id = $request['blind_id'];
        $cad->ranking = $ranking;
        $cad->save();
        //
        return ["result"=>"S","message"=>$message,'id'=>$cad->id];

    }

    public function index(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'blind_id' => 'required|exists:blinds,id',
        ],
            [
                'blind_id.required'=>"Informe o Blind",
                'blind_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //Award
        if ($request->has('award_id'))
            $award_id = $request['award_id'];
        else
            $award_id = 0;

        //se for um award somente
        if ($award_id>0){
            $lista = BlindAward::whereid($award_id)->get();
        }else{
            //Carrega a lista completa
            $lista = BlindAward::whereblind_id($request['blind_id'])
                    ->orderby('ranking','asc')
                    ->get();
        }

        $listaawards = [];
        $xids = 0;
        foreach ($lista as $item){
            $listaawards[$xids] = $item->id;
            $xids++;
        }

        $html = View::make('club.blind.award', compact('lista','etapa') )->render();
        return ["result"=>"S",'qtd'=>$lista->count(),'html'=>$html,'listawards'=>$listaawards];
    }

    public function destroy(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'award_id' => 'required|exists:blind_awards,id',
        ],
            [
                'award_id.required'=>"Informe o Blind",
                'award_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = BlindAward::find($request['award_id']);

        /**** Qtd de awardes restantes ****/
        $qtd = BlindAward::whereblind_id($cad->blind_id)->count() -1;


        //Auditoria
        Auditoria( 'DELETE', 'ROUND', $cad->id );

        //excluir cadastro no banco
        $cad->delete();


        return ["result"=>"S",'qtd'=>$qtd,'message'=>'Prêmio excluido'];
    }

    public function store(Request $request)
    {

        function numberformt($n) {
            $nx = str_replace(".","",$n);
            $nx = str_replace(",",".",$nx);
            return $nx;
        }

        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'blind_id' => 'required|exists:blinds,id',
            //'award_valor' => 'required|numeric',
        ],
            [
                'blind_id.required'=>"Informe o Blind",
                'blind_id.exists'=>"Blind não cadastrado",
                //'award_valor.required' => 'O Valor do Prêmio deve ser numérico',
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $cad = Blind::find($request['blind_id']);
        /**** verificar se essa foto é dele ***/
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este blind no seu clube'];
        }

        foreach ($cad->awards as $item){
            $input1 = 'award_ranking'.$item->id;
            $input2 = 'award_valor'.$item->id;
            $input3 = 'award_points'.$item->id;
            $input4 = 'award_another'.$item->id;
            $request[$input2] = numberformt($request[$input2]);
            /*** Salva a etapa **/
            if ($request->has($input1))
                $this->SaveStep($item->id,$request[$input1],$request[$input2],$request[$input3],$request[$input4]);
        }


        /*** ok ***/
        return ["result"=>"S","message"=>'Prêmios atualizados com sucesso!'];
    }

    function SaveStep($award,$var1,$var2,$var3,$var4){
        $cad = BlindAward::whereid($award)
            ->first();

        $cad->ranking = $var1;
        $cad->valor = $var2;
        $cad->points = $var3;
        $cad->another = $var4;
        $cad->save();

        return true;
    }

}
