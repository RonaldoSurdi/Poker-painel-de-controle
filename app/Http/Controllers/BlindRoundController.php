<?php

namespace App\Http\Controllers;

use App\Models\Blind;
use App\Models\BlindRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;


class BlindRoundController extends Controller
{
    public function add(Request $request)
    {
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'blind_id' => 'required|exists:blinds,id',
//            'name' => 'required|unique:blind_rounds',
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

        /*** se é alteracao de round ***/
        $play_id = 0;
        if ($request->has('round_id'))
            $play_id = $request['round_id'];

        /*** se tem round ***/
        if ($play_id>0){
            $cad = BlindRound::find($play_id);
            //if (!$cad)
                return ["result"=>"N","message"=>'Round não encontrado'];
            //
            //$message = 'Round Alterado';
        }//else {
            $cad = new BlindRound();
            $message = 'Round adicionado';
        //}

        $smallblind = [10,15,25, 50, 75,100,150,200,300,400, 500, 600, 800,1000,1500,2000,3000,4000,5000, 6000, 8000, 10000,15000,20000,30000,40000,50000, 60000, 80000, 100000,200000,300000,400000,500000, 600000, 700000, 800000, 900000, 1000000];
        $bigblind   = [20,30,50,100,150,200,300,400,600,800,1000,1200,1600,2000,3000,4000,6000,8000,10000,12000,16000,20000,30000,40000,60000,80000,100000,120000,160000,200000,400000,600000,800000,1000000,1200000,1400000,1600000,1800000,2000000];

        $lista = BlindRound::whereraw('break = 0 AND blind_id = '.$request['blind_id'])
            ->orderby('id','desc')
            ->first();

        $listacount = 0;
        if ($lista) {
            $axsmallb = $lista->small_blind;
            for ($i=0; $i < count($smallblind); $i++){
                $axsmallb2 = $smallblind[$i];
                if ($axsmallb == $axsmallb2) {
                    $listacount = $i+1;
                    break;
                }
            }
        }
        $tamanoar = count($smallblind);
        if ($listacount>=$tamanoar) $listacount = $tamanoar-1;

        $cad->blind_id = $request['blind_id'];
        $namesend = $request['name'];
        if ($namesend === 'Break') {
            $cad->duration = $request['break_duration_default'];
            $cad->break = 1;
            $cad->rebuy = 0;
            $cad->addon = 0;
            /*$cad->ante = 'NULL';
            $cad->small_blind = 'NULL';
            $cad->big_blind = 'NULL';*/
        } else {
            $cad->duration = $request['round_duration_default'];
            $cad->small_blind = $smallblind[$listacount];
            $cad->big_blind = $bigblind[$listacount];
            $cad->ante = $request['round_ante_default'];
            $cad->break = 0;
        }
        //
        $cad->name = $namesend; //mb_convert_case($request['name'], MB_CASE_TITLE, "UTF-8"); //primeira letra em maiusculo
        $cad->save();
        //
        return ["result"=>"S","message"=>$message,'id'=>$cad->id,'name'=>$cad->name];

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

        $blind = Blind::find($request['blind_id']);
        /**** verificar se é dele ***/
        if ($blind->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este blind no seu clube'];
        }

        //Round
        if ($request->has('round_id'))
            $round_id = $request['round_id'];
        else
            $round_id = 0;

        //se for um round somente
        if ($round_id>0){
            $lista = BlindRound::whereid($round_id)->get();
        }else{
            //Carrega a lista completa
            $lista = BlindRound::whereblind_id($request['blind_id'])
                    ->get();
        }

        $roundatual = $blind->round_idx;
        $blindstat = $blind->status;

        $html = View::make('club.blind.round', compact('lista','etapa') )->render();
        return ["result"=>"S",'qtd'=>$lista->count(),'html'=>$html,'listrounds'=>$lista,'idx'=>$roundatual,'blindstat'=>$blindstat];
    }

    public function listrounds(Request $request){
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

        $initBlind = 0;
        $apprimary = 0;
        $roundidx = $request['roundidx'];
        $startimerblind = $request['startimerblind'];
        $roundid = $request['roundid'];

        if ($request->has('init'))
            $initBlind = $request['init'];

        $cad = Blind::find($request['blind_id']);
        if ($initBlind == 1) {
            if ($cad->status == 0) {
                $cad->status = 1;
                $cad->blind_ini = date('Y-m-d H:i:s');
                //$cad->round_id = $request['round_id'];
                $cad->round_idx = $roundidx;
                $cad->round_time = $startimerblind;
                $cad->round_id = $roundid;
                $cad->save();
                $apprimary = 1;
            } else {
                $roundidx = $cad->round_idx;
                $startimerblind = $cad->round_time;
            }
            //aqui
        } elseif ($initBlind == 2) {
            if ($request['apprimary'] == 1) {
                //$cad->round_id = $request['round_id'];
                $cad->round_idx = $roundidx;
                $cad->round_time = $startimerblind;
                $cad->round_id = $roundid;
                $cad->save();
                $apprimary = 1;
            } else {
                $roundidx = $cad->round_idx;
                $startimerblind = $cad->round_time;
            }
        }

        $blindgame = 0;
        $bgameok = true;
        $lista = BlindRound::whereblind_id($request['blind_id'])
            ->get();

        if ($roundidx > 0) {
            $roundx = 0;
            foreach ($lista as $item){
                if ($roundidx == $roundx) {
                    $bgameok = false;
                    //break;
                }
                if ($bgameok) {
                    if ($item->break == 0) $blindgame++;
                }
                $roundx++;
            }
        }

        return ["result"=>"S",'qtd'=>$lista->count(),'listrounds'=>$lista,'apprimary'=>$apprimary,'roundidx'=>$roundidx,'startimerblind'=>$startimerblind,'blindgame'=>$blindgame];
    }

