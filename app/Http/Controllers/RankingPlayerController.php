<?php

namespace App\Http\Controllers;

use App\Models\RankingPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class RankingPlayerController extends Controller
{
    public function add(Request $request)
    {
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'rank_id' => 'required|exists:rankings,id',
//            'name' => 'required|unique:ranking_players',
        ],
            [
                'rank_id.required'=>"Informe o Ranking",
                'rank_id.exists'=>"Ranking não cadastrado",
//                'name.required'=>"Informe o nome do jogador",
//                'name.unique'=>"Já existe um jogador com este nome",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** se é alteracao de player ***/
        $play_id = 0;
        if ($request->has('player_id'))
            $play_id = $request['player_id'];

        /*** se tem player ***/
        if ($play_id>0){
            $cad = RankingPlayer::find($play_id);
            if (!$cad)
                return ["result"=>"N","message"=>'Jogador não encontrado'];
            //
            $message = 'Jogador Alterado';
        }else {
            $cad = new RankingPlayer();
            $message = 'Jogador adicionado';
        }

        //
        $cad->ranking_id = $request['rank_id'];
        $cad->name = mb_convert_case($request['name'], MB_CASE_TITLE, "UTF-8"); //primeira letra em maiusculo
        $cad->save();
        //
        return ["result"=>"S","message"=>$message,'id'=>$cad->id,'name'=>$cad->name];
    }

    public function index(Request $request){
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

        //etapa
        if ($request->has('step'))
            $etapa = $request['step'];
        else
            $etapa = 1;

        //Player
        if ($request->has('player_id'))
            $player_id = $request['player_id'];
        else
            $player_id = 0;

        //se for um jogador somente
        if ($player_id>0){
            $lista = RankingPlayer::whereid($player_id)->get();
        }else
            //Carrega a lista completa
            $lista = RankingPlayer::whereranking_id($request['rank_id'])
                    ->get();
        //
        $html = View::make('club.rank.player', compact('lista','etapa') )->render();
        return ["result"=>"S",'qtd'=>$lista->count(),'html'=>$html];
    }

    public function destroy(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:ranking_players,id',
        ],
            [
                'player_id.required'=>"Informe o Ranking",
                'player_id.exists'=>"Ranking não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = RankingPlayer::find($request['player_id']);
        if ($cad->photo_ext<>'') {
            $arqName = $cad->ranking_id . '/' . $cad->id . '.' . $cad->photo_ext;
            if ( Storage::disk('rankings')->exists( $arqName ) ){
                Storage::disk('rankings')->delete( $arqName );
            }
        }

        /**** Qtd de jogadores restantes ****/
        $qtd = RankingPlayer::whereranking_id($cad->ranking_id)->count() -1;


        //Auditoria
        Auditoria( 'DELETE', 'PLAYER', $cad->id );

        //excluir cadastro no banco
        $cad->delete();


        return ["result"=>"S",'qtd'=>$qtd,'message'=>'Jogador excluido'];
    }

    function SetImagem(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:ranking_players,id',
            'foto1' => 'required|file',
        ],
            [
                'player_id.required'=>"Informe o Ranking",
                'player_id.exists'=>"Ranking não cadastrado",
                'foto1.required'=>"Selecione uma imagem",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = RankingPlayer::find($request['player_id']);

        //dados do arquivo ftp
        $arquivo = $request->file('foto1');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();
        $arqOld = $cad->photo_ext;

        $arqName = $cad->ranking_id.'/'.$cad->id.'.'.$ext;

        /*** Salvar no banco ***/
        $cad->photo_ext = $ext;
        $cad->save();

        /*** apagar o logo antigo ***/
        if ($arqOld<>''){
            $arqOld = $cad->ranking_id.'/'.$cad->id.'.'.$arqOld;
            if ( Storage::disk('rankings')->exists($arqOld) )
                Storage::disk('rankings')->delete($arqOld);
        }


        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (\Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou a imagem, tente novamente!'];
            Log::debug($e);
        }
        Storage::disk('rankings')->put($arqName , $file );

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Imagem salva!'
            ,'photo'=>$cad->photo()
        ];
    }

    public function DelImagem(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:ranking_players,id',
        ],
            [
                'player_id.required'=>"Informe o Ranking",
                'player_id.exists'=>"Ranking não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = RankingPlayer::find($request['player_id']);
        if (!$cad->photo_ext) {
            return ["result"=>"N","message"=>'Este jogador não tem imagem!'];
        }

        $arqName = $cad->ranking_id . '/' . $cad->id . '.' . $cad->photo_ext;
        if ( Storage::disk('rankings')->exists( $arqName ) ){
            Storage::disk('rankings')->delete( $arqName );
        }

        Auditoria( 'DELETE', 'PLAYER_IMG', $cad->id );

        //apaga no banco
        $cad->photo_ext ='';
        $cad->save();

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Imagem Excluida!'
            ,'photo'=> $cad->photo()
        ];
    }
}
