@extends('layouts.painelclub')
@php($pag='rank')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        @if ($cad->id>0)
            <h3>Alterar Ranking</h3>
        @else
            <h3>Novo Ranking</h3>
        @endif
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title">
                            Dados do Ranking
                        </h6>
                    </div>

                    <form class="panel-body" id="frm_cad" name="frm_cad">
                        {{ csrf_field() }}
                        <input type="hidden" id="idd" name="idd" value="{{$cad->id}}">
                        <input type="hidden" id="steps" name="steps" value="{{$cad->steps}}">

                        <div class="row mt-10">

                            <div class="form-group col-sm-8">
                                <div class="form-group">
                                    <label id="lbl_event">Selecione o Torneio</label>
                                    <select class="select2 event" name='event' id='event' style="width: 100%" onchange="SetTitulo()">
                                        <option></option>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="input-group mb-15">
                            <span class="input-group-addon"> Titulo</span>
                            <input type="text" id="title" name="title" value="{{ $cad->title }}"
                                   class="form-control text-blue-800" placeholder="titulo do Ranking">
                        </div>

                        <a href="{{route('club.rank')}}" class="btn btn-sm btn-info heading-btn pull-left">Voltar para lista</a>

                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                onclick="SalvarDados()" id="btn_save"><i class="icon-floppy-disk"></i> Salvar</button>
                    </form>
                </div>
            </div>

            <div class="col-xs-12" id="div_point">
                <center>
                    <div class="container" style="max-width: 1000px">
                        <div class="col-xs-12 col-sm-4 text-left" >
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="text-semibold panel-title">
                                        <i class="icon-list-numbered"></i> Etapas
                                    </h6>
                                    <div class="heading-elements">
                                        <button type="button" class="btn btn-sm btn-default heading-btn" id="btn_step" onclick="StepsAdd()"><i class="icon-plus3"></i> Adicionar</button>
                                    </div>
                                </div>

                                <div class="list-group no-border" id="list_steps">

                                </div>
                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-8">
                            <form id="frm_foto" name="frm_foto" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" id="rank_id" name="rank_id" value="{{$cad->id}}">
                                <input type="hidden" id="player_id" name="player_id" value="0">
                                <input id="foto1" name="foto1" type="file" style="display: none"  accept="image/*" onchange="upload_img('foto1')" >
                            </form>
                            <center>
                                <div class="panel panel-flat border-top-lg border-top-warning text-left">
                                    <div class="panel-heading p-10">
                                        <h6 class="panel-title">
                                            <i class="icon-users"></i> Etapa <b id="lbl_etapa"></b>
                                        </h6>
                                        <div class="heading-elements">
                                            <button type="button" class="btn btn-primary heading-btn" id="btn_player" onclick="btn_AddPlayer()"><i class="icon-user-plus"></i> Adicionar Jogador</button>
                                            <button type="button" class="btn btn-success heading-btn" id="btn_positions" onclick="Positions()"><i class="icon-medal-first"></i> Classificação</button>
                                        </div>
                                    </div>

                                    <div class="panel-body">

                                        <form name="frm_points" id="frm_points">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="rank_id" id="rank_idPoint" value="{{$cad->id}}">
                                            <input type="hidden" id="step" name="step" value="1">

                                            <table class="table table-hover datatable-responsive">
                                                <thead>
                                                <tr>
                                                    <th class="col-xs-7 col-md-2">Jogadores</th>
                                                    <th class="col-xs-3 col-md-9">Pontuação <span class="hidden-sm hidden-xs">nesta etapa</span></th>
                                                    <th class="col-xs-2">Geral</th>
                                                </tr>
                                                </thead>
                                                <tbody id="lista_players">
                                                    <tr>
                                                        <td align="center" colspan="3">
                                                            <i class="icon-spinner2 spinner"></i> Carregando jogadores...
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tr>
                                                    <td colspan="3">
                                                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                                                onclick="SalvarPontos()" id="btn_point"><i class="icon-floppy-disk"></i> Salvar Pontuação</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>

                                    </div>
                                </div>
                            </center>
                        </div>
                    </div>

                </center>


            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <!-- Vertical form modal -->
    <div id="modal_position" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Classificação</h5>
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


    <script type="text/javascript" src="{{ asset('my/js/rank_cad.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/rank_img.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/rank_step.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/rank_player.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/return_tab.js')}}"></script>
    <script>
    @if ($cad->id==0)
        $('#div_point').hide();
        CarregarEvents();
    @else
        CarregarEvents({{$cad->tournament_id}});

        @if ($cad->steps==0)
            StepsAdd();
        @else
            StepList();
        @endif
    @endif

    </script>
@endsection