<?php

namespace App\Http\Controllers;

use App\Http\Requests\TournamentRequest;
use App\Models\Tournament;
use App\Models\Club;
use App\Models\TournamentCard;
use App\Models\TournamentDate;
use App\Models\TournamentSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Tournament::whereclub_id(Auth::user()->club_id)
            ->orderby('id','desc')
            ->get();
        return view('club.torn.list',compact('lista'));
    }


    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Tournament::whereclub_id(Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('name', 'like', '%'.$busca.'%')
                    ->orWhere('desc', 'like', '%'.$busca.'%')
                    ->orWhere('ring_game', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.torn.list',compact('lista','busca'));
    }

    public function create()
    {
        $cad = new Tournament();
        $cad->id=0;
        $cad->type=2;
        $cad->week_hour='20:00';
        $cad->week='';
        $cad->qtd_days=1;
        $cad->insc_app=0;
        $cad->promo=0;

        return view('club.torn.edit',compact('cad'));
    }

    public function createWeek($day)
    {
        $cad = new Tournament();
        $cad->id=0;
        $cad->type=1;
        $cad->week=$day;
        $cad->week_hour='22:00';
        $cad->qtd_days=1;
        $cad->insc_app=0;
        $cad->promo=0;

        return view('club.torn.edit',compact('cad'));
    }

    public function store(TournamentRequest $request)
    {
        if ($request['idd'] > 0) {
            $cad = Tournament::whereclub_id(Auth::user()->club_id)->whereid($request['idd'])->first();
            if (!$cad) {
                Session::flash('Aviso', 'Torneio não encontrado no seu clube!');
                return redirect()->route('club.event');
            }
        } else {
            $cad = new Tournament();
            $cad->club_id = Auth::user()->club_id;
        }

        $cad->name = $request['name'];
        $cad->type = $request['date_type'];
        $cad->qtd_days = $request['qtd_days'];

        $cad->week = null;
        $cad->week_hour = $request['week_hour'];
        for ($i = 1; $i <= 7; $i++)
            if ($request->has('ckweek' . $i))
                $cad->week .= $i;

        $cad->desc = $request['desc'];
        $cad->ring_game = $request['ring_game'];
        if ($request->get('ck_insc_app'))
            $cad->insc_app = 1;
        else
            $cad->insc_app = 0;
        if ($request->get('ck_cards'))
            $cad->promo = 1;
        else
            $cad->promo = 0;
        $cad->save();


        /***** salva a qtd de dias ****/
        if ($cad->type==2) {
            //apaga os existentes
            //TournamentDate::wheretournament_id($cad->id)->delete();
            //Salva os configurados
            for ($i = 1; $i <= $cad->qtd_days; $i++)
                if ($request['day_date'.$i]) {
                    $dia = new TournamentDate();
                    $dia->tournament_id = $cad->id;
                    $dia->data= $request['day_date'.$i].' '.$request['day_hour'.$i];
                    $dia->save();
                }
        }


        /**** Salvar as cartas ****/
        if ($cad->promo == 1){
            for ($i = 1; $i <= 6; $i++) {
                $card = TournamentCard::wheretournament_id($cad->id)
                    ->wherecard($i)
                    ->first();
                if ($request['card' . $i . '_premio'] <> '') {
                    if (!$card) {
                        $card = new TournamentCard();
                        $card->tournament_id = $cad->id;
                        $card->card = $i;
                    }
                    $card->premium = $request['card' . $i . '_premio'];
                    $card->qtd = $request['card' . $i . '_qtd'];
                    $card->save();
                } elseif ($card) {
                    $card->delete();
                }
            }
        }else{
            //Limpa as cartas configuradas
            //TournamentCard::wheretournament_id($cad->id)->delete();
        }

        Session::flash('Sok', 'Torneio Salvo!');
        return redirect()->route('club.torn.show',['id'=>$cad->id]);

    }

    public function show(Tournament $cad){
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.torn');
        //
        $cad2 = Club::where('id','=',$cad->club_id)->first();
        $cad['clubdados'] = $cad2->address.', '.$cad2->number.' - '.$cad2->district.'\n'.$cad2->city().'\nFones: '.$cad2->phone;//FormatarCEP($cad2->zipcode).'\n'
        $cad['clublogo'] = $cad2->logo() ? $cad2->logo() : '//storage/tournaments-models/modelo-logo.png';
        return view('club.torn.show',compact('cad'));
    }

    public function edit(Tournament $cad){
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.torn');

        return view('club.torn.edit',compact('cad'));
    }


    function SetImagem(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'torn_id' => 'required|exists:tournaments,id',
            'img1' => 'required|file',
        ],
            [
                'torn_id.required'=>"Informe o torneio",
                'torn_id.exists'=>"Torneio não cadastrado",
                'img1.required'=>"Selecione uma imagem",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = Tournament::whereid($request['torn_id'])
            ->whereclub_id(Auth::user()->club_id)
            ->first();
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não foi encontrado esse torneio no seu clube!'];
        }

        //dados do arquivo ftp
        $arquivo = $request->file('img1');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();
        $arqOld = $cad->img_ext;

        $arqName = $cad->id.'/imagem.'.$ext;

        /*** Salvar no banco ***/
        $cad->img_ext = $ext;
        $cad->save();

        /*** apagar o logo antigo ***/
        if ($arqOld<>''){
            $arqOld = $cad->id.'/imagem.'.$arqOld;
            if ( Storage::disk('tournaments')->exists($arqOld) )
                Storage::disk('tournaments')->delete($arqOld);
        }


        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (\Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou a imagem, tente novamente!'];
            Log::debug($e);
        }
        Storage::disk('tournaments')->put($arqName , $file );

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Imagem salva!'
            ,'logo'=>$cad->img()
        ];
    }


    function SetImagem2(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'torn_id' => 'required|exists:tournaments,id'
        ],
            [
                'torn_id.required'=>"Informe o torneio",
                'torn_id.exists'=>"Torneio não cadastrado"
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = Tournament::whereid($request['torn_id'])
            ->whereclub_id(Auth::user()->club_id)
            ->first();
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não foi encontrado esse torneio no seu clube!'];
        }
        $imagedata = base64_decode($request['img1']);
        $ext = 'png';
        $arqOld = $cad->img_ext;
        $arqName = $cad->id.'/imagem.'.$ext;
        $cad->img_ext = $ext;
        $cad->save();
        if ($arqOld<>''){
            $arqOld = $cad->id.'/imagem.'.$arqOld;
            if ( Storage::disk('tournaments')->exists($arqOld) )
                Storage::disk('tournaments')->delete($arqOld);
        }
        Storage::disk('tournaments')->put($arqName , $imagedata );

        return ["result"=>"S"
            ,"message"=>'Imagem salva!'
            ,'logo'=>$cad->img()
        ];
    }

    public function DelImagem(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'torn_id' => 'required|exists:tournaments,id',
        ],
            [
                'torn_id.required'=>"Informe o torneio",
                'torn_id.exists'=>"Torneio não cadastrado",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = Tournament::whereid($request['torn_id'])
            ->whereclub_id(Auth::user()->club_id)
            ->first();
        if ($cad->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não foi encontrado esse torneio no seu clube!'];
        }
        if (!$cad->img_ext)
            return ["result"=>"N","message"=>'Você ainda não tem imagem!'];


        $arqName = $cad->id.'/imagem.'.$cad->img_ext;

        //exclui o arquivo
        Storage::disk('tournaments')->delete( $arqName );

        Auditoria( 'DELETE', 'TOURNAMENT_IMG', $cad->id );

        //apaga no banco
        $cad->img_ext ='';
        $cad->save();

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Imagem Excluida!'
            ,'logo'=> $cad->img()
        ];
    }

    public function destroy(Tournament $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.torn');
        //

        Storage::disk('tournaments')->deleteDirectory( $cad->id );
        Auditoria('DELETE','TOURNAMENT',$cad->id);
        $cad->delete();

        Session::flash('Sok', 'Torneio excluido!');
        return redirect()->route('club.torn');
    }

    public function getTournaments(Request $request){

        if ($request['club_id'] === '667788229') {

            $data = collect();

            //Listar os torneios por data
            $lista = Tournament::select('tournaments.*')
                ->join('tournament_dates','tournament_dates.tournament_id','tournaments.id')
                ->where('img_ext','<>','')
                ->wheretype(2)
                ->whereraw('date(tournament_dates.data)>=current_date')
                ->groupby('tournaments.id')
                ->orderby('date(tournament_dates.data)','desc')
                ->limit(50)
                ->get();
            $data = $this->setListaToData($lista,$data);

            //Listar os torneios semanais
            $lista = Tournament::where('img_ext','<>','')
                ->wheretype(1)
                ->get();
            $data = $this->setListaToData($lista,$data);
        } else {

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

            $data = collect();

            //Listar os torneios por data
            $lista = Tournament::select('tournaments.*')
                ->join('tournament_dates','tournament_dates.tournament_id','tournaments.id')
                ->whereclub_id($request['club_id'])
                ->where('img_ext','<>','')
                ->wheretype(2)
                ->whereraw('date(tournament_dates.data)>=current_date')
                ->groupby('tournaments.id')
                ->get();
            $data = $this->setListaToData($lista,$data);

            //Listar os torneios semanais
            $lista = Tournament::whereclub_id($request['club_id'])
                ->where('img_ext','<>','')
                ->wheretype(1)
                ->get();
            $data = $this->setListaToData($lista,$data);

        }

       return ['result'=>'S','items'=>$data];
    }

    function setListaToData($lista,$data){
        foreach ($lista as $item){
            if ($item->insc_app==1) $insc='S'; else $insc = 'N';
            if ($item->promo==1) $promo='S'; else $promo = 'N';

            $dates = collect();
            //Se for do tipo data
            if ($item->type==2) {
                foreach ($item->dates as $date) {
                    $dates->push([
                        'day' => date('d/m/Y', strtotime($date->data)),
                        'hour' => date('H:i', strtotime($date->data)),
                    ]);
                }
            }else{
                //Se for semanal
                $dates->push([
                    'day' => date('d/m/Y', strtotime($item->date_event()) ),
                    'hour' => date('H:i', strtotime($item->week_hour)),
                ]);
            }

            $cad2 = Club::where('id','=',$item->club_id)->first();
            $clubName = $cad2->name;
            $clubLogo = $cad2->logo() ? $cad2->logo() : '//storage/tournaments-models/modelo-logo.png';

            $data->push([
                'id'=>$item->id,
                'name'=> TratarNull($item->name),
                'folder'=>$item->img(),
                //'ring_game'=>TratarNull($item->ring_game),
                'description'=>TratarNull($item->desc),
                'subscriptionapp'=>$insc,
                'promotion'=>$promo,
                'dates'=>$dates,
                'clubname'=>$clubName,
                'clublogo'=>$clubLogo
            ]);

        }

        return $data;
    }

    public function getPromotion(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'tournament_id' => 'required|exists:tournaments,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'tournament_id.required'=>"Informe o Torneio",
                'tournament_id.exists'=>"Torneio não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }


        $lista = TournamentCard::wheretournament_id($request['tournament_id'])
            ->get();

        $data = collect();
        foreach ($lista as $item){
            if ( ($item->saldo()>0) and ($item->premium<>'') ) {
                $data->push([
                    'id' => $item->id,
                    'card' => $item->carta(),
                    'premium' => $item->premium,
                ]);
            }
        }

        return ['result'=>'S','items'=>$data];
    }

    public function putUserSubscriptionTournament(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'tournament_id' => 'required|exists:tournaments,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'tournament_id.required'=>"Informe o Torneio",
                'tournament_id.exists'=>"Torneio não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //procurar inscriçao para o dia do torneio
        $cad = TournamentSubscription::wheretournament_id($request['tournament_id'])
            ->whereuser_app_id($request['user_id'])
            ->whereRaw(' date(date_event) >= current_date ')
            ->first();
        if ($cad) {
            $msg = 'Você já está inscrito para este torneio';
            if ($cad->date_event)
                $msg.=' do dia ' . date('d/m/Y', strtotime($cad->date_event));
            if ($cad->tournament_card_id)
                $msg.= '. Você ganhou o prêmio '.$cad->card->premium;

            //Retornos
            return [
                "result" => "N"
                , "message" => $msg
            ];
        }

        //Saldo do Prêmio
        if ($request->has('premium_id')){
            $card = TournamentCard::find($request['premium_id']);
            if ($card->saldo()<=0)
                return ["result"=>"N","message"=>'Prêmio não tem mais saldo, tente novamente'];
        }

        //data do evento
        $tourn = Tournament::find($request['tournament_id']);

        //Salva a inscrição
        $cad = new TournamentSubscription();
        $cad->tournament_id = $request['tournament_id'];
        $cad->user_app_id = $request['user_id'];
        $cad->date_event = $tourn->date_event();
        if ($request->has('premium_id'))
            $cad->tournament_card_id = $request['premium_id'];
        $cad->save();

        return ['result'=>'S'];
    }

    public function getUserSubscriptionTournament(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'tournament_id' => 'required|exists:tournaments,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'tournament_id.required'=>"Informe o Torneio",
                'tournament_id.exists'=>"Torneio não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //procurar inscriçao
        $cad = TournamentSubscription::wheretournament_id($request['tournament_id'])
            ->whereuser_app_id($request['user_id'])
            ->whereRaw(' date(date_event) >= current_date ')
            ->first();

        if (!$cad) {
            $subscription = 'N';
            $msg = 'Você ainda não se inscreveu neste torneio!';
        }else {
            $subscription = 'S';
            $msg = 'Você já está inscrito para este torneio';
            if ($cad->date_event)
                $msg.=' do dia ' . date('d/m/Y', strtotime($cad->date_event));
            if ($cad->tournament_card_id)
                $msg.= '. Você ganhou o prêmio '.$cad->card->premium;
        }

        return ['result'=>'S','usersubscription'=>$subscription,'message'=>$msg];
    }


    public function ListaInscritos(Tournament $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.torn');
        //
        /**
         * select user_app.name, tournament_cards.card, tournament_cards.premium
        from tournament_subscriptions
        inner join user_app on user_app.id = tournament_subscriptions.user_app_id
        left join tournament_cards on tournament_cards.id = tournament_subscriptions.tournament_card_id
        where tournament_subscriptions.tournament_id=1
        order by user_app.name
         */
        $lista = TournamentSubscription::selectRaw(' user_app.name, tournament_cards.card, tournament_cards.premium ')
            ->join('user_app','user_app.id','tournament_subscriptions.user_app_id')
            ->leftjoin('tournament_cards','tournament_cards.id','tournament_subscriptions.tournament_card_id')
            ->where('tournament_subscriptions.tournament_id',$cad->id)
            ->whereraw('date(tournament_subscriptions.date_event)>=current_date')
            ->orderby('tournament_subscriptions.date_event')
            ->orderby('user_app.name')
            ->get();

        return view('club.torn.inscritos',compact('lista','cad'));
    }
}
