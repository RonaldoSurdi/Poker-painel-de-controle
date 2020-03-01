@extends('layouts.painelclub')
@php($pag='torn')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        @if ($cad->id>0)
            <h3>Alterar Torneio</h3>
        @else
            <h3>Novo Torneio</h3>
        @endif

        <div class="row">
            <div class="col-sm-12 col-lg-8 col-lg-offset-2">
                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title">
                            Dados do Torneio
                        </h6>
                    </div>

                    <form class="panel-body" id="frm_cad" name="frm_cad" method="POST" action="{{route('club.torn.save')}}">
                        {{ csrf_field() }}
                        <input type="hidden" id="idd" name="idd" value="{{$cad->id}}">

                        <div class="form-group  {!! hasErrorClass($errors,'name') !!}">
                            <div class="input-group">
                                <span class="input-group-addon"> Nome</span>
                                <input type="text" id="name" name="name" value="{{ old('name') ? old('name') : $cad->name }}"
                                       class="form-control text-blue-800" placeholder="Nome do torneio" autofocus required>
                            </div>
                            {!! helpBlock($errors,'name') !!}
                        </div>

                        <div class="content">
                            <label><b>Data do Evento:</b></label>

                            <div class="row {!! hasErrorClass($errors,'date_type') !!}">

                                <div class="col-sm-11 panel mr-10 ml-10">
                                    <div class="panel-heading p-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="date_type" id="date_type1" class="control-primary" required
                                                   value="1" onchange="DateType(this)" @if($cad->type==1) checked="checked" @endif>
                                            Acontece toda semana
                                        </label>
                                    </div>
                                    <div class="panel-body no-padding pb-5" id="radio_week">
                                        <div class="col-sm-12 p-5">
                                            <span class="text-muted"> - Marque os dias da semana que ele acontece</span>
                                            <br>
                                            @for($i=1;$i<=7;$i++)
                                                {!! myCheckboxLine('ckweek'.$i, diaSemana($i-1) ,$cad->WeekCkecked($i)) !!}
                                            @endfor
                                        </div>

                                        <div class="col-sm-4 p-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Horário:</span>
                                                <input type="time" id="week_hour" name="week_hour" class="form-control text-blue-800" placeholder="Hora do evento"
                                                       value="{{old('week_hour') ? old('week_hour') : $cad->week_hour}}">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-sm-11 panel mr-10 ml-10">
                                    <div class="panel-heading p-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="date_type" id="date_type2" class="control-primary" required
                                                   value="2" onchange="DateType(this)" @if($cad->type==2) checked="checked" @endif>
                                            Data Agendada
                                        </label>
                                    </div>
                                    <div class="panel-body no-padding pb-5" id="radio_date">
                                        <div class="col-sm-3">
                                            <button id="btn_day" type="button" class="btn btn-info btn-sm" onclick="AddDate()">Adicionar + dias</button>
                                            <input type="hidden" name="qtd_days" id="qtd_days" value="{{old('qtd_days') ? old('qtd_days') : $cad->qtd_days}}">
                                        </div>

                                        <div class="col-sm-9 panel panel-default">

                                            <table class="table table-responsive table-hover" id="list_days">

                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {!! helpBlock($errors,'date_type') !!}
                        </div>

                        <div class="form-group {!! hasErrorClass($errors,'desc') !!}">
                            <label><b>Descrição:</b></label>
                            <textarea rows="4" cols="5" id="desc" name="desc"  class="form-control text-blue-800" required
                                      placeholder="Escreve aqui informações sobre este evento">{{ old('desc') ? old('desc') : $cad->desc }}</textarea>
                            {!! helpBlock($errors,'desc') !!}
                        </div>

                        {{--<div class="form-group {!! hasErrorClass($errors,'ring_game') !!}">--}}
                            {{--<label><b>Ring Game:</b></label>--}}
                            {{--<textarea rows="4" cols="5" id="ring_game" name="ring_game"  class="form-control text-blue-800"--}}
                                      {{--placeholder="Descreva o ring game">{{ old('ring_game') ? old('ring_game') : $cad->ring_game }}</textarea>--}}
                            {{--{!! helpBlock($errors,'ring_game') !!}--}}
                        {{--</div>--}}

                        <div class="form-group">
                            {!! mycheckbox('ck_insc_app', 'Aceita Inscrição pelo App', $cad->insc_app==1 ? 'checked="checked"' : '' )!!}
                        </div>

                        <div class="form-group cards">
                            {!! mycheckbox('ck_cards', 'Entregar premiação para inscritos', $cad->promo==1 ? 'checked="checked"' : '')!!}

                            <fieldset class="premios">
                                <legend class="text-semibold">Premiação por carta</legend>

                                <div class="row">
                                    @php( $cards=['Dez','Valete','Dama', 'Rei', 'As', 'Curinga'])
                                    @for($i=1;$i<=6;$i++)
                                        <div class="col-sm-4 no-margin">
                                            <div class="thumbnail">
                                                <div class="thumb">
                                                    <img  alt="" style="height: 120px;width: auto;"
                                                            src="{{asset('/my/images/card_'.$i.'.png')}}">
                                                </div>

                                                <div class="caption">
                                                    <h6 class="no-margin-top text-semibold text-center">
                                                        <a href="#" class="text-default">{{$cards[$i-1]}}</a>
                                                    </h6>
                                                    <div class="form-group">
                                                        <label>Descreva o prêmio desta carta:</label>
                                                        <input type="text" name="card{{$i}}_premio" class="form-control text-blue-800" placeholder="Prêmio"
                                                               value="{{ old('card'.$i.'_qtd') ? old('card'.$i.'_qtd') : $cad->carta($i,'P')}}">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Qtd.Fichas:</span>
                                                        <input type="number"  name="card{{$i}}_fichas" class="form-control text-blue-800"
                                                               style="font-size: 18pt" placeholder="" min="1" step="1"
                                                               value="{{ old('card'.$i.'_fichas') ? old('card'.$i.'_fichas') : $cad->carta($i,'F')}}">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Qtd.Premio:</span>
                                                        <input type="number"  name="card{{$i}}_qtd" class="form-control text-blue-800"
                                                               style="font-size: 18pt" placeholder="" min="1" step="1"
                                                               value="{{ old('card'.$i.'_qtd') ? old('card'.$i.'_qtd') : $cad->carta($i,'Q')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                            </fieldset>
                        </div>





                        <a href="{{route('club.torn')}}" class="btn btn-sm btn-info heading-btn pull-left">Voltar para lista</a>

                        <button type="submit" class="btn btn-sm btn-primary heading-btn pull-right" id="btn_save"><i class="icon-floppy-disk"></i> Salvar</button>
                    </form>
                </div>
            </div>



        </div>
    </div>

@endsection

@section('script_footer')
    <script type="text/javascript" src="{{ asset('my/js/torn_edit.js')}}"></script>
    <script>
        @if ($cad->id>0)
            AllDate();
        @else
            $('#qtd_days').val(0);
            AddDate();
        @endif
    </script>
@endsection