    public function destroy(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'round_id' => 'required|exists:blind_rounds,id',
        ],
            [
                'round_id.required'=>"Informe o Blind",
                'round_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = BlindRound::find($request['round_id']);

        /**** Qtd de roundes restantes ****/
        $qtd = BlindRound::whereblind_id($cad->blind_id)->count() -1;


        //Auditoria
        Auditoria( 'DELETE', 'ROUND', $cad->id );

        //excluir cadastro no banco
        $cad->delete();


        return ["result"=>"S",'qtd'=>$qtd,'message'=>'Round excluido'];
    }

    public function move(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'round_id' => 'required|exists:blind_rounds,id',
        ],
            [
                'round_id.required'=>"Informe o Blind",
                'round_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //mover round
        $roundid = $request['round_id'];
        $roundpos = $request['round_pos'];
        //Log::info('pos: '.$roundpos);
        //Log::info('pos: '.$roundpos);
        $cad = BlindRound::find($roundid);

        $blindid = $cad->blind_id;

        if ($roundpos == 2) {
            $blind = BlindRound::whereraw('blind_id = '.$blindid)
                ->orderby('id','desc')
                ->first();
            if ($blind->id == $roundid) {
                return ["result"=>"N","message"=>'Não permitido'];
            }
            $blind2 = BlindRound::whereraw('blind_id = '.$blindid.' AND id > '.$roundid)
                ->orderby('id','asc')
                ->first();


            $roundidOld = $blind2->id;
            $cadmove = BlindRound::whereraw('id = ' . $roundidOld)
                ->first();
            $cadmove->id = 1;
            $cadmove->save();
            $cad->id = $roundidOld;
            $cad->save();
            $cadmove = BlindRound::whereraw('id = 1')
                ->first();
            $cadmove->id = $roundid;
            $cadmove->save();
        } else {
            $blind = BlindRound::whereraw('blind_id = ' . $blindid)
                ->orderby('id','asc')
                ->first();
            if ($blind->id == $roundid) {
                return ["result" => "N", "message" => 'Não permitido'];
            }
            $blind2 = BlindRound::whereraw('blind_id = '.$blindid.' AND id < '.$roundid)
                ->orderby('id','desc')
                ->first();

            $roundidOld = $blind2->id;
            $cadmove = BlindRound::whereraw('id = ' . $roundidOld)
                ->first();
            $cadmove->id = 1;
            $cadmove->save();
            $cad->id = $roundidOld;
            $cad->save();
            $cadmove = BlindRound::whereraw('id = 1')
                ->first();
            $cadmove->id = $roundid;
            $cadmove->save();
        }

        return ["result"=>"S",'message'=>'Round movido'];
    }

    public function store(Request $request)
    {
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

        $cad = Blind::find($request['blind_id']);
        /**** verificar se essa foto é dele ***/
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este blind no seu clube'];
        }

        foreach ($cad->rounds as $item){
            $input1 = 'round_duration'.$item->id;
            $input2 = 'round_ante'.$item->id;
            $input3 = 'round_small_blind'.$item->id;
            $input4 = 'round_big_blind'.$item->id;
            $input5 = 'round_rebuy'.$item->id;
            $input6 = 'round_addon'.$item->id;
            /*** Salva a etapa **/
            if ($request->has($input1))
                $this->SaveStep($item->id,$request[$input1],$request[$input2],$request[$input3],$request[$input4],$request[$input5],$request[$input6]);
            //else
              //  $this->SaveStep($item->id,0);
        }


        /*** ok ***/
        return ["result"=>"S","message"=>'Rounds atualizados com sucesso!'];
    }

    function SaveStep($round,$var1,$var2,$var3,$var4,$var5,$var6){
        $cad = BlindRound::whereid($round)
            ->first();
        /*Log::info('Save Round: '.$round);
        Log::info('var5: '.$var5.' '.$cad->rebuy);
        Log::info('var6: '.$var6.' '.$cad->addon);*/

        $cad->duration = $var1;
        $cad->ante = $var2;
        $cad->small_blind = $var3;
        $cad->big_blind = $var4;
        $cad->rebuy = $var5;
        $cad->addon = $var6;
        $cad->save();

        return true;
    }

}
