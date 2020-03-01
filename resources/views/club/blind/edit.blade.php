@extends('layouts.painelclub')
@php($pag='blind')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('my/css/blind.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front/cropper/cropper.css') }}" />

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/jquery.mask/jquery.mask.min.js')}}"></script>
    <script>
        $( function() {
            $( "#tabs" ).tabs();
        } );
    </script>
@endsection

@section('content')
    <div class="container-fluid">
        <div id="titleblind" class="container">
        @if ($cad->id>0)
            <h3>Configurar Blind</h3>
        @else
            <h3>Adicionar Blind</h3>
        @endif

        </div>
        <div id="tabs" class="row" style="display:none">
            <div class="container">
                <ul>
                    <li id="tab_torn"><a href="#div_torn">Torneio</a></li>
                    <li id="tab_awards"><a href="#div_awards">Premiação</a></li>
                    <li id="tab_rounds"><a href="#div_rounds">Rounds</a></li>
                    <li id="tab_players"><a href="#div_players">Jogadores</a></li>
                    <li id="tab_start"><a id="bt_tab_start" href="#div_start">Blind</a></li>
                    <li id="tab_blind"><a id="btdivblind" href="#div_blind">Tela do Blind</a></li>
                    <li id="tab_podium"><a id="bt_tab_podium" href="#div_podium">Resultado Final</a></li>
                    <li id="tab_ranking"><a id="bt_tab_ranking" href="#div_ranking">Classificação</a></li>
                    <li id="tab_mesas"><a id="bt_tab_mesas" href="#div_mesas">Mesas</a></li>
                </ul>
            </div>

            <div class="col-xs-12" id="div_torn">
                <div class="container">
                        <form id="frm_cad" name="frm_cad">
                            {{ csrf_field() }}
                            <input type="hidden" id="idd" name="idd" value="{{$cad->id}}">
                            <input type="hidden" id="total_insc" name="total_insc" value="0">
                            <input type="hidden" id="total_game" name="total_game" value="0">
                            <input type="hidden" id="geral_rebuy" name="geral_rebuy" value="0">
                            <input type="hidden" id="geral_addon" name="geral_addon" value="0">
                            <input type="hidden" id="club_logo" name="club_logo" value="{{ $cad->clublogo ? $cad->clublogo : asset('my/images/sem_imagem.png') }}">

                            <div class="panel panel-flat border-top-lg border-top-warning text-left">
                                <div class="row mt-15">
                                    <div class="col-sm-9">
                                        <div class="panel-heading p-10">
                                            <h6 class="panel-title">
                                                Dados do Torneio
                                            </h6>
                                        </div>
                                        <div class="panel-body">

                                            <div class="mb-15">
                                                <div class="form-group mb-15">
                                                    <div class="form-group" style="font-size:20px !important">
                                                        <select class="select2 event" name='event' id='event' style="width: 100%" onchange="SetTitulo()" {{ (($cad->id>0)&&($cad->id!==$cad->blind_id)) ? 'disabled' : '' }}>
                                                            <option>Selecione um Torneio</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"> Titulo do Blind</span>
                                                <input type="text" id="title" name="title" value="{{ $cad->title }}"
                                                       class="form-control text-blue-800" placeholder="titulo do Blind" required>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"> Etapa do Torneio</span>
                                                <input type="text" id="steps" name="steps" value="{{ $cad->steps }}"
                                                       class="form-control text-blue-800" placeholder="Etapa do Torneio" required>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"> Mensagem Rotativa</span>
                                                <input type="text" id="premiacao" name="premiacao" value="{{ $cad->premiacao }}"
                                                       class="form-control text-blue-800" placeholder="Mensagem Rotativa da Tela do Blind">
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Número de Mesas</span>
                                                        <input type="text" id="qtd_mesas" name="qtd_mesas" value="{{ number_format($cad->qtd_mesas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Mesas" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Jogadores por Mesa</span>
                                                        <input type="text" id="jogadores_mesas" name="jogadores_mesas" value="{{ number_format($cad->jogadores_mesas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Jogadores/Mesa" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-right">
                                        <img src="{{  $cad->tornamentimg }}" style="height: 350px; width: auto; max-width: 100%">
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-flat border-top-lg border-top-warning text-left">
                                <div class="row mt-15">
                                    <div class="col-sm-6">
                                        <div class="panel-heading p-10">
                                            <h6 class="panel-title">
                                                Fichas/Preços do Torneio
                                            </h6>
                                        </div>

                                        <div class="panel-body">
                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Valor Buy-in</span>
                                                        <input type="text" id="buyin" name="buyin" value="{{ number_format($cad->buyin,2,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Valor do Buy-in em R$" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Recebidas</span>
                                                        <input type="text" id="buyin_fichas" name="buyin_fichas" value="{{ number_format($cad->buyin_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Valor ReBuy</span>
                                                        <input type="text" id="rebuy" name="rebuy" value="{{ number_format($cad->rebuy,2,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Valor do ReBuy em R$" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Recebidas</span>
                                                        <input type="text" id="rebuy_fichas" name="rebuy_fichas" value="{{ number_format($cad->rebuy_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Valor Add-on</span>
                                                        <input type="text" id="addon" name="addon" value="{{ number_format($cad->addon,2,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Valor do Add-on em R$" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Recebidas</span>
                                                        <input type="text" id="addon_fichas" name="addon_fichas" value="{{ number_format($cad->addon_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Round (Bônus/Horário) </span>
                                                        <input type="text" id="bonus_round" name="bonus_round" value="{{ number_format($cad->bonus_round,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Round Limite" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Bônus</span>
                                                        <input type="text" id="bonus_fichas" name="bonus_fichas" value="{{ number_format($cad->bonus_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="panel-heading p-10">
                                            <h6 class="panel-title">
                                                Passaport do Torneio
                                            </h6>
                                        </div>

                                        <div class="panel-body">
                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Valor R$</span>
                                                        <input type="text" id="passaport_valor" name="passaport_valor" value="{{ number_format($cad->passaport_valor,2,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Valor do Passaport em R$" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Buy-in</span>
                                                        <input type="text" id="passaport_buyin_fichas" name="passaport_buyin_fichas" value="{{ number_format($cad->passaport_buyin_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas ReBuy</span>
                                                        <input type="text" id="passaport_rebuy_fichas" name="passaport_rebuy_fichas" value="{{ number_format($cad->passaport_rebuy_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                </div>
                                            </div>

                                            <div class="row mt-15">
                                                <div class="col-sm-6">
                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"> Fichas Add-on</span>
                                                        <input type="text" id="passaport_addon_fichas" name="passaport_addon_fichas" value="{{ number_format($cad->passaport_addon_fichas,0,",",".") }}"
                                                               class="form-control text-blue-800" placeholder="Qtidade de fichas recebidas" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{route('club.blind')}}" class="btn btn-sm btn-info heading-btn pull-left">Voltar para lista</a>

                            <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                    onclick="SalvarDados()" id="btn_save"><i class="icon-floppy-disk"></i> Salvar</button>
                        </form>
                </div>
            </div>

            <div class="col-xs-12" id="div_awards">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title">
                                    <i class="icon-coins"></i> Premiação
                                </h6>
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_award" onclick="btn_AddAward()"><i class="icon-coin-dollar"></i> Adicionar Prêmio</button>
                                </div>
                            </div>

                            <div class="panel-body">

                                <form name="frm_awards" id="frm_awards">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="blind_id" id="blind_idAdward" value="{{$cad->id}}">
                                    <input type="hidden" id="step" name="step" value="1">

                                    <table class="table table-hover datatable-responsive">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-1">Colocação</th>
                                            <th class="col-sm-2">Prêmio em R$</th>
                                            <th class="col-sm-2">Pontos Ranking</th>
                                            <th class="col-sm-4">Outro Prêmio</th>
                                            <th class="col-sm-1">Ação</th>
                                        </tr>
                                        </thead>
                                        <tbody id="lista_awards">
                                        <tr>
                                            <td align="center" colspan="3">
                                                <i class="icon-spinner2 spinner"></i> Carregando premiação...
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                            onclick="SalvarAwards()" id="btn_saveawards"><i class="icon-floppy-disk"></i> Salvar Premiação</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_rounds">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title">
                                    <i class="icon-alarm"></i> Rounds do Torneio
                                </h6>
                                <div class="heading-elements">
                                    <div style="padding:8px; float: left"> Round: </div>
                                    <div style="width: 100px; float: left"><input type="time" name="round_duration_default" id="round_duration_default"
                                                                                  class="form-control text-blue-800" placeholder="-"
                                                                                  min='0:00' max="23:59" value="00:15" ></div>
                                    <div style="padding:8px; float: left"> Break: </div>
                                    <div style="width: 100px; float: left"><input type="time" name="break_duration_default" id="break_duration_default"
                                                                                  class="form-control text-blue-800" placeholder="-"
                                                                                  min='0:00' max="23:59" value="00:10" ></div>
                                    <input type="hidden" id="round_ante_default" name="round_ante_default" value="0">
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_round" onclick="btn_AddRound()"><i class="icon-alarm-add"></i> Adicionar Round</button>
                                    <button type="button" class="btn btn-warning heading-btn" id="btn_break" onclick="btn_AddBreak()"><i class="icon-alarm-cancel"></i> Adicionar Break</button>
                                    <button type="button" class="btn btn-success heading-btn" id="btn_roundauto" onclick="Round_Auto()"><i class="icon-calculator3"></i> <span id="roundauto-tx">Auto adicionar Rounds (ON)</span></button>
                                </div>
                            </div>

                            <div class="panel-body">

                                <form name="frm_rounds" id="frm_rounds">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="blind_id" id="blind_idRound" value="{{$cad->id}}">
                                    <input type="hidden" id="step" name="step" value="1">

                                    <table class="table table-hover datatable-responsive">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-3">Tempo</th>
                                            <th class="col-sm-2">Duração</th>
                                            <th class="col-sm-2">Ante</th>
                                            <th class="col-sm-2">Small Blind</th>
                                            <th class="col-sm-2">Big Blind</th>
                                            <th class="col-sm-2">Rebuy</th>
                                            <th class="col-sm-2">Add-on</th>
                                        </tr>
                                        </thead>
                                        <tbody id="lista_rounds">
                                        <tr>
                                            <td align="center" colspan="3">
                                                <i class="icon-spinner2 spinner"></i> Carregando rounds...
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                            onclick="SalvarRounds()" id="btn_saverounds"><i class="icon-floppy-disk"></i> Salvar Rounds</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_players">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <form id="frm_foto" name="frm_foto" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="blind_id" name="blind_id" value="{{$cad->id}}">
                            <input type="hidden" id="player_id" name="player_id" value="0">
                            <input id="foto1" name="foto1" type="file" style="display: none"  accept="image/*" onchange="upload_img('foto1')" >
                        </form>
                        <div class="progress" style="display: none">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                        <div class="alert" role="alert" style="display: none"></div>

                            <div class="panel panel-flat border-top-lg border-top-warning text-left">
                                <div class="panel-heading p-10">
                                    <h6 class="panel-title">
                                        <i class="icon-users"></i> Jogadores do Torneio
                                    </h6>
                                    <div class="heading-elements">
                                        <button type="button" class="btn btn-primary heading-btn" id="btn_playerbusca" onclick="btn_BuscaPlayer()"><i class="icon-search4"></i> Busca</button>
                                        <button type="button" class="btn btn-primary heading-btn" id="btn_playerapp" onclick="btn_AddPlayerApp()"><i class="icon-users4"></i> Importar Inscritos</button>
                                        <button type="button" class="btn btn-primary heading-btn" id="btn_playeradd" onclick="btn_AddPlayer()"><i class="icon-user-plus"></i> Adicionar Jogador</button>
                                        <button type="button" class="btn btn-primary heading-btn" id="btn_sortear" onclick="Sortear_Mesas()"><i class="icon-calculator3"></i> Sortear Mesas</button>
                                        <button type="button" class="btn btn-success heading-btn" id="btn_sortearauto" onclick="Sortear_Auto()"><i class="icon-calculator3"></i> <span id="sortearauto-tx">Auto posicionar (ON)</span></button>
                                        <div id="btsmesas" style="width: 170px; float: left"></div>
                                    </div>
                                </div>

                                <div class="panel-body">

                                    <form name="frm_players" id="frm_players">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="blind_id" id="blind_idPlayer" value="{{$cad->id}}">
                                        <input type="hidden" id="step" name="step" value="1">

                                        <table class="table table-hover datatable-responsive">
                                            <thead>
                                            <tr>
                                                <th id='playerorder' class="col-xs-3"><i class="icon-arrow-down15 pull-left"></i> Jogador</th>
                                                <th class="col-sm-1">Fichas Buy-in</th>
                                                <th class="col-sm-1 text-center">Insc. APP</th>
                                                <th class="col-sm-1">Fichas Bonus</th>
                                                <th class="col-sm-1 text-center">Passaport</th>
                                                <th class="col-sm-1 text-center">Habilitado</th>
                                                <th class="col-sm-1 text-center">Rebuy</th>
                                                <th class="col-sm-1 text-center">AddOn</th>
                                                <th class="col-sm-1">Mesa</th>
                                                <th class="col-sm-1">Cadeira</th>
                                                <th class="col-sm-1">Eliminar</th>
                                                <th class="col-sm-1">Info</th>
                                            </tr>
                                            </thead>
                                            <tbody id="lista_players">
                                                <tr>
                                                    <td align="center" colspan="3">
                                                        <i class="icon-spinner2 spinner"></i> Carregando jogadores...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br><br>
                                        <div class="input-group mb-15">
                                            <span class="input-group-addon"> Mensagem Rotativa</span>
                                            <input type="text" id="premiacao_players" name="premiacao_players" value="{{ $cad->premiacao }}"
                                                   class="form-control text-blue-800" placeholder="Mensagem Rotativa da Tela do Blind">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-left"
                                                onclick="PlayerPagePos(1)" id="btn_playerprior">Anterior <i class="icon-previous"></i></button>
                                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-left"
                                                onclick="PlayerPagePos(2)" id="btn_playernext"><i class="icon-next"></i> Próximo</button>
                                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                                onclick="SalvarPlayer()" id="btn_player"><i class="icon-floppy-disk"></i> Salvar Alterações</button>
                                    </form>

                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_start">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title">
                                    <i class="icon-display"></i> Administração do Blind
                                </h6>
                            </div>
                            <div class="panel-body">
                                <div class="sblind text-center">
                                    <h2 id="blindstatus" class="blindstat">TELA DO BLIND</h2>
                                    <button type="button" class="btn2 heading-btn" id="btn_blindscreen" onclick="abrir_janela_blind()" style="border-color:#00A0E3">ABRIR TELA DO BLIND</button><br>
                                    <button type="button" class="btn2 heading-btn" id="btn_roundplay" onclick="abrir_BlindIniciar()" style="border-color:#009847"><img src="/my/images/blind/bt-start.png"> INICIAR BLIND</button><br>
                                    <button type="button" class="btn2 heading-btn" id="btn_roundbreak" onclick="abrir_BlindPausa()" style="border-color:#E31E25"><img src="/my/images/blind/bt-pause.png"> PAUSAR BLIND</button><br>
                                    <button type="button" class="btn2 heading-btn" id="btn_podium" onclick="abrir_Podium()" style="border-color:#e6eb0c">RESULTADO FINAL</button><br>
                                    <button type="button" class="btn2 heading-btn" id="btn_ranking" onclick="abrir_Ranking()" style="border-color:#898988">CLASSIFICAÇÃO</button><br>
                                    <button type="button" class="btn2 heading-btn" id="btn_mesas" onclick="abrir_Mesas()" style="border-color:#D2885B">MESAS DO TORNEIO</button><br><br>
                                    <button type="button" class="btn3 heading-btn" id="btn_prior" onclick="abrir_BlindPrior()"><img src="/my/images/blind/bt-prior.png"><br>VOLTAR BLIND</button>
                                    <button type="button" class="btn3 heading-btn" id="btn_next" onclick="abrir_BlindNext()"><img src="/my/images/blind/bt-next.png"><br>PRÓXIMO BLIND</button>
                                    <br><br><br>
                                    <img src="/my/images/blind/poker_clubs.png" style="width: 200px; height: auto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_blind">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title" style="font-size: 20px">
                                    <i class="icon-alarm"></i> Blind do Torneio
                                </h6>
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_fulscreen" onclick="entrarFullScreen(1)"><i class="icon-users4"></i> Tela Cheia</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="blindscreen" class="cblind">
                                    <div class="cblind_1">
                                        <div class="text1" id="blind_torneio"></div>
                                    </div>
                                    <div class="cblind_2">
                                        <div class="text1" id="cblfont2">TEMPO DO BLIND ATUAL</div>
                                    </div>
                                    <div class="cblind_3">
                                        <div class="text1" id="blind_tempo"></div>
                                    </div>
                                    <div class="cblind_4">
                                        <div class="text1" id="blind_progress"></div>
                                    </div>
                                    <div class="cblind_5">
                                        <div class="text1" id="cblfont5">PRÓXIMO BREAK <span id="blind_nextbreak"></span></div>
                                    </div>
                                    <div class="cblind_6">
                                        <div class="text1">
                                            <div class="text11" id="cblfont61">BLIND ATUAL</div>
                                            <div class="text12" id="blind_blind"></div>
                                        </div>
                                        <div class="text2">
                                            <div class="text21" id="cblfont62">ANTE</div>
                                            <div class="text22" id="blind_ante"></div>
                                        </div>
                                    </div>
                                    <div class="cblind_7">
                                        <div class="text1" id="cblfont7">PRÓXIMO BLIND <span id="blind_nextblind"></span> ANTE <span id="blind_nextante"></span></div>
                                    </div>
                                    <div class="cblind_8">
                                        <div class="text1" id="blind_premio"></div>
                                    </div>
                                    <div id="blind_logo1" class="cblind_9">
                                    </div>
                                    <div id="blind_logo2" class="cblind_10">
                                    </div>
                                    <div class="cblind_11">
                                        <div class="text1" id="cblfont11">INSCRITOS</div>
                                        <div class="text2" id="blind_insc"></div>
                                    </div>
                                    <div class="cblind_12">
                                        <div class="text1" id="cblfont12">REBUYS</div>
                                        <div class="text2" id="blind_rebuys"></div>
                                    </div>
                                    <div class="cblind_13">
                                        <div class="text1" id="cblfont13">ADD-ON</div>
                                        <div class="text2" id="blind_addon"></div>
                                    </div>
                                    <div class="cblind_14">
                                        <div class="text1" id="cblfont14">NÍVEL</div>
                                        <div class="text2" id="blind_nivel"></div>
                                    </div>
                                    <div class="cblind_15">
                                        <div class="text1" id="cblfont15">MESAS</div>
                                        <div class="text2" id="blind_mesas"></div>
                                    </div>
                                    <div class="cblind_16">
                                        <div class="text1" id="cblfont16">MÉDIA</div>
                                        <div class="text2" id="blind_media"></div>
                                    </div>
                                    <div class="cblind_17" id="blind_fnd">
                                        <img src="/my/images/blind/blind-background.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_podium">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title" style="font-size: 20px">
                                    <i class="icon-alarm"></i> Resultado Final do Torneio
                                </h6>
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-success heading-btn" id="btn_baixar_podium" onclick="btn_BlindBaixar(2)"><i class="icon-file-download"></i> Baixar Imagem Desktop</button>
                                    <button type="button" class="btn btn-success heading-btn" id="btn_baixar_podium2" onclick="btn_BlindBaixar(21)"><i class="icon-file-download"></i> Baixar Imagem Mobile</button>
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_fulscreen_podium" onclick="entrarFullScreen(2)"><i class="icon-users4"></i> Tela Cheia</button>
                                    <button type="button" class="btn btn-secundary heading-btn" id="btn_close_podium" onclick="closeScreen(2)"><i class="icon-close2"></i></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="podiumscreen" class="cpodium">
                                    <div class="cpodium_1">
                                        <div class="text1" id="podium_torneio"></div>
                                    </div>
                                    <div id="podium_logo1" class="cpodium_2">
                                    </div>
                                    <div id="podium_logo2" class="cpodium_3">
                                    </div>
                                    <div id="podium_palco" class="cpodium_6">
                                        <div class="pos1" id="podium_pos1">
                                            <div class="foto1" id="podium_foto1">
                                            </div>
                                        </div>
                                        <div class="pos2" id="podium_pos2">
                                            <div class="foto2" id="podium_foto2">
                                            </div>
                                        </div>
                                        <div class="pos3" id="podium_pos3">
                                            <div class="foto3" id="podium_foto3">
                                            </div>
                                        </div>
                                        <div class="back">
                                            <img src="/my/images/blind/blind-podium.png">
                                            <div class="namegam">
                                                <div class="text" id="podium_gam2"></div>
                                                <div class="text" id="podium_gam1"></div>
                                                <div class="text" id="podium_gam3"></div>
                                            </div>
                                            <div class="premgam">
                                                <div class="text" id="podium_prm2"></div>
                                                <div class="text" id="podium_prm1"></div>
                                                <div class="text" id="podium_prm3"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cpodium_4">
                                        <div class="text1" id="pdlfont58"></div>
                                        <div class="text2" id="pd2font58"></div>
                                        <div class="text3" id="pd3font58"></div>
                                        <div class="text4" id="pd4font58"></div>
                                    </div>
                                    <div class="cpodium_5">
                                        <img src="/my/images/blind/blind-fim-background.jpg">
                                    </div>
                                </div>
                                <div id="podiumscreen2" class="cpodium2">
                                    <div class="cpodium2_1">
                                        <div class="text1" id="podium2_torneio"></div>
                                    </div>
                                    <div id="podium2_logo1" class="cpodium2_2">
                                    </div>
                                    <div id="podium2_logo2" class="cpodium2_3">
                                    </div>
                                    <div id="podium2_palco" class="cpodium2_6">
                                        <div class="pos1" id="podium2_pos1">
                                            <div class="foto1" id="podium2_foto1">
                                            </div>
                                        </div>
                                        <div class="pos2" id="podium2_pos2">
                                            <div class="foto2" id="podium_foto2">
                                            </div>
                                        </div>
                                        <div class="pos3" id="podium2_pos3">
                                            <div class="foto3" id="podium2_foto3">
                                            </div>
                                        </div>
                                        <div class="back">
                                            <img src="/my/images/blind/blind-podium-mob.png">
                                            <div class="namegam">
                                                <div class="text" id="podium2_gam2"></div>
                                                <div class="text" id="podium2_gam1"></div>
                                                <div class="text" id="podium2_gam3"></div>
                                            </div>
                                            <div class="premgam">
                                                <div class="text" id="podium2_prm2"></div>
                                                <div class="text" id="podium2_prm1"></div>
                                                <div class="text" id="podium2_prm3"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cpodium2_4">
                                        <div class="text1" id="pd2lfont58"></div>
                                        <div class="text2" id="pd22font58"></div>
                                    </div>
                                    <div class="cpodium2_5">
                                        <img src="/my/images/blind/blind-fim-background-mob.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_ranking">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title" style="font-size: 20px">
                                    <i class="icon-alarm"></i> Classificação do Torneio
                                </h6>
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-success heading-btn" id="btn_baixar_ranking" onclick="btn_BlindBaixar(3)"><i class="icon-file-download"></i> Baixar Imagem</button>
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_fulscreen_ranking" onclick="entrarFullScreen(3)"><i class="icon-users4"></i> Tela Cheia</button>
                                    <button type="button" class="btn btn-secundary heading-btn" id="btn_close_ranking" onclick="closeScreen(3)"><i class="icon-close2"></i></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="rankingscreen" class="cranking">
                                    <div class="cranking_1">
                                        <div class="text1" id="ranking_torneio"></div>
                                    </div>
                                    <div id="ranking_logo1" class="cranking_2">
                                    </div>
                                    <div id="ranking_logo2" class="cranking_3">
                                    </div>
                                    <div class="cranking_4">
                                        <div class="text1" id="rklfont58"></div>
                                        <div class="text2" id="rk2font58"></div>
                                        <div class="text3" id="rk3font58"></div>
                                        <div class="text4" id="rk4font58"></div>
                                    </div>
                                    <div class="cranking_5">
                                        <img src="/my/images/blind/blind-fim-background.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="div_mesas">
                <div class="container">
                    <div class="col-xs-12 col-sm-12">
                        <div class="panel panel-flat border-top-lg border-top-warning text-left">
                            <div class="panel-heading p-10">
                                <h6 class="panel-title" style="font-size: 20px">
                                    <i class="icon-alarm"></i> Mesas do Torneio
                                </h6>
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-success heading-btn" id="btn_baixar_mesas" onclick="btn_BlindBaixar(4)"><i class="icon-file-download"></i> Baixar Imagem</button>
                                    <button type="button" class="btn btn-primary heading-btn" id="btn_fulscreen_mesas" onclick="entrarFullScreen(4)"><i class="icon-users4"></i> Tela Cheia</button>
                                    <button type="button" class="btn btn-secundary heading-btn" id="btn_close_mesas" onclick="closeScreen(4)"><i class="icon-close2"></i></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="mesasscreen" class="cmesas">
                                    <div class="cmesas_1">
                                        <div class="text1" id="mesas_torneio"></div>
                                    </div>
                                    <div id="mesas_logo1" class="cmesas_2">
                                    </div>
                                    <div id="mesas_logo2" class="cmesas_3">
                                    </div>
                                    <div class="cmesas_4">
                                        <div class="text1" id="mslfont58"></div>
                                        <div class="text2" id="ms2font58"></div>
                                        <div class="text3" id="ms3font58"></div>
                                        <div class="text4" id="ms4font58"></div>
                                    </div>
                                    <div class="cmesas_5">
                                        <img src="/my/images/blind/blind-fim-background.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script_footer')
    <!-- Modal -->
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Selecione a Imagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" src="/my/images/sem_avatar.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="crop">Enviar foto</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Vertical form modal -->
    <div id="modal_mesas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Mesas do Torneio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-hover datatable-responsive">
                        <tbody id="lista_mesas">

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Vertical form modal -->
    <div id="modal_position" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Classificação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-hover datatable-responsive">
                        <tbody id="lista_positions">

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /vertical form modal -->

    <script src="{{ asset('front/jssocials.js')}}"></script>
    <script src="{{ asset('front/html2canvas.js')}}"></script>
    <script src="{{ asset('front/cropper/cropper.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/blind_player.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/return_tab.js')}}"></script>
    <script>

    $('#steps').mask("#.##0", {reverse: true});
    $('#qtd_mesas').mask("#.##0", {reverse: true});
    $('#jogadores_mesas').mask("#.##0", {reverse: true});
    $('#buyin').mask("#.##0,00", {reverse: true});
    $('#buyin_fichas').mask("#.##0", {reverse: true});
    $('#rebuy').mask("#.##0,00", {reverse: true});
    $('#rebuy_fichas').mask("#.##0", {reverse: true});
    $('#addon').mask("#.##0,00", {reverse: true});
    $('#addon_fichas').mask("#.##0", {reverse: true});
    $('#bonus_round').mask("#.##0", {reverse: true});
    $('#bonus_fichas').mask("#.##0", {reverse: true});
    $('#passaport_valor').mask("#.##0,00", {reverse: true});
    $('#passaport_buyin_fichas').mask("#.##0", {reverse: true});
    $('#passaport_rebuy_fichas').mask("#.##0", {reverse: true});
    $('#passaport_addon_fichas').mask("#.##0", {reverse: true});


    @if ($cad->id==0)
        initWin(0,0);
    @else
        initWin('{{$cad->tournament_id}}','{{$cad->status}}','{{$cad->blind_action}}','{{$cad->tournament_id}}');
    @endif

    </script>
@endsection