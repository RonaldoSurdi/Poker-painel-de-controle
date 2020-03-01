@extends('layouts.painelclub')
@php($pag='msgs')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        @if ($cad->id>0)
            <h3>Alterar Mensagem</h3>
        @else
            <h3>Nova Mensagem</h3>
        @endif

        <div class="row">
            <div class="col-sm-12 col-md-8">


                @if($errors->any())
                    <div class="alert alert-warning alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        <strong>Atenção!</strong>

                        <ul>
                            @foreach( $errors->all() as $erro)
                                <li>{{$erro}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title">
                            Dados da Mensagem
                        </h6>
                    </div>

                    <form class="panel-body" id="frm_cad" name="frm_cad" method="POST" action="{{route('club.msg.save')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="idd" name="idd" value="{{$cad->id}}">

                        <div class="form-group  {!! hasErrorClass($errors,'title') !!}">
                            <div class="input-group">
                                <span class="input-group-addon"> Titulo:</span>
                                <input type="text" id="name" name="title" value="{{ old('title') ? old('title') : $cad->title }}"
                                       class="form-control text-blue-800" placeholder="Titulo da mensagem" autofocus required>
                            </div>
                            {!! helpBlock($errors,'title') !!}
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 no-padding {!! hasErrorClass($errors,'user_type') !!}">
                                <div class="input-group">
                                    <label class="control-label col-sm-12">Enviar para</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="user_type" id="user_type" onchange="CalcUsers()">
                                            <option value="1" selected>Meus seguidores</option>
                                            {{--<option value="2">Usuários próximos</option>--}}
                                            {{--<option value="3">Todos usuários</option>--}}
                                        </select>
                                        {!! helpBlock($errors,'user_type') !!}
                                    </div>
                                </div>
                                <div class="input-group" style="padding: 7px 12px 7px 12px;" id="div_raio">
                                    <span class="input-group-addon">Raio</span>
                                    <select class="form-control" id="radius" name="radius" onchange="CalcUsers()">
                                        @for($i=50;$i<=1000;$i=$i+50)
                                            <option value="{{$i}}">{{$i}} km</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="panel panel-body" id="user_type1">
                                    <div class="media no-margin">
                                        <div class="media-left media-middle">
                                            <i class="icon-users icon-3x text-indigo-400"></i>
                                        </div>

                                        <div class="media-body text-right">
                                            <h3 class="no-margin text-semibold" id="qtd_users">***</h3>
                                            <span class="text-uppercase text-size-mini text-muted">Usuários atingidos</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="panel panel-body" id="user_type1">
                                    <div class="media no-margin">
                                        <div class="media-left media-middle">
                                            <i class="icon-coin-dollar icon-3x text-green-400"></i>
                                        </div>

                                        <div class="media-body text-right">
                                            <h3 class="no-margin text-semibold" id="valor">***</h3>
                                            <span class="text-uppercase text-size-mini text-muted">Valor pelo envio</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <fieldset class="content-group">
                            <legend class="text-bold">Data e hora de envio:</legend>

                            <div class="row">
                                <div class="col-sm-4 p-5  {!! hasErrorClass($errors,'date_day') !!}">
                                    <input type="date" id="date_day" name="date_day" value="{{ old('date_day') ? old('date_day') : $cad->date_day() }}"
                                           class="form-control text-blue-800" placeholder="data do evento" required>
                                    {!! helpBlock($errors,'date_day') !!}
                                </div>
                                <div class="col-sm-2 p-5 {!! hasErrorClass($errors,'date_hour') !!}">
                                    <input type="time" id="date_hour" name="date_hour" value="{{ old('date_hour') ? old('date_hour') : $cad->date_hour() }}"
                                           class="form-control text-blue-800" placeholder="Hora do evento" required>
                                    {!! helpBlock($errors,'date_hour') !!}
                                </div>
                            </div>
                        </fieldset>



                        <fieldset class="content-group">
                            <legend class="text-bold">Conteudo da Mensagem:</legend>

                            <div class="row {!! hasErrorClass($errors,'msg_type') !!}">

                                <div class="col-sm-11 panel mr-10 ml-10">
                                    <div class="panel-heading p-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="msg_type" id="msg_type1" class="control-primary" required
                                                   value="1" onchange="MsgType()"
                                                   @if ((old('msg_type') ? old('msg_type') : $cad->msg_type)==1) checked="checked" @endif>
                                            Enviar texto
                                        </label>
                                    </div>
                                    <div class="panel-body no-padding pb-5" id="div_desc">
                                        <div class="form-group {!! hasErrorClass($errors,'desc') !!}">
                                            <label><b>Texto:</b></label>
                                            <textarea rows="4" cols="5" id="desc" name="desc"  class="form-control text-blue-800"
                                                      placeholder="Escreve aqui o texto da mensagem">{{ old('desc') ? old('desc') : $cad->text }}</textarea>
                                            {!! helpBlock($errors,'desc') !!}
                                        </div>

                                    </div>

                                </div>

                                <div class="col-sm-11 panel mr-10 ml-10">
                                    <div class="panel-heading p-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="msg_type" id="msg_type2" class="control-primary" required
                                                   value="2" onchange="MsgType()"
                                                   @if ((old('msg_type') ? old('msg_type') : $cad->msg_type)==2) checked="checked" @endif>
                                            Enviar Imagem
                                        </label>
                                    </div>
                                    <div class="panel-body no-padding pb-5" id="div_img">

                                        <input id="img1" name="img1" type="file" style="display: none"  accept="image/*" onchange="upload_img()" >
                                        <button type="button" class="btn btn-primary heading-btn" id="btn_img">
                                            <i class="icon-image2"></i> Selecionar Imagem</button>
                                        <hr>
                                        <center>
                                            <img src="{{$cad->img()}}" class="img-responsive img-rounded" id="imgTemp">
                                        </center>

                                    </div>
                                </div>

                            </div>
                            {!! helpBlock($errors,'msg_type') !!}
                        </fieldset>



                        <a href="{{route('club.msg')}}" class="btn btn-sm btn-info heading-btn pull-left">Voltar para lista</a>

                        <button type="submit" class="btn btn-sm btn-primary heading-btn pull-right" id="btn_save"><i class="icon-floppy-disk"></i> Salvar</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4 hidden-sm hidden-xs">
                <center>
                <img src="{{asset('/my/images/exemplo_msg.png')}}" class="img-responsive">
                </center>
            </div>

        </div>
    </div>

@endsection

@section('script_footer')
    <script type="text/javascript" src="{{ asset('my/js/msg_edit.js')}}"></script>
    <script>
        $('#user_type').val('{{ old('user_type') ? old('user_type') : $cad->user_type  }}').trigger('change');
        $('#radius').val('{{ old('radius') ? old('radius') : $cad->radius  }}').trigger('change');

    </script>
@endsection