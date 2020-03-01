<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingPlayer;
use App\Models\RankingPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class RankingPointController extends Controller
{


    public function store(Request $request)
    {
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'rank_id' => 'required|exists:rankings,id',
            'step' => 'required',
        ],
            [
                'step.required'=>"Informe a Etapa",
                'rank_id.required'=>"Informe o Ranking",
                'rank_id.exists'=>"Ranking não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $cad = Ranking::find($request['rank_id']);
        /**** verificar se essa foto é dele ***/
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este ranking no seu clube'];
        }

        foreach ($cad->players as $item){
            $step = $request['step'];

            $input = 'player'.$item->id.'_step'.$step;
            /*** Salva a etapa **/
            if ($request->has($input))
                $this->SaveStepPoint($item->id,$step,$request[$input]);
            else
                /*** não existe então remove ***/
                $this->SaveStepPoint($item->id,$step,0);
        }


        /*** ok ***/
        return ["result"=>"S","message"=>'Pontuação Salva!'];
    }

    function SaveStepPoint($player,$step,$point){
        $cad = RankingPoint::whereplayer_id($player)
            ->wherestep($step)
            ->first();
        /** Se for maior que zero salva */
        if ($point>0) {
            if (!$cad) {
                $cad = new RankingPoint();
                $cad->player_id = $player;
                $cad->step = $step;
            }
            $cad->point = $point;
            $cad->save();

        }elseif ($cad){ /*** Se for zero então deleta **/
            $cad->delete();
        }

        return true;
    }

    public function positions(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'rank_id' => 'required|exists:rankings,id',
        ],
            [
                'rank_id.required'=>"Informe o Ranking",
                'rank_id.exists'=>"Ranking não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $lista = RankingPlayer::selectraw(' ranking_players.id,ranking_players.name,sum(ranking_points.point)"Total" ')
                                ->join('ranking_points', 'ranking_players.id', '=', 'ranking_points.player_id')
                                ->whereranking_id($request['rank_id'])
                                ->groupby('ranking_players.id')
                                ->groupby('ranking_players.name')
//                                ->orderby(db::raw('3 desc'))
                                ->orderbyraw('3 desc')
                                ->get();


        $html = View::make('club.rank.position', compact('lista') )->render();
        return ["result"=>"S",'qtd'=>$lista->count(),'html'=>$html];

    }
}
