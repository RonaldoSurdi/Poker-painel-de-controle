<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ranking;
use App\Models\RankingPlayer;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = Ranking::whereclub_id(Auth::user()->club_id)
            ->orderby('id','desc')
            ->get();
        return view('club.rank.list',compact('lista'));
    }

    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Ranking::whereclub_id(Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('title', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.rank.list',compact('lista','busca'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cad = new Ranking();
        $cad->id = 0;
        $cad->steps = 1;

        return view('club.rank.edit',compact('cad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'idd' => 'required',
            'title' => 'required',
//            'event' => 'required',
        ],
            [
                'idd.required'=>"Informe o ranking",
                'title.required'=>"Informe um titulo",
                'event.required'=>"Informe o evento",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** Verifica se a galeria existe ****/
        if ($request['idd']>0) {
            $cad = Ranking::whereid($request['idd'])->whereclub_id(Auth::user()->club_id)->first();
            if (!$cad)
                return ["result"=>"N","message"=>'Ranking não encontrado no seu Clube'];
        }else{
            /*** Nova galeria ****/
            $cad = new Ranking();
            $cad->club_id = Auth::user()->club_id;
        }
        /*** Salva os dados novos ****/
        $cad->title = $request['title'];
        $cad->tournament_id = null;
        if ($request->has('event'))
            $cad->tournament_id = $request['event'];
        $cad->save();

        /*** ok ***/
        return ["result"=>"S","message"=>'Ranking Salvo!','id'=>$cad->id];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit(Ranking $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.rank');
        //

        return view('club.rank.edit',compact('cad'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.rank');
        //

        Storage::disk('rankings')->deleteDirectory( $cad->id );
        Auditoria('DELETE','RANKING',$cad->id);
        $cad->delete();

        Session::flash('Sok', 'Ranking Excluido!');
        return redirect()->route('club.rank');
    }


    public function list_tourn(Request $request){
        $lista = Tournament::whereclub_id(Auth::user()->club_id)->orderby('name')->get();

        $data = collect();
        $data->push([
            'id'=>0,
            'text'=>'Não cadastrado',
        ]);
        foreach ($lista as $item){
            $data->push([
                'id'=>$item->id,
                'text'=>$item->name,
            ]);
        }

        return ['result'=>'S','items'=>$data];
    }

    public function getRankings(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
            'club_id' => 'required|exists:clubs,id',
        ],
            [
                'club_id.required'=>"Informe o clube",
                'club_id.exists'=>"Clube não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $lista = Ranking::whereclub_id($request['club_id'])->orderby('id','desc')->get();

        $data = collect();

        foreach ($lista as $cad){
            $event_id = 0;
            $event_name = '';
            if ($cad->tournament_id>0){
                $event_id = $cad->tournament_id;
                $event_name = $cad->tournament->name;
            }

            /*** Lista de jogadores no ranking geral***/
            $RankGeral = collect();
            $listPlay = RankingPlayer::selectraw('ranking_players.name, ranking_players.photo_ext, sum(ranking_points.point)"total", ranking_players.id')
                ->join('ranking_points', 'ranking_players.id', '=', 'ranking_points.player_id')
                ->whereranking_id($cad->id)
                ->groupby('ranking_players.name')
                ->groupby('ranking_players.photo_ext')
                ->orderbyraw('3 desc')
                ->get();
            $i =0;
            foreach ($listPlay as $item){
                $i++;
                //
                $RankGeral->push([
                    'position' => $i.'º',
                    'point' => $item->total,
                    'name' => $item->name,
                    'avatar' => $item->photo()
                ]);
            }

            /***** Ranking por etapas *****/
            $steps = collect();
            $K = 0;
            for ($i=1; $i<= $cad->steps; $i++){
                $rank = collect();

                $listPlay = RankingPlayer::selectraw(' ranking_players.name, ranking_players.photo_ext, sum(ranking_points.point)"total",ranking_players.id ')
                    ->join('ranking_points', 'ranking_players.id', '=', 'ranking_points.player_id')
                    ->whereranking_id($cad->id)
                    ->wherestep($i)
                    ->groupby('ranking_players.name')
                    ->groupby('ranking_players.photo_ext')
                    ->orderbyraw('3 desc')
                    ->get();
                $pos =0;

                foreach ($listPlay as $item){
                    $pos++;
                    //
                    $rank->push([
                        'position' => $pos.'º',
                        'point' => $item->total,
                        'name' => $item->name,
                        'avatar' => $item->photo()
                    ]);
                }

                /**** montar a etapa ***/
                if (count($rank)>0) {
                    $steps->push([
                        'id' => $i
                        , 'title' => 'Etapa ' . $i
                        , 'rank' => $rank
                    ]);
                }
            }


            /***** montar os dados ***/
            $data->push([
                'id' => $cad->id
                ,'title' => $cad->title
                ,'tournament_id' => $event_id
                ,'tournament_name' => $event_name
                ,'rank_geral' => $RankGeral
                ,'steps' => $steps
            ]);
        }

        return ['result'=>'S','items'=>$data];
    }


    public function steps(Request $request){
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
        $rank = Ranking::find($request['rank_id']);
        /**** verificar se é dele ***/
        if ($rank->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este ranking no seu clube'];
        }

        /*** se veio o campo de qrd então salva a qtd *****/
        if ($request['qtd']>0){
            $rank->steps = $request['qtd'];
            $rank->save();
        }

        //qtd de etapas
        $qtd = $rank->steps;

        //monta o html
        $html = View::make('club.rank.steps', compact('qtd') )->render();

        //retorna o result em json
        return ["result"=>"S",'qtd'=>$qtd,'html'=>$html];
    }
}
