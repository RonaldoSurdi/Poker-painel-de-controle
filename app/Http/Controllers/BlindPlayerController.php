<?php

namespace App\Http\Controllers;

use App\Models\Blind;
use App\Models\BlindPlayer;
use App\Models\BlindRound;
use App\Models\BlindAward;
//use App\Models\UserApp;
//use App\Models\Tournament;
//use App\Models\TournamentCard;
use App\Models\TournamentSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class BlindPlayerController extends Controller
{
    public function add(Request $request)
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
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $blind = Blind::find($request['blind_id']);

        if ($blind->status == 2) {
            return ["result"=>"N","message"=>"Blind já finalizado, não é possível adicionar jogadores!"];
        }

        $roundidx = $blind->round_idx;
        $roundidx++;
        $roundnum = $blind->bonus_round;
        $atribuibonus = true;
        if (($roundnum>0)&&($roundidx>$roundnum)) $atribuibonus = false;

        /*** se é alteracao de player ***/
        $play_id = 0;
        if ($request->has('player_id'))
            $play_id = $request['player_id'];

        /*** se tem player ***/
        if ($play_id>0){
            $cad = BlindPlayer::find($play_id);
            if (!$cad)
                return ["result"=>"N","message"=>'Jogador não encontrado'];
            //
            $message = 'Jogador Alterado';
        } else {
            $cad = new BlindPlayer();
            $message = 'Jogador adicionado';
            $cad->user_app_id = 0;
            $cad->blind_id = $request['blind_id'];
            $cad->fichas_buyin = numberformt($request['buyin_fichas']);
            if ($atribuibonus) $cad->fichas_bonus = numberformt($request['bonus_fichas']);
            else $cad->fichas_bonus = 0;
            $cad->fichas_app = 0;
            $cad->card_app = 0;
            $cad->user_type = 0;
            $cad->active = 0;
            $cad->mesa = 0;
            $cad->cadeira = 0;
        }

        $cad2 = Blind::find($request['blind_id']);
        if ($cad2->status > 0) {
            $listar = BlindPlayer::whereraw('saiu = 1 AND blind_id = '.$request['blind_id'])
                ->get();
            foreach ($listar as $item) {
                $itemrank = $item->ranking;
                $itemrank = $itemrank+1;
                $item->ranking = $itemrank;
                $item->save();
            }
        }

        //
        $cad->name = mb_convert_case($request['name'], MB_CASE_TITLE, "UTF-8"); //primeira letra em maiusculo

        $cad->save();
        //
        return ["result"=>"S","message"=>$message,'id'=>$cad->id,'name'=>$cad->name];
    }

    public function addusersapp(Request $request)
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
        if ($validator->fails()) { /***se tem algum erro de campo***/
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

            return ["result"=>"N","message"=>'Jogador não encontrado'];
            //
            $message = 'Jogador Alterado';
        }

        $message = 'Jogadores adicionados';

        $blindid = $request['blind_id'];
        $fichas_buyin = numberformt($request['buyin_fichas']);
        $fichas_bonus = numberformt($request['bonus_fichas']);

        $cadblind = Blind::whereid($blindid)
            ->first();

        if ($cadblind->status == 2) {
            return ["result"=>"N","message"=>"Blind já finalizado, não é possível adicionar jogadores!"];
        }
        $roundidx = $cadblind->round_idx;
        $roundidx++;
        $roundnum = $cadblind->bonus_round;
        $atribuibonus = true;
        if ($roundidx>$roundnum) $atribuibonus = false;

        $tournament_id = $cadblind->tournament_id;

        $listaSubscription = TournamentSubscription::selectRaw(' user_app.id as id, user_app.name as name, tournament_cards.fichas as fichas, tournament_cards.card as card')
            ->join('user_app','user_app.id','tournament_subscriptions.user_app_id')
            ->leftjoin('tournament_cards','tournament_cards.id','tournament_subscriptions.tournament_card_id')
            ->where('tournament_subscriptions.tournament_id',$tournament_id)
            ->whereraw('date(tournament_subscriptions.date_event)>=current_date')
            ->orderby('tournament_subscriptions.date_event')
            ->orderby('user_app.name')
            ->get();

        foreach ($listaSubscription as $item) {
            $cadplayer = BlindPlayer::whereraw('user_app_id = '.$item->id.' AND blind_id = '.$blindid)
                ->first();
            //Log::info('id: '.$tournament_id.' '.$item->id);
            //Log::info('name: '.$item->name);
            if (!$cadplayer) {
                $cad = new BlindPlayer();
                $cad->blind_id = $blindid;
                $cad->user_app_id = $item->id;
                $cad->name = mb_convert_case($item->name, MB_CASE_TITLE, "UTF-8");
                $cad->fichas_buyin = $fichas_buyin;
                $fichasapp = $item->fichas;
                if ($fichas_bonus == -1) {
                    $cad->fichas_bonus = 0;
                    if ($fichasapp > 0) {
                        $cad->fichas_app = $fichasapp;
                    } else {
                        $cad->fichas_app = 0;
                    }
                    $cad->card_app = $item->card;
                } else {
                    if ($fichasapp > 0) {
                        if ($atribuibonus) $cad->fichas_bonus = $fichasapp;
                        else $cad->fichas_bonus = 0;
                        $cad->fichas_app = $item->fichas;
                        $cad->card_app = $item->card;
                    } else {
                        if ($atribuibonus) $cad->fichas_bonus = $fichas_bonus;
                        else $cad->fichas_bonus = 0;
                        $cad->fichas_app = 0;
                        $cad->card_app = $item->card;
                    }
                }
                $cad->user_type = 1;
                $cad->active = 0;
                $cad->save();
            }
        }

        return ["result"=>"S","message"=>$message];
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

        if ($request->has('tipo'))
            $etapa = $request['tipo'];
        else
            $etapa = 0;

        $playersfielody = 'id';
        $playersorderby = 'asc';
        if ($request->has('playersorderby')) {
            $ordbyplay = $request['playersorderby'];
            if ($ordbyplay == 0) {
                $playersfielody = 'id';
                $playersorderby = 'asc';
            } elseif ($ordbyplay == 1) {
                $playersfielody = 'name';
                $playersorderby = 'asc';
            } elseif ($ordbyplay == 2) {
                $playersfielody = 'name';
                $playersorderby = 'desc';
            }
        }

        $playerpage = $request['playerpage'];
        //$playerpage = $playerpage + 50;

        $playerbusca = '';
        if ($request->has('playerbusca')) {
            $plbusca = $request['playerbusca'];
            if ((strlen($plbusca)>=1)&&(strlen($plbusca)<=50))
                $playerbusca = " AND name like '%".$plbusca."%'";
        }

        if ($etapa == '0')
            $lista = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].$playerbusca)//mesa > 0 AND
                ->orderby($playersfielody,$playersorderby)
                //->limit($playerpage,50)
                ->skip($playerpage)
                ->take(50)
                //->offset($playerpage)
                //->limit($playerpage)
                ->get();
        else
            $lista = BlindPlayer::whereraw('mesa = '.$etapa.' AND blind_id = '.$request['blind_id'].$playerbusca)
                ->orderby($playersfielody,$playersorderby)
                //->limit($playerpage,50)
                ->get();


        $blind = Blind::find($request['blind_id']);

        $statusblind = $blind->status;

        $geral_fichas_buyin = 0;
        $geral_fichas_app = 0;
        $geral_fichas_bonus = 0;
        $geral_fichas_total = 0;
        $cardtext = ['DEZ','VALETE','DAMA','REI','AS','CURINGA'];

        foreach ($lista as $item){
            $item->fichas_rebuy = 0;
            $item->fichas_addon = 0;
            $valor_total = $blind->buyin;
            //$cardid = 0;
            $cardid = $item->card;
            if ($cardid>0) $cardid--;//$cardtext[$cardid].
            if ($item->card_app > 0)
                $item->premium_app = number_format($item->fichas_app, 0, ',', '.');
            else $item->premium_app = '-';
            if ($item->passaport) {
                $valor_total = $valor_total + $blind->passaport_valor;
                $item->fichas_buyin = $item->fichas_buyin + $blind->passaport_buyin_fichas;
                if ($item->rebuy > 0) {
                    $item->fichas_rebuy = $item->rebuy * ($blind->rebuy_fichas + $blind->passaport_rebuy_fichas);
                    $valor_total = $valor_total + ($item->rebuy * $blind->rebuy);
                }
                if ($item->addon > 0) {
                    $item->fichas_addon = $item->addon * ($blind->addon_fichas + $blind->passaport_addon_fichas);
                    $valor_total = $valor_total + ($item->addon * $blind->addon);
                }
            } else {
                if ($item->rebuy > 0) {
                    $item->fichas_rebuy = $item->rebuy * $blind->rebuy_fichas;
                    $valor_total = $valor_total + ($item->rebuy * $blind->rebuy);
                }
                if ($item->addon > 0) {
                    $item->fichas_addon = $item->addon * $blind->addon_fichas;
                    $valor_total = $valor_total + ($item->addon * $blind->addon);
                }
            }

            $fichas_total = $item->fichas_buyin + $item->fichas_bonus + $item->fichas_rebuy + $item->fichas_addon;
            $geral_fichas_buyin = $geral_fichas_buyin + $item->fichas_buyin;
            $geral_fichas_app = $geral_fichas_app + $item->fichas_app;
            $geral_fichas_bonus = $geral_fichas_bonus + $item->fichas_bonus;
            $geral_fichas_total = $geral_fichas_total + $fichas_total;
            $item->fichas_total = $fichas_total;
            $item->valor_total = $valor_total;

            $item->fichas_buyin = number_format($item->fichas_buyin, 0, ',', '.');
        }

        $lista2 = BlindPlayer::whereraw('active = 1 AND blind_id = '.$request['blind_id']);

        $geral_rebuy = 0;
        $geral_addon = 0;
        foreach ($lista2 as $item){
            $geral_rebuy = $geral_rebuy + $item->rebuy;
            $geral_addon = $geral_addon + $item->addon;
        }

        $lista3 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->get();

        $listacposition = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->orderby('mesa','desc')
            ->orderby('cadeira','desc')
            ->first();

        if ($listacposition) $mesasativas = $listacposition->mesa;
        else $mesasativas = $blind->qtd_mesas;

        $html = View::make('club.blind.player', compact('lista','etapa', 'geral_fichas_buyin', 'geral_fichas_app', 'geral_fichas_bonus', 'geral_fichas_total', 'statusblind') )->render();
        return ["result"=>"S",'qtd'=>$lista->count(),'total_insc'=>$lista2->count(),'total_game'=>$lista3->count(),'html'=>$html,'geral_rebuy'=>$geral_rebuy,'geral_addon'=>$geral_addon,'mesasativas'=>$mesasativas];
    }

    public function destroy(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = BlindPlayer::find($request['player_id']);
        if ($cad->photo_ext<>'') {
            $arqName = $cad->blind_id . '/' . $cad->id . '.' . $cad->photo_ext;
            if ( Storage::disk('blinds')->exists( $arqName ) ){
                Storage::disk('blinds')->delete( $arqName );
            }
        }

        /**** Qtd de jogadores restantes ****/
        $qtd = BlindPlayer::whereblind_id($cad->blind_id)->count() -1;

        //Auditoria
        Auditoria( 'DELETE', 'PLAYER', $cad->id );

        //excluir cadastro no banco
        $cad->delete();

        $lista1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$cad->blind_id)
            ->get();
        $lista1Count = $lista1->count();
        $lista1Count++;
        $recalrank = BlindPlayer::whereraw('saiu = 1 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->orderby('ranking','asc')
            ->get();
        foreach ($recalrank as $item){
            $item->ranking = $lista1Count;
            $item->save();
            $lista1Count++;
        }

        return ["result"=>"S",'qtd'=>$qtd,'message'=>'Jogador excluido'];
    }

    public function blindstat(Request $request){
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

        $msg = 'Sincronizando Blind';
        if ($request->has('action')) {
            $blindAction = $request['action'];
            if ($blindAction > 0) {
                $cad = Blind::find($request['blind_id']);
                if ($cad->status < 2) {
                    if ($cad->status == 0) {
                        if ($blindAction == 4) $cad->blind_action = 0;
                        else if (($cad->blind_action == 0)&&(($blindAction == 2)||($blindAction == 3))) $cad->blind_action = 0;
                        else if (($cad->blind_action == 1)&&(($blindAction == 2)||($blindAction == 3))) $cad->blind_action = 1;
                        else $cad->blind_action = 1;
                    } else {
                        $cad->blind_action = $blindAction;
                    }
                    $cad->save();
                }
            }
        }

        return ["result"=>"S",'message'=>$msg];
    }

    public function passaport(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = BlindPlayer::find($request['player_id']);
        $msg = 'Passaporte ';
        if ($cad->passaport) {
            $cad->passaport = 0;
            $msg .= 'Desativado';
        } else {
            $cad->passaport = 1;
            $msg .= 'Ativado';
        }

        $cad->save();

        return ["result"=>"S",'message'=>$msg];
    }

    public function info(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //info player
        $cad = BlindPlayer::find($request['player_id']);
        $blind = Blind::find($request['blind_id']);

        //info player
        $playertotalfichas = 0;
        $playertotalvalor = 0;
        $playerpassportfichas = 0;

        if ($cad->passaport) {
            $playerpassportfichas = $blind->passaport_buyin_fichas;
            $playerpassportfichas = $playerpassportfichas + ($cad->rebuy * $blind->passaport_rebuy_fichas);
            $playerpassportfichas = $playerpassportfichas + ($cad->addon * $blind->passaport_addon_fichas);

        }

        $msgdg = 'Jogador <b>' . $cad->name . '</b><br>';
        if ($cad->ranking>0) $msgdg .= '(<b>' . $cad->ranking . '°</b> lugar)<br><br>';
        else $msgdg .= '<br>';

        $msgdg .= ' Mesa <b>' . $cad->mesa . '</b> posição <b>' . $cad->cadeira . '</b><br><br>';

        $msgdg .= ' Passaporte ';
        if ($cad->passaport) {
            $playertotalfichas = $playerpassportfichas;
            $msgdg .= '<b>'.number_format($playerpassportfichas, 0, ',', '.').'</b> / Valor R$ <b>' . number_format($blind->passaport_valor, 2, ',', '.') . '</b><br>';
            $playertotalvalor = $playertotalvalor + $blind->passaport_valor;
        } else {
            $msgdg .= '(não adquiriu)<br>';
        }

        $inputtot = $blind->buyin_fichas;
        $playertotalfichas = $playertotalfichas + $inputtot;
        $inputval = $blind->buyin;
        $playertotalvalor = $playertotalvalor + $inputval;
        $msgdg .= ' Buyin: <b>';
        $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br>';

        $inputtot = $cad->fichas_bonus;
        $playertotalfichas = $playertotalfichas + $inputtot;
        $msgdg .= ' Fichas Bônus: <b>';
        $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas<br><br>';

        $inputtot = $blind->rebuy_fichas;
        $inputtot = $cad->rebuy * $inputtot;
        $playertotalfichas = $playertotalfichas + $inputtot;
        $inputval = $cad->rebuy * $blind->rebuy;
        $playertotalvalor = $playertotalvalor + $inputval;
        $msgdg .= ' ReBuy: <b>';
        $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br>';

        $inputtot = $blind->addon_fichas;
        $inputtot = $cad->addon * $inputtot;
        $playertotalfichas = $playertotalfichas + $inputtot;
        $inputval = $cad->addon * $blind->addon;
        $playertotalvalor = $playertotalvalor + $inputval;
        $msgdg .= ' Addon: <b>';
        $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br><br>';

        $msgdg .= 'TOTAL: <b>';
        $msgdg .= number_format($playertotalfichas, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($playertotalvalor, 2, ',', '.') . '</b><br>';

        return ["result"=>"S",'message'=>$msgdg];
    }

    public function active(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $blind = Blind::find($request['blind_id']);
        $totalmesas = $blind->qtd_mesas;
        $totalcadeiras = $blind->jogadores_mesas;


        $cad = BlindPlayer::find($request['player_id']);
        $msg = 'Jogador ';
        $msgdg = 'N';
        $cad->fichas_bonus = $request['bonus'];

        $player_realocado = '';

        $listacposition = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
            ->orderby('mesa', 'desc')
            ->orderby('cadeira', 'desc')
            ->first();

        if ($listacposition) {
            $mesapos = $listacposition->mesa;
            if (!$cad->active) {
                $listac1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                    ->get();
                $totaljogadores = $listac1->count();
                Log::info('totaljogadores: '.$totaljogadores.' '.$listac1->count());

                $cadeirasgeral = [];
                $idcadeira = 0;
                if ($totaljogadores < ($totalcadeiras*$mesapos)) {
                    $x = 0;
                    $xcount = 9999999;
                    $mesaid = $mesapos;
                    for ($z = 1; $z <= $mesapos; $z++) {
                        $listac1 = BlindPlayer::whereraw('mesa = '.$z.' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        $xcountax = $listac1->count();
                        if ($xcountax < $xcount) {
                            $xcount = $xcountax;
                            $mesaid = $z;
                            Log::info('mesaid: '.$mesaid);
                        }
                    }
                    $mesapos = $mesaid;
                    $cadeirasgeral[0]=1;
                    for ($z = 1; $z <= $totalcadeiras; $z++) {
                        $listac1 = BlindPlayer::whereraw('mesa = '.$mesapos.' AND cadeira = '.$z.' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->first();
                        if (!$listac1) {
                            $cadeirasgeral[$x] = $z;
                            Log::info('cadeirasgeral: '.$cadeirasgeral[$x]);
                            $x++;
                        }
                    }
                    shuffle($cadeirasgeral);
                    $cadeirapos = $cadeirasgeral[0];
                    Log::info('cadeirapos: '.$cadeirapos);
                } else {
                    $mesapos++;

                    $x = 0;
                    for ($z = 1; $z <= $totalcadeiras; $z++) {
                        $cadeirasgeral[$x] = $z;
                        $x++;
                    }
                    shuffle($cadeirasgeral);

                    if ($mesapos > $totalmesas) {
                        return ["result" => "N", "message" => "O número de vagas foi excedido adicione mais uma mesa!"];
                    }

                    $totaljogadores++;
                    $jogadopormesa = $totaljogadores / $mesapos;
                    $jogadopormesa = ceil($jogadopormesa);
                    for ($i = 1; $i < $mesapos; $i++) {
                        $listac2 = BlindPlayer::whereraw('mesa = ' . $i . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        if ($listac2) {
                            $jogcount = $listac2->count();
                            if ($jogcount > 0) {
                                $jogre = [];
                                $xj = 0;
                                foreach ($listac2 as $item) {
                                    $jogrx[$xj] = $xj;
                                    $jogre[$xj] = $item->id;
                                    $jogrn[$xj] = $item->name;
                                    $xj++;
                                }
                                shuffle($jogrx);
                                if ($jogcount > $jogadopormesa) {
                                    $jogcountot = $jogcount - $jogadopormesa;
                                    for ($z = 0; $z < $jogcountot; $z++) {
                                        $idjog = $jogrx[$z];
                                        $caddd = BlindPlayer::find($jogre[$idjog]);
                                        $caddd->mesa = $mesapos;
                                        $caddd->cadeira = $cadeirasgeral[$idcadeira];
                                        $caddd->save();
                                        $player_realocado .= 'Jogador <b>' . $jogrn[$idjog] . '</b> para Mesa <b>' . $mesapos . '</b> posição <b>' . $caddd->cadeira . '</b><br>';
                                        $idcadeira++;
                                    }
                                }
                            }
                        }
                    }
                    $cadeirapos = $cadeirasgeral[$idcadeira];
                }
                $cad->active = 1;
                $msg .= 'Habilitado';
                $msgdg = '';
                $cad->mesa = $mesapos;
                $cad->cadeira = $cadeirapos;
                $cad->save();
            } else {
                $cad->active = 0;
                $cad->save();
                $mesac = $cad->mesa;
                $cadeirac = $cad->cadeira;
                $realocou = false;
                $randuser = [];
                $randuseridx = 0;

                $listac1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                    ->get();
                $totaljogadores = $listac1->count();

                $jogadoresmesasCount = $totaljogadores / $totalcadeiras;

                $jogadoresmesas = ceil($jogadoresmesasCount);

                if ($jogadoresmesas < $mesapos) {
                    $jogadoresmesas++;
                    $listac1 = BlindPlayer::whereraw('mesa = ' . $jogadoresmesas . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                        ->get();
                    $mesacCount = $listac1->count();
                    if ($mesacCount > 0) {
                        $realocou = true;
                        foreach ($listac1 as $item) {
                            $randuser[$randuseridx] = $item->id;
                            $randuseridx++;
                        }
                        $jogadoresmesas--;
                        $player_realocado = '';
                        for ($i = 1; $i <= $jogadoresmesas; $i++) {
                            for ($z = 1; $z <= $totalcadeiras; $z++) {
                                $listac1 = BlindPlayer::whereraw('mesa = ' . $i . ' AND cadeira = ' . $z . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                                    ->get();
                                $mesacCount = $listac1->count();
                                if ($mesacCount == 0) {
                                    $randuseridx--;
                                    $listac3 = BlindPlayer::whereraw('id = ' . $randuser[$randuseridx])
                                        ->first();
                                    $player_realocado .= 'Jogador <b>' . $listac3->name . '</b> para Mesa <b>' . $i . '</b> posição <b>' . $z . '</b><br>';
                                    $listac3->mesa = $i;
                                    $listac3->cadeira = $z;
                                    $listac3->save();
                                    if ($randuseridx == 0) break;
                                }
                            }
                            if ($randuseridx == 0) break;
                        }
                    }

                }

                if (!$realocou) {
                    $listac1 = BlindPlayer::whereraw('mesa = ' . $mesac . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                        ->get();
                    $mesacCount = $listac1->count();

                    for ($i = 1; $i <= $totalmesas; $i++) {
                        if ($i <> $mesac) {
                            $listac2 = BlindPlayer::whereraw('mesa = ' . $i . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                                ->get();
                            $mesac2Count = $listac2->count();
                            if ($mesac2Count > ($mesacCount + 1)) {
                                foreach ($listac2 as $item) {
                                    $randuser[$randuseridx] = $item->id;
                                    $randuseridx++;
                                }
                                break;
                            }
                        }
                    }
                    shuffle($randuser);
                    if ($randuseridx > 0) {
                        $listac3 = BlindPlayer::whereraw('id = ' . $randuser[0])
                            ->first();
                        $player_realocado = 'Jogador <b>' . $listac3->name . '</b> para Mesa <b>' . $mesac . '</b> posição <b>' . $cadeirac . '</b><br>';
                        $listac3->mesa = $mesac;
                        $listac3->cadeira = $cadeirac;
                        $listac3->save();
                    }
                }
                $msg .= 'Desabilitado';
                $cad->mesa = 0;
                $cad->cadeira = 0;
                $cad->save();
            }
        } else {
            if (!$cad->active) {
                $msgdg = '';
                $cad->active = 1;
                $msg .= 'Habilitado';
            } else {
                $cad->active = 0;
                $msg .= 'Desabilitado';
            }
            $mesapos = 1;
            $cadeirasgeral[0]=1;
            $x = 0;
            for ($z = 1; $z <= $totalcadeiras; $z++) {
                $cadeirasgeral[$x] = $z;
                $x++;
            }
            shuffle($cadeirasgeral);
            $cadeirapos = $cadeirasgeral[0];
            $cad->mesa = $mesapos;
            $cad->cadeira = $cadeirapos;
            $cad->save();
        }
        $lista1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$cad->blind_id)
            ->get();
        $lista1Count = $lista1->count();
        $lista1Count++;
        $recalrank = BlindPlayer::whereraw('saiu = 1 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->orderby('ranking','asc')
            ->get();
        if ($recalrank) {
            foreach ($recalrank as $item) {
                $item->ranking = $lista1Count;
                $item->save();
                $lista1Count++;
            }
        }
        if ($msgdg !== 'N') {
            //info player
            $msgdg = 'Jogador <b>' . $cad->name . '</b><br><br>';
            if ($cad->mesa>0) {
                $msgdg .= ' Mesa <b>' . $cad->mesa . '</b> posição <b>' . $cad->cadeira . '</b><br><br>';
            }

            $playertotalfichas = 0;
            $playertotalvalor = 0;

            $msgdg .= ' Passaporte ';
            if ($cad->passaport) {
                $playertotalfichas = $blind->passaport_buyin_fichas;
                $msgdg .= '<b>'.number_format($blind->passaport_buyin_fichas, 0, ',', '.').'</b> / Valor R$ <b>' . number_format($blind->passaport_valor, 2, ',', '.') . '</b><br>';
                $playertotalvalor = $playertotalvalor + $blind->passaport_valor;
            } else {
                $msgdg .= '(não adquiriu)<br>';
            }

            $inputtot = $blind->buyin_fichas;
            $playertotalfichas = $playertotalfichas + $inputtot;
            $inputval = $blind->buyin;
            $playertotalvalor = $playertotalvalor + $inputval;
            $msgdg .= ' Buyin: <b>';
            $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br>';
            $inputtot = $cad->fichas_bonus;
            $playertotalfichas = $playertotalfichas + $inputtot;
            $msgdg .= ' Fichas Bônus: <b>';
            $msgdg .= number_format($inputtot, 0, ',', '.') . '</b> fichas<br><br>';

            $msgdg .= 'TOTAL: <b>';
            $msgdg .= number_format($playertotalfichas, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($playertotalvalor, 2, ',', '.') . '</b><br>';
        }
        if ($player_realocado !== '') {
            if (($msgdg == 'N')&&($cad->active == 0)) {
                $msgdg = 'Jogador <b>' . $cad->name . '</b> desabilitado<br><br>';
            }
            $msgdg .= '<br><hr><b>Jogadores realocados:</b><br>'.$player_realocado;
        }
        return ["result"=>"S",'message'=>$msg,'msgdg'=>$msgdg];
    }

    public function saiu(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //eliminar/reativar jogador
        $player_finalista = 0;
        $player_name = '';
        $player_photo = '';
        $player_resultado1 = '';
        $player_resultado2 = '';
        $player_realocado = 'N';
        $msg = 'Jogador saiu da partida';

        $cadblind = Blind::find($request['blind_id']);
        $totalmesas = $cadblind->qtd_mesas;
        $totalcadeiras = $cadblind->jogadores_mesas;

        $cad = BlindPlayer::find($request['player_id']);

        if ($cad->saiu == 1) {
            if ($cadblind->status == 2) {
                $cadblind->status = 1;
                $cadblind->save();
                $listac3 = BlindPlayer::whereraw('blind_id = '.$cad->blind_id.' AND ranking = 1')
                    ->first();
                $listac3->ranking = 0;
                $listac3->save();
            }


            $listacposition = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                ->orderby('mesa', 'desc')
                ->orderby('cadeira', 'desc')
                ->first();

            if ($listacposition) {
                $mesapos = $listacposition->mesa;

                $listac1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                    ->get();
                $totaljogadores = $listac1->count();

                $cadeirasgeral = [];
                $idcadeira = 0;
                if ($totaljogadores < ($totalcadeiras * $mesapos)) {
                    $x = 0;
                    $xcount = 9999999;
                    $mesaid = $mesapos;
                    for ($z = 1; $z <= $mesapos; $z++) {
                        $listac1 = BlindPlayer::whereraw('mesa = ' . $z . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        $xcountax = $listac1->count();
                        if ($xcountax < $xcount) {
                            $xcount = $xcountax;
                            $mesaid = $z;
                        }
                    }
                    $mesapos = $mesaid;
                    $cadeirasgeral[0] = 1;
                    for ($z = 1; $z <= $totalcadeiras; $z++) {
                        $listac1 = BlindPlayer::whereraw('mesa = ' . $mesapos . ' AND cadeira = ' . $z . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->first();
                        if (!$listac1) {
                            $cadeirasgeral[$x] = $z;
                            $x++;
                        }
                    }
                    shuffle($cadeirasgeral);
                    $cadeirapos = $cadeirasgeral[0];
                } else {
                    $mesapos++;

                    $x = 0;
                    for ($z = 1; $z <= $totalcadeiras; $z++) {
                        $cadeirasgeral[$x] = $z;
                        $x++;
                    }
                    shuffle($cadeirasgeral);

                    if ($mesapos > $totalmesas) {
                        return ["result" => "N", "message" => "O número de vagas foi excedido adicione mais uma mesa!"];
                    }

                    $totaljogadores++;
                    $jogadopormesa = $totaljogadores / $mesapos;
                    $jogadopormesa = ceil($jogadopormesa);
                    for ($i = 1; $i < $mesapos; $i++) {
                        $listac2 = BlindPlayer::whereraw('mesa = ' . $i . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        if ($listac2) {
                            $jogcount = $listac2->count();
                            if ($jogcount > 0) {
                                $jogre = [];
                                $xj = 0;
                                foreach ($listac2 as $item) {
                                    $jogrx[$xj] = $xj;
                                    $jogre[$xj] = $item->id;
                                    $jogrn[$xj] = $item->name;
                                    $xj++;
                                }
                                shuffle($jogrx);
                                if ($jogcount > $jogadopormesa) {
                                    $jogcountot = $jogcount - $jogadopormesa;
                                    for ($z = 0; $z < $jogcountot; $z++) {
                                        $idjog = $jogrx[$z];
                                        $caddd = BlindPlayer::find($jogre[$idjog]);
                                        $caddd->mesa = $mesapos;
                                        $caddd->cadeira = $cadeirasgeral[$idcadeira];
                                        $caddd->save();
                                        if ($player_realocado == 'N') {
                                            $player_realocado = '<br><hr><b>Jogadores realocados:</b><br>';
                                        }
                                        $player_realocado .= 'Jogador <b>' . $jogrn[$idjog] . '</b> para Mesa <b>' . $mesapos . '</b> posição <b>' . $caddd->cadeira . '</b><br>';
                                        $idcadeira++;
                                    }
                                }
                            }
                        }
                    }
                    $cadeirapos = $cadeirasgeral[$idcadeira];
                }
            } else {
                $mesapos = 0;
                $cadeirapos = 0;
            }

            $cad->mesa = $mesapos;
            $cad->cadeira = $cadeirapos;
            $cad->saiu = 0;
            $cad->ranking = 0;
            $cad->save();
            $msg = 'Jogador foi reativado';

            $lista1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$cad->blind_id)
                ->get();
            $lista1Count = $lista1->count();
            $lista1Count++;
            $recalrank = BlindPlayer::whereraw('saiu = 1 AND active = 1 AND blind_id = '.$request['blind_id'])
                ->orderby('ranking','asc')
                ->get();
            foreach ($recalrank as $item){
                $item->ranking = $lista1Count;
                $item->save();
                $lista1Count++;
            }
        } else {
            $lista1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$cad->blind_id)
                ->get();
            if ($lista1->count() == 1) {
                $msg = 'Jogador finalista não pode ser eliminado';
            } else {
                $cad->saiu = 1;
                //$cad->mesa = 0;
                //$cad->cadeira = 0;
                $cad->ranking = $lista1->count();
                $cad->save();

                if ($request['sortearauto'] == 1) {
                    $mesac = $cad->mesa;
                    $cadeirac = $cad->cadeira;
                    $realocou = false;
                    $randuser = [];
                    $randuseridx = 0;

                    $listac1 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                        ->get();
                    $totaljogadores = $listac1->count();

                    $jogadoresmesasCount = $totaljogadores / $totalcadeiras;
                    $jogadoresmesas = ceil($jogadoresmesasCount);

                    $listacposition = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                        ->orderby('mesa', 'desc')
                        ->orderby('cadeira', 'desc')
                        ->first();

                    if ($listacposition) $mesapos = $listacposition->mesa;
                    else $mesapos = $totalmesas;

                    if ($jogadoresmesas < $mesapos) {
                        $jogadoresmesas++;
                        $listac1 = BlindPlayer::whereraw('mesa = ' . $jogadoresmesas . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        $mesacCount = $listac1->count();
                        if ($mesacCount > 0) {
                            $realocou = true;
                            foreach ($listac1 as $item) {
                                $randuser[$randuseridx] = $item->id;
                                $randuseridx++;
                            }
                            $jogadoresmesas--;
                            $player_realocado = '<b>Jogadores realocados:</b><br>';
                            for ($i = 1; $i <= $jogadoresmesas; $i++) {
                                for ($z = 1; $z <= $totalcadeiras; $z++) {
                                    $listac1 = BlindPlayer::whereraw('mesa = ' . $i . ' AND cadeira = ' . $z . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                                        ->get();
                                    $mesacCount = $listac1->count();
                                    if ($mesacCount == 0) {
                                        $randuseridx--;
                                        $listac3 = BlindPlayer::whereraw('id = ' . $randuser[$randuseridx])
                                            ->first();
                                        $player_realocado .= 'Jogador <b>' . $listac3->name . '</b> para Mesa <b>' . $i . '</b> posição <b>' . $z . '</b><br>';
                                        $listac3->mesa = $i;
                                        $listac3->cadeira = $z;
                                        $listac3->save();
                                        if ($randuseridx == 0) break;
                                    }
                                }
                                if ($randuseridx == 0) break;
                            }
                        }

                    }

                    if (!$realocou) {
                        $listac1 = BlindPlayer::whereraw('mesa = ' . $mesac . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                            ->get();
                        $mesacCount = $listac1->count();

                        for ($i = 1; $i <= $totalmesas; $i++) {
                            if ($i <> $mesac) {
                                $listac2 = BlindPlayer::whereraw('mesa = ' . $i . ' AND saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                                    ->get();
                                $mesac2Count = $listac2->count();
                                if ($mesac2Count > ($mesacCount + 1)) {
                                    foreach ($listac2 as $item) {
                                        $randuser[$randuseridx] = $item->id;
                                        $randuseridx++;
                                    }
                                    break;
                                }
                            }
                        }
                        shuffle($randuser);
                        if ($randuseridx > 0) {
                            $listac3 = BlindPlayer::whereraw('id = ' . $randuser[0])
                                ->first();
                            $player_realocado = 'O Jogador <b>' . $listac3->name . '</b> foi recolocado na Mesa <b>' . $mesac . '</b> posição <b>' . $cadeirac . '</b>';
                            $listac3->mesa = $mesac;
                            $listac3->cadeira = $cadeirac;
                            $listac3->save();
                        }
                    }
                }

                $lista3 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                    ->get();
                $lita3count = $lista3->count();
                if (($lita3count == 1) && ($cadblind->status == 1)) {
                    $cadblind->status = 2;
                    $cadblind->blind_end = date('Y-m-d H:i:s');
                    //$blind->round_time = 1;
                    $cadblind->save();
                    $player_finalista = 1;
                    $finalista = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = ' . $request['blind_id'])
                        ->first();
                    $finalista->ranking = 1;
                    $finalista->save();
                    $player_name = $finalista->name;
                    $player_photo = $finalista->photo();
                    $resultadoranking = BlindPlayer::whereraw('blind_id = ' . $request['blind_id'] . ' AND active = 1')
                        ->orderby('ranking', 'asc')
                        ->get();
                    $x = 1;
                    foreach ($resultadoranking as $item) {
                        if ($x <= 15) {
                            $player_resultado1 .= '<span class="cblspan58">' . $x . '°:</span> ' . $item->name . '<br>';
                        } elseif (($x > 15) && ($x <= 30)) {
                            $player_resultado2 .= '<span class="cb2span58">' . $x . '°:</span> ' . $item->name . '<br>';
                        }
                        $x++;
                    }
                }
            }
        }

        return ["result"=>"S",'message'=>$msg,'playerfinalista'=>$player_finalista,'playername'=>$player_name,'playerphoto'=>$player_photo,'playerresultado1'=>$player_resultado1,'playerresultado2'=>$player_resultado2,'playerrealocado'=>$player_realocado];
    }

    function SetImagem(Request $request){
        /***valição dos campos ***/
        //Log::info('player_id: '.$request['player_id']);
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                Log::info("message: ".$message);
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** localizar o evento ***/
        $cad = BlindPlayer::find($request['player_id']);

        $arquivo = $request->file('avatar');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();
        $arqOld = $cad->photo_ext;

        $arqName = $cad->blind_id.'/'.$cad->id.'.'.$ext;

        /*** Salvar no banco ***/
        $cad->photo_ext = $ext;
        $cad->save();

        /*** apagar o logo antigo ***/
        if ($arqOld<>''){
            $arqOld = $cad->blind_id.'/'.$cad->id.'.'.$arqOld;
            if ( Storage::disk('blinds')->exists($arqOld) )
                Storage::disk('blinds')->delete($arqOld);
        }

        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (\Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou a imagem, tente novamente!'];
            //Log::debug($e);
        }
        Storage::disk('blinds')->put($arqName , $file );

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Imagem salva!'
            ,'photo'=>$cad->photo()
        ];
    }

    public function DelImagem(Request $request){
        /**** valição dos campos ****/
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:blind_players,id',
        ],
            [
                'player_id.required'=>"Informe o Blind",
                'player_id.exists'=>"Blind não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //excluir foto
        $cad = BlindPlayer::find($request['player_id']);
        if (!$cad->photo_ext) {
            return ["result"=>"N","message"=>'Este jogador não tem imagem!'];
        }

        $arqName = $cad->blind_id . '/' . $cad->id . '.' . $cad->photo_ext;
        if ( Storage::disk('blinds')->exists( $arqName ) ){
            Storage::disk('blinds')->delete( $arqName );
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

        $blind = Blind::find($request['blind_id']);
        /**** verificar se essa foto é dele ***/
        if ($blind->club_id <> Auth::user()->club_id){
            return ["result"=>"N","message"=>'Não encontramos este blind no seu clube'];
        }

        $resultadosave='';

        if ($request['premiacao_players'] <> '') {
            $blind->premiacao = $request['premiacao_players'];
            $blind->save();
        }

        foreach ($blind->players as $item){
            $input1 = 'player_bonus'.$item->id;
            $input2 = 'player_mesa'.$item->id;
            $input3 = 'player_cadeira'.$item->id;
            $input4 = 'player_rebuy'.$item->id;
            $input5 = 'player_addon'.$item->id;
            $entersalv = false;
            /*** Salva a etapa **/
            if ($request->has($input1)) {
                $this->SaveStep($item->id,$request[$input1],$request[$input2],$request[$input3],$request[$input4],$request[$input5]);
                $resultadosaveax = '';

                if (($request[$input2] <> $item->mesa)||($request[$input3] <> $item->cadeira)) {
                    $entersalv = true;
                    $resultadosaveax .= ' Mesa <b>' . $request[$input2] . '</b> posição <b>' . $request[$input3] . '</b><br><br>';
                }

                $playertotalfichas = 0;
                $playertotalvalor = 0;
                $playerpassportfichas = 0;

                if ($request[$input4] <> $item->rebuy) {
                    $entersalv = true;
                    $inputax = $request[$input4];
                    $inputtot = $blind->rebuy_fichas;
                    if ($inputax > $item->rebuy) {
                        $inputtot = ($inputax-$item->rebuy)*$inputtot;
                        $inputval = ($inputax-$item->rebuy)*$blind->rebuy;
                        $resultadosaveax.= ' ReBuys adicionados: <b>';
                        if ($item->passaport) $playerpassportfichas = $playerpassportfichas + (($inputax-$item->rebuy) * $blind->passaport_rebuy_fichas);
                    } else {
                        $inputtot = ($item->rebuy-$inputax)*$inputtot;
                        $inputval = ($item->rebuy-$inputax)*$blind->rebuy;
                        $resultadosaveax.= ' ReBuys removidos: <b>';
                        if ($item->passaport) $playerpassportfichas = $playerpassportfichas + (($item->rebuy-$inputax) * $blind->passaport_rebuy_fichas);
                    }
                    $playertotalfichas = $playertotalfichas + $inputtot;
                    $playertotalvalor = $playertotalvalor + $inputval;
                    $resultadosaveax.=  number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br>';
                }

                if ($request[$input5] <> $item->addon) {
                    $entersalv = true;
                    $inputax = $request[$input5];
                    $inputtot = $blind->addon_fichas;
                    if ($inputax > $item->addon) {
                        $inputtot = ($inputax-$item->addon)*$inputtot;
                        $inputval = ($inputax-$item->addon)*$blind->addon;
                        $resultadosaveax.= ' AddOn adicionado: <b>';
                        if ($item->passaport) $playerpassportfichas = $playerpassportfichas + (($inputax-$item->addon) * $blind->passaport_addon_fichas);
                    } else {
                        $inputtot = ($item->addon-$inputax)*$inputtot;
                        $inputval = ($item->addon-$inputax)*$blind->addon;
                        $resultadosaveax.= ' AddOn removido: <b>';
                        if ($item->passaport) $playerpassportfichas = $playerpassportfichas + (($item->addon-$inputax) * $blind->passaport_addon_fichas);
                    }
                    $playertotalfichas = $playertotalfichas + $inputtot;
                    $playertotalvalor = $playertotalvalor + $inputval;
                    $resultadosaveax.=  number_format($inputtot, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($inputval, 2, ',', '.') . '</b><br>';
                }
                if ($entersalv) {
                    if ($resultadosave <> '')
                        $resultadosave.='<hr>';
                    if ($item->passaport) {
                        $playertotalfichas = $playertotalfichas + $playerpassportfichas;
                        $resultadosaveax = 'Passaporte '.number_format($playerpassportfichas, 0, ',', '.') . '</b> fichas<br>'.$resultadosaveax;
                    }
                    $resultadosaveax.= 'TOTAL: <b>';
                    $resultadosaveax.= number_format($playertotalfichas, 0, ',', '.') . '</b> fichas / Valor R$ <b>' . number_format($playertotalvalor, 2, ',', '.') . '</b><br>';
                    $resultadosave.= 'Jogador <b>'.$item->name . '</b><br>'.$resultadosaveax;
                }

            }
        }
        if ($resultadosave <> '')
            $resultadosave='<b>Dados atualizados:</b><br><br>'.$resultadosave;
        else $resultadosave = 'N';

        /*** ok ***/
        return ["result"=>"S","message"=>'Dados Atualizados!','resultadosave'=>$resultadosave];
    }

    function SaveStep($player,$var1,$var2,$var3,$var4,$var5){
        $cad = BlindPlayer::whereid($player)
            ->first();

        $cad->fichas_bonus = $var1;
        $cad->mesa = $var2;
        $cad->cadeira = $var3;
        $cad->rebuy = $var4;
        $cad->addon = $var5;
        $cad->save();

        return true;
    }

    public function podium(Request $request){
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

        $message = 'Podium';

        $pos1o = [];
        $lista1 = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 1')
            ->first();
        if ($lista1) {
            $listposition = BlindAward::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 1')
                ->first();
            $premio = '';
            if ($listposition) {
                if ($listposition->valor > 0) $premio .= 'R$ '.number_format($listposition->valor, 2, ',', '.');
                if ($listposition->points > 0) {
                    if ($premio <>'') $premio = $premio . ' - ';
                    $premio .= number_format($listposition->points, 0, ',', '.').' pts';
                }
                if ($listposition->another <> '') {
                    if ($premio <>'') $premio = $premio . '<br>';
                    $premio .= $listposition->another;
                }
            }
            $pos1o[0] = $lista1->name;
            $pos1o[1] = $lista1->photo();
            $pos1o[2] = $premio;
        }

        $pos2o = [];
        $lista1 = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 2')
            ->first();
        if ($lista1) {
            $listposition = BlindAward::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 2')
                ->first();
            $premio = '';
            if ($listposition) {
                if ($listposition->valor > 0) $premio .= 'R$ '.number_format($listposition->valor, 2, ',', '.');
                if ($listposition->points > 0) {
                    if ($premio <>'') $premio = $premio . ' - ';
                    $premio .= number_format($listposition->points, 0, ',', '.').' pts';
                }
                if ($listposition->another <> '') {
                    if ($premio <>'') $premio = $premio . '<br>';
                    $premio .= $listposition->another;
                }
            }
            $pos2o[0] = $lista1->name;
            $pos2o[1] = $lista1->photo();
            $pos2o[2] = $premio;
        }

        $pos3o = [];
        $lista1 = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 3')
            ->first();
        if ($lista1) {
            $listposition = BlindAward::whereraw('blind_id = '.$request['blind_id'].' AND ranking = 3')
                ->first();
            $premio = '';
            if ($listposition) {
                if ($listposition->valor > 0) $premio .= 'R$ '.number_format($listposition->valor, 2, ',', '.');
                if ($listposition->points > 0) {
                    if ($premio <>'') $premio = $premio . ' - ';
                    $premio .= number_format($listposition->points, 0, ',', '.').' pts';
                }
                if ($listposition->another <> '') {
                    if ($premio <>'') $premio = $premio . '<br>';
                    $premio .= $listposition->another;
                }
            }
            $pos3o[0] = $lista1->name;
            $pos3o[1] = $lista1->photo();
            $pos3o[2] = $premio;
        }

        $posmore = [];
        $qtd = 0;
        $slots = 0;
        $str = '';
        $lncolor = 0;
        $lista = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND ranking > 3')
            ->orderby('ranking','asc')
            ->get();
        foreach ($lista as $item){
            if ($lncolor == 0) {
                $str .= '<div class="lin1">';
                $lncolor = 1;
            } else {
                $str .= '<div class="lin2">';
                $lncolor = 0;
            }
            $str .='<span>'.$item->ranking.'° - </span> '.$item->name.'</div>';
            $qtd++;
            if ($qtd>=5) {
                $posmore[$slots] = $str;
                $slots++;
                $qtd = 0;
                $str = '';
                $lncolor = 0;
            }
        }
        if ($str !== '') {
            $pree = 5-$qtd;
            for ($i=1; $i<=$pree; $i++){
                if ($lncolor == 0) {
                    $str .= '<div class="lin1">&nbsp</div>';
                    $lncolor = 1;
                } else {
                    $str .= '<div class="lin2">&nbsp</div>';
                    $lncolor = 0;
                }
            }
            $posmore[$slots] = $str;
            $slots++;
        }
        if ($slots < 4) {
            $pree = 4-$slots;
            for ($i=1; $i<=$pree; $i++){
                $str = '<div class="lin1">&nbsp</div>';
                $str .= '<div class="lin2">&nbsp</div>';
                $str .= '<div class="lin1">&nbsp</div>';
                $str .= '<div class="lin2">&nbsp</div>';
                $str .= '<div class="lin1">&nbsp</div>';
                $posmore[$slots] = $str;
                $slots++;
            }
        }

        return ["result"=>"S",'message'=>$message,'pos1o'=>$pos1o,'pos2o'=>$pos2o,'pos3o'=>$pos3o,'posmore'=>$posmore,'count'=>$slots];
    }

    public function positions(Request $request){
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

        $message = 'Ranking';
        $html = [];
        $qtd = 0;
        $slots = 0;
        $str = '';
        $lista = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND ranking > 0')
            ->orderby('ranking','asc')
            ->get();
        foreach ($lista as $item){
            $listposition = BlindAward::whereraw('blind_id = '.$request['blind_id'].' AND ranking = '.$item->ranking)
                ->first();
            $premio = '';
            if ($listposition) {
                if ($listposition->valor > 0) {
                    $premio .= 'Prêmio: R$ ' . number_format($listposition->valor, 2, ',', '.') . '<br>';
                } else {
                    $premio .= 'Prêmio: -<br>';
                }
                if ($listposition->points > 0) {
                    $premio .= 'Pontos: ' . number_format($listposition->points, 0, ',', '.') . ' pts' . '<br>';
                } else {
                    $premio .= 'Pontos: -<br>';
                }
                if ($listposition->another <> '') {
                    $premio .= $listposition->another;
                } else {
                    $premio .= '-';
                }
            } else {
                $premio .= '-<br>';
                $premio .= '-<br>';
                $premio .= '-<br>';
            }
            if ($premio <>'') $premio = '<br><div class="premioc" style="color:#9c9c9c;line-height:1;margin-bottom:5px;font-size:90% !important;">'.$premio.'</div>';
            else $premio = '<br>';
            $str .='<span>'.$item->ranking.'°.</span> '.$item->name.$premio;
            $qtd++;
            if ($qtd>=5) {
                $html[$slots] = $str;
                $slots++;
                $qtd = 0;
                $str = '';
            }
        }
        if ($str !== '') {
            $html[$slots] = $str;
            $slots++;
        }

        return ["result"=>"S",'message'=>$message,'html'=>$html,'count'=>$slots];
    }

    public function mesas(Request $request){
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

        $message = 'Mesas';
        $html = [];
        $qtd = 0;
        $slots = 0;
        $str = '';
        $str2 = '';
        $lista = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND mesa > 0 AND active = 1 AND saiu = 0')
            ->orderby('mesa','asc')
            ->orderby('cadeira','asc')
            ->get();
        foreach ($lista as $item){
            if (($qtd !== $item->mesa)&&($str === '')) {
                $str .='<span><strong>Mesa '.$item->mesa.':</strong></span><br>';
                $qtd = $item->mesa;
                $str .= $str2;
                $str2 = '';
            }
            if ($qtd !== $item->mesa) {
                $str2 = 'Posição <strong>'.$item->cadeira.'</strong> - '.$item->name.'<br>';
                $html[$slots] = $str;
                $slots++;
                $qtd = 0;
                $str = '';
            } else {
                $str .='Posição <strong>'.$item->cadeira.'</strong> - '.$item->name.'<br>';
            }
        }
        if ($str !== '') {
            $html[$slots] = $str;
            $slots++;
        }

        return ["result"=>"S",'message'=>$message,'html'=>$html,'count'=>$slots];
    }

    public function mesasrefresh(Request $request){
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

        $cadblind = Blind::find($request['blind_id']);

        $msgpremio = $cadblind->premiacao;

        if ($request->has('startimerblind')) {
            $startimerblind = $request['startimerblind'];
            if ($startimerblind>0) {
                $cadblind->round_time = $startimerblind;
                $cadblind->round_update = date('Y-m-d H:i:s');
                $cadblind->save();
            }
        }

        if ($request['roundid']!=='0') {
            $roundlist = BlindRound::whereraw('id = '.$request['roundid'])
                ->first();

            $blind_atual_name = $roundlist->small_blind.'/'.$roundlist->big_blind;
            $blind_atual_ante = $roundlist->ante;

            $roundlist = BlindRound::whereraw('break = 0 AND id > '.$request['roundid'])
                ->orderby('id','asc')
                ->first();

            if ($roundlist) {
                $blind_next_name = $roundlist->small_blind.'/'.$roundlist->big_blind;
                $blind_next_ante = $roundlist->ante;
            } else {
                $blind_next_name = '-';
                $blind_next_ante = '-';
            }
        } else {
            $blind_atual_name = '';
            $blind_atual_ante = '';
            $blind_next_name = '';
            $blind_next_ante = '';
        }

        $blindaction = $cadblind->blind_action;

        $lista2 = BlindPlayer::whereraw('ACTIVE = 1 AND blind_id = '.$request['blind_id'])
            ->get();

        $geral_rebuy = 0;
        $geral_addon = 0;
        $geral_rebuy_passaport = 0;
        $geral_addon_passaport = 0;

        foreach ($lista2 as $item){
            $geral_rebuy = $geral_rebuy + $item->rebuy;
            $geral_addon = $geral_addon + $item->addon;
            if ($item->passaport) {
                $geral_rebuy_passaport = $geral_rebuy_passaport + $item->rebuy;
                $geral_addon_passaport = $geral_addon_passaport + $item->addon;
            }
        }

        $fichas_total = 0;
        $fichas_total_passaport = 0;
        foreach ($lista2 as $item){
            $fichas_total = $fichas_total + $item->fichas_buyin + $item->fichas_bonus; //$item->fichas_app +
            if ($item->passaport) {
                $fichas_total_passaport = $fichas_total_passaport + $cadblind->passaport_buyin_fichas;
            }
        }

        $player_finalista = 0;
        $player_name = '';
        $player_photo = '';
        $player_resultado1 = '';
        $player_resultado2 = '';

        $lista3 = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->get();
        $lita3count = $lista3->count();
        if ($lita3count == 1) {
            $player_finalista = 1;
            $finalista = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$request['blind_id'])
                ->first();
            $finalista->ranking = 1;
            $finalista->save();
            $player_name = $finalista->name;
            $player_photo = $finalista->photo();
            $resultadoranking = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND active = 1')
                ->orderby('ranking','asc')
                ->get();
            $x = 1;
            foreach ($resultadoranking as $item){
                if ($x <= 15) {
                    $player_resultado1.= '<span class="cblspan58">'.$x.'°:</span> '.$item->name.'<br>';
                } elseif (($x > 15)&&($x <= 30)) {
                    $player_resultado2.= '<span class="cb2span58">'.$x.'°:</span> '.$item->name.'<br>';
                }
                $x++;
            }
        }

        $listacposition = BlindPlayer::whereraw('saiu = 0 AND active = 1 AND blind_id = '.$request['blind_id'])
            ->orderby('mesa','desc')
            ->orderby('cadeira','desc')
            ->first();

        if ($listacposition) $mesasativas = $listacposition->mesa;
        else $mesasativas = $cadblind->qtd_mesas;

        return ["result"=>"S",'total_insc'=>$lista2->count(),'total_game'=>$lista3->count(),'geral_rebuy'=>$geral_rebuy,'geral_addon'=>$geral_addon,'fichas_total'=>$fichas_total,'playerfinalista'=>$player_finalista,'playername'=>$player_name,'playerphoto'=>$player_photo,'playerresultado1'=>$player_resultado1,'playerresultado2'=>$player_resultado2,'mesasativas'=>$mesasativas,'blindaction'=>$blindaction,'msgpremio'=>$msgpremio,'fichas_total_passaport'=>$fichas_total_passaport,'geral_rebuy_passaport'=>$geral_rebuy_passaport,'geral_addon_passaport'=>$geral_addon_passaport,'blind_atual_name'=>$blind_atual_name,'blind_atual_ante'=>$blind_atual_ante,'blind_next_name'=>$blind_next_name,'blind_next_ante'=>$blind_next_ante];
    }

    public function sortearmesas(Request $request){
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

        $lista = BlindPlayer::whereraw('blind_id = '.$request['blind_id'].' AND saiu = 0 AND active = 1') //whereblind_id($request['blind_id'])
            ->get();

        $rand = [];
        $mesas = [];
        $cadeiras = [];
        $cadeirasgeral = [];

        //Log::info('id: '.$item->id);

        $totaljogadores = $lista->count();
        $qtdademesas = $request['qtd_mesas'];
        $jogadoresmesas = $request['jogadores_mesas'];
        $x = 0;
        for ($z=1; $z<=$jogadoresmesas; $z++){
            $cadeirasgeral[$x] = $z;
            $x++;
        }
        shuffle($cadeirasgeral);
        $x = 0;
        if ($totaljogadores<=$jogadoresmesas) {
            for ($z=0; $z<$jogadoresmesas; $z++){
                $rand[$x] = $x;
                $mesas[$x] = 1;
                $cadeiras[$x] = $cadeirasgeral[$z];
                $x++;
                if ($x>=$totaljogadores) break;
            }
        } else {
            $icount = 0;
            for ($i=1; $i<=$qtdademesas; $i++){
                $icount++;
                for ($z=1; $z<=$jogadoresmesas; $z++){
                    $x++;
                    if ($x>=$totaljogadores) break;
                }
                if ($x>=$totaljogadores) break;
            }
            $x = 0;
            $jogadoresmesasCount = $totaljogadores / $icount;
            $jogadoresmesas = ceil($jogadoresmesasCount);
            for ($i=1; $i<=$qtdademesas; $i++){
                for ($z=0; $z<$jogadoresmesas; $z++){
                    $rand[$x] = $x;
                    $mesas[$x] = $i;
                    $cadeiras[$x] = $cadeirasgeral[$z];
                    $x++;
                    if ($x>=$totaljogadores) break;
                }
                if ($x>=$totaljogadores) break;
            }
        }
        shuffle($rand);
        //shuffle($cadeiras);


        $x = 0;
        foreach ($lista as $item){
            //Log::info('id: '.$item->id);
            $cad = BlindPlayer::whereid($item->id)
                ->first();
            $idrand = $rand[$x];
            $cad->mesa = $mesas[$idrand];
            $cad->cadeira = $cadeiras[$idrand];
            $cad->save();
            $x++;
        }

        return ["result"=>"S","message"=>'Sorteo efetuado com sucesso!'];
    }

}
