<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Blind;
use App\Models\BlindPlayer;
use App\Models\BlindRound;
use App\Models\BlindAward;
use App\Models\Tournament;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class BlindController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = Blind::whereclub_id(Auth::user()->club_id)
            ->orderby('id','desc')
            ->get();
        return view('club.blind.list',compact('lista'));
    }

    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Blind::whereclub_id(Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('title', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.blind.list',compact('lista','busca'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cad = new Blind();
        $cad->id = 0;
        $cad->blind_id = 0;
        $cad->steps = 1;
        $cad->bonus_round = 1;

        return view('club.blind.edit',compact('cad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newsleep(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'id' => 'required',
//            'event' => 'required',
        ],
            [
                'id.required'=>"Informe o blind",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $clone = false;
        if ($request['clone']=='1') $clone = true;

        $blind = Blind::whereraw('id = '.$request['id'])
            ->whereclub_id(Auth::user()->club_id)
            ->first();
        if (!$blind) return ["result"=>"N","message"=>'Erro ao criar blind!'];

        $blindsteps = Blind::whereraw('blind_id = '.$blind->blind_id)
            ->orderby('steps','desc')
            ->first();

        $blindprincipal = Blind::whereraw('id = '.$blind->blind_id)
            ->first();

        //
        $cad = new Blind();
        $cad->club_id = $blind->club_id;
        if ($clone) {
            $cad->title = '';
            $cad->steps = 1;
            $cad->blind_id = 0;
            $cad->tournament_id = $blind->tournament_id;
        } else {
            $cad->title = $blindprincipal->title.' - Etapa: '.($blindsteps->steps+1);
            $cad->tournament_id = $blind->tournament_id;
            $cad->steps = $blindsteps->steps+1;
            $cad->blind_id = $blind->blind_id;
        }
        $cad->premiacao = $blind->premiacao;
        $cad->qtd_mesas = $blind->qtd_mesas;
        $cad->jogadores_mesas = $blind->jogadores_mesas;
        $cad->buyin = $blind->buyin;
        $cad->buyin_fichas = $blind->buyin_fichas;
        $cad->rebuy = $blind->rebuy;
        $cad->rebuy_fichas = $blind->rebuy_fichas;
        $cad->addon = $blind->addon;
        $cad->addon_fichas = $blind->addon_fichas;
        $cad->bonus_fichas = $blind->bonus_fichas;
        $cad->bonus_round = $blind->bonus_round;
        $cad->passaport_valor = $blind->passaport_valor;
        $cad->passaport_buyin_fichas = $blind->passaport_buyin_fichas;
        $cad->passaport_rebuy_fichas = $blind->passaport_rebuy_fichas;
        $cad->passaport_addon_fichas = $blind->passaport_addon_fichas;
        $cad->save();
        if ($clone) {
            $cad->blind_id = $cad->id;
            $cad->save();
        }

        $blindrounds = BlindRound::whereraw('blind_id = '.$blind->id)
            ->orderby('id','asc')
            ->get();
        foreach ($blindrounds as $item) {
            $cadround = new BlindRound();
            $cadround->blind_id = $cad->id;
            $cadround->name = $item->name;
            $cadround->order = $item->order;
            $cadround->level = $item->level;
            $cadround->duration = $item->duration;
            $cadround->ante = $item->ante;
            $cadround->small_blind = $item->small_blind;
            $cadround->big_blind = $item->big_blind;
            $cadround->rebuy = $item->rebuy;
            $cadround->addon = $item->addon;
            $cadround->break = $item->break;
            $cadround->save();
        }

        $blindawards = BlindAward::whereraw('blind_id = '.$blind->id)
            ->orderby('id','asc')
            ->get();
        foreach ($blindawards as $item) {
            $cadaward = new BlindAward();
            $cadaward->blind_id = $cad->id;
            $cadaward->ranking = $item->ranking;
            $cadaward->valor = $item->valor;
            $cadaward->points = $item->points;
            $cadaward->another = $item->another;
            $cadaward->save();
        }

        //return view('club.blind.edit',compact('cad'));
        return ["result"=>"S","message"=>'Nova Etapa Adicionada!','id'=>$cad->id];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request['steps'] = numberformt($request['steps']);
        $request['qtd_mesas'] = numberformt($request['qtd_mesas']);
        $request['jogadores_mesas'] = numberformt($request['jogadores_mesas']);
        $request['buyin'] = numberformt($request['buyin']);
        $request['buyin_fichas'] = numberformt($request['buyin_fichas']);
        $request['rebuy'] = numberformt($request['rebuy']);
        $request['rebuy_fichas'] = numberformt($request['rebuy_fichas']);
        $request['addon'] = numberformt($request['addon']);
        $request['addon_fichas'] = numberformt($request['addon_fichas']);
        $request['bonus_fichas'] = numberformt($request['bonus_fichas']);
        $request['bonus_round'] = numberformt($request['bonus_round']);
        $request['passaport_valor'] = numberformt($request['passaport_valor']);
        $request['passaport_buyin_fichas'] = numberformt($request['passaport_buyin_fichas']);
        $request['passaport_rebuy_fichas'] = numberformt($request['passaport_rebuy_fichas']);
        $request['passaport_addon_fichas'] = numberformt($request['passaport_addon_fichas']);


        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'idd' => 'required',
            'event' => 'required',
            'title' => 'required',
            'steps' => 'required|numeric',
            'qtd_mesas' => 'required|numeric',
            'jogadores_mesas' => 'required',
            'buyin' => 'required|numeric',
            'buyin_fichas' => 'required|numeric',
            'rebuy' => 'required|numeric',
            'rebuy_fichas' => 'required|numeric',
            'addon' => 'required|numeric',
            'addon_fichas' => 'required|numeric',
            'bonus_fichas' => 'required|numeric',
            'passaport_valor' => 'required|numeric',
            'passaport_buyin_fichas' => 'required|numeric',
            'passaport_rebuy_fichas' => 'required|numeric',
            'passaport_addon_fichas' => 'required|numeric',
        ],
            [
                'idd.required'=>"Informe o blind",
                'event.required'=>"Selecione o Torneio",
                'title.required'=>"Informe um Título",
                'steps.required'=>"Informe a Etapa do Torneio",
                'qtd_mesas.required'=>"Informe a Quantidade de Mesas",
                'jogadores_mesas.required'=>"Informe o número de Jogadores por Mesa",
                'buyin.required' => 'O Buy-in deve ser numérico',
                'buyin_fichas.required' => 'Informe a qtdade de fichas do Buy-in',
                'rebuy.required' => 'Informe o ReBuy',
                'rebuy_fichas.required' => 'Informe a qtdade de fichas do ReBuy',
                'addon.required' => 'Informe o Add-on',
                'addon_fichas.required' => 'Informe a qtdade de fichas do Add-on',
                'bonus_fichas.required' => 'Informe a qtdade de fichas Bônus',
                'passaport_valor.required' => 'Informe o valor do Passaport',
                'passaport_buyin_fichas.required' => 'Informe a qtdade de fichas BuyIn do Passaport',
                'passaport_rebuy_fichas.required' => 'Informe a qtdade de fichas ReBuy do Passaport',
                'passaport_addon_fichas.required' => 'Informe a qtdade de fichas Add-on do Passaport',
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $newblind = false;

        /**** Verifica se a galeria existe ****/
        if ($request['idd']>0) {
            $cad = Blind::whereid($request['idd'])->whereclub_id(Auth::user()->club_id)->first();
            if (!$cad)
                return ["result"=>"N","message"=>'Blind não encontrado no seu Clube'];
        }else{
            /*** Nova galeria ****/
            $cad = new Blind();
            $cad->club_id = Auth::user()->club_id;
            $cad->blind_id = 0;
            $newblind = true;
        }
        /*** Salva os dados novos ****/
        $cad->title = $request['title'];
        $cad->steps = $request['steps'];
        $cad->premiacao = $request['premiacao'];
        $cad->qtd_mesas = $request['qtd_mesas'];
        $cad->jogadores_mesas = $request['jogadores_mesas'];
        $cad->buyin = $request['buyin'];
        $cad->buyin_fichas = $request['buyin_fichas'];
        $cad->rebuy = $request['rebuy'];
        $cad->rebuy_fichas = $request['rebuy_fichas'];
        $cad->addon = $request['addon'];
        $cad->addon_fichas = $request['addon_fichas'];
        $cad->bonus_fichas = $request['bonus_fichas'];
        $cad->bonus_round = $request['bonus_round'];
        $cad->passaport_valor = $request['passaport_valor'];
        $cad->passaport_buyin_fichas = $request['passaport_buyin_fichas'];
        $cad->passaport_rebuy_fichas = $request['passaport_rebuy_fichas'];
        $cad->passaport_addon_fichas = $request['passaport_addon_fichas'];
        $cad->tournament_id = null;
        if ($request->has('event'))
            $cad->tournament_id = $request['event'];
        $cad->save();
        if ($newblind) {
            $cad->blind_id = $cad->id;
            $cad->save();
        }

        /*** ok ***/
        return ["result"=>"S","message"=>'Blind Salvo!','id'=>$cad->id];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Blind  $blind
     * @return \Illuminate\Http\Response
     */
    public function edit(Blind $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.blind');
        //
        $cadclub = Club::find($cad->club_id);
        $clublogo = $cadclub->logo();
        $cad->clublogo = $clublogo;
        if ($cad->tournament_id <> null) {
            $torunm = Tournament::find($cad->tournament_id);
            $cad->tornamentimg = $torunm->img();
        } else {
            $cad->tornamentimg = $cadclub->logo();
        }

        return view('club.blind.edit',compact('cad'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blind  $blind
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blind $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.blind');
        //

        Storage::disk('blinds')->deleteDirectory( $cad->id );
        Auditoria('DELETE','BLIND',$cad->id);
        $cad->delete();

        Session::flash('Sok', 'Blind Excluido!');
        return redirect()->route('club.blind');
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

    public function getBlinds(Request $request){
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

        $lista = Blind::whereclub_id($request['club_id'])->orderby('id','desc')->get();

        $data = collect();

        foreach ($lista as $cad){
            $event_id = 0;
            $event_name = '';
            if ($cad->tournament_id>0){
                $event_id = $cad->tournament_id;
                $event_name = $cad->tournament->name;
            }

            /*** Lista de jogadores no blind geral***/
            $RankGeral = collect();
            $listPlay = BlindPlayer::selectraw('blind_players.name, blind_players.photo_ext, sum(blind_points.point)"total", blind_players.id')
                ->join('blind_points', 'blind_players.id', '=', 'blind_points.player_id')
                ->whereblind_id($cad->id)
                ->groupby('blind_players.name')
                ->groupby('blind_players.photo_ext')
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

            /*** Lista de rounds no blind geral***/
            $Rounds = collect();
            $listRounds = BlindRound::selectraw('blind_rounds.name, blind_rounds.id')
                ->join('blind_rounds.id', '=', 'blind_points.round_id')
                ->whereblind_id($cad->id)
                ->groupby('blind_rounds.name')
                ->orderbyraw('3 desc')
                ->get();
            $i =0;
            foreach ($listRounds as $item){
                $i++;
                //
                $Rounds->push([
                    'position' => $i.'º',
                    'name' => $item->name
                ]);
            }

            /***** Blind por etapas *****/
            $steps = collect();
            $K = 0;
            for ($i=1; $i<= $cad->steps; $i++){
                $blind = collect();

                $listPlay = BlindPlayer::selectraw(' blind_players.name, blind_players.photo_ext, sum(blind_points.point)"total",blind_players.id ')
                    ->join('blind_points', 'blind_players.id', '=', 'blind_points.player_id')
                    ->whereblind_id($cad->id)
                    ->wherestep($i)
                    ->groupby('blind_players.name')
                    ->groupby('blind_players.photo_ext')
                    ->orderbyraw('3 desc')
                    ->get();
                $pos =0;

                foreach ($listPlay as $item){
                    $pos++;
                    //
                    $blind->push([
                        'position' => $pos.'º',
                        'point' => $item->total,
                        'name' => $item->name,
                        'avatar' => $item->photo()
                    ]);
                }

                /**** montar a etapa ***/
                if (count($blind)>0) {
                    $steps->push([
                        'id' => $i
                        , 'title' => 'Etapa ' . $i
                        , 'blind' => $blind
                    ]);
                }
            }


            /***** montar os dados ***/
            $data->push([
                'id' => $cad->id
                ,'title' => $cad->title
                ,'tournament_id' => $event_id
                ,'tournament_name' => $event_name
                ,'blind_geral' => $RankGeral
                ,'rounds' => $Rounds
                ,'steps' => $steps
            ]);
        }

        return ['result'=>'S','items'=>$data];
    }


    public function steps(Request $request){
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

        /*** se veio o campo de qrd então salva a qtd *****/
        if ($request['qtd']>0){
            $blind->steps = $request['qtd'];
            $blind->save();
        }

        //qtd de etapas
        $qtd = $blind->steps;

        //monta o html
        $html = View::make('club.blind.steps', compact('qtd') )->render();

        //retorna o result em json
        return ["result"=>"S",'qtd'=>$qtd,'html'=>$html];
    }

    function SetImagemBlind(Request $request){
        /***valição dos campos ***/
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

        $imagedata = base64_decode($request['img1']);
        $ext = 'png';
        $arqName = $blind->tournament_id.'/blind.'.$ext;
            if ( Storage::disk('tournaments')->exists($arqName) )
                Storage::disk('tournaments')->delete($arqName);

        Storage::disk('tournaments')->put($arqName , $imagedata );

        return ["result"=>"S"
            ,"message"=>'Imagem salva!'
        ];
    }

    function roundRefresh(Request $request){
        /***valição dos campos ***/
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

        $roundatual = $blind->round_idx;

        return ["result"=>"S",'idx'=>$roundatual];
    }
}
