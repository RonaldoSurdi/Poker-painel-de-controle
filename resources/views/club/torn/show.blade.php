@extends('layouts.painelclub')
@php($pag='torn')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('front/jssocials.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front/jssocials-theme-flat.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('my/css/torneiosadd.css') }}" />
    <style>
        .titleban {
            font-size: 17px !important;
            line-height: 14px !important;
        }
    </style>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Visualizar Torneio</h3>
        <div class="row">
            <div class="col-md-7" id="paneCad">
                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title"> <i class="icon-profile"></i> Dados de Cadastro</h6>
                        <div class="heading-elements">
                            <a href="{{route('club.torn')}}" class="btn btn-sm btn-info heading-btn">Voltar para lista</a>
                            <a href="{{route('club.torn.edit',['id'=>$cad->id])}}" class="btn btn-primary heading-btn" id="btn_cad">Alterar Cadastro</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <h3><b>{{$cad->name}}</b></h3>
                        <hr>
                        <h5><b>Data do evento:</b> {{$cad->data()}}</h5>
                        @if ($cad->type==1)
                            {!! $cad->week() !!}
                        @else
                            @foreach($cad->dates as $day)
                                {{ date('d/m/Y H:i', strtotime($day->data) ) }}<BR>
                            @endforeach
                        @endif
                        {{--<hr>--}}
                        {{--<h5><b>Ring Game:</b></h5>--}}
                        {{--{!! nl2br($cad->ring_game) !!}--}}

                        <hr>
                        <h5><b>Descrição do evento:</b></h5>
                        {!! nl2br($cad->desc) !!}
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <h5><b>Inscrição pelo App:</b> {{$cad->inscricao()}}</h5>
                            </div>
                            @if ($cad->insc_app==1)
                                <div class="col-sm-6">
                                    <h5><b>Premiação para Inscritos:</b> {{$cad->Promo()}}</h5>
                                </div>
                                @if ($cad->promo==1)
                                    <div class="col-sm-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">Cartas e seus prêmios</h6>
                                            </div>

                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th><b>Carta</b></th>
                                                    <th><b>Prêmio</b></th>
                                                    <th><b>Fichas</b></th>
                                                    <th><b>Qtd</b></th>
                                                    <th><b>Saldo</b></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($cad->cards as $card)
                                                    <tr>
                                                        <td>
                                                            <a href="{{asset('/my/images/card_'.$card->card.'.png')}}" data-popup="lightbox">
                                                                <img src="{{asset('/my/images/card_'.$card->card.'.png')}}" alt="" class="img-rounded img-sm">
                                                            </a>
                                                            <b> {{$card->carta()}} </b>
                                                        </td>
                                                        <td>{{$card->premium}}</td>
                                                        <td>{{$card->fichas}}</td>
                                                        <td>{{$card->qtd}}</td>
                                                        <td>{{$card->saldo()}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4">
                                                            <h5>Não foi encontrada carta configurada</h5>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>

                    </div>
                </div>

            </div>
            <div class="col-md-5" id="paneImg">

                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title"> <i class="icon-image2"></i> Banner:</h6>
                        <div class="heading-elements">
                        @if (($cad->img()==="https://www.pokerclubsapp.com.br/my/images/sem_imagem.png")||($cad->img()==="https://pokerclubsapp.com.br/my/images/sem_imagem.png"))
                            <!-- <button type="button" class="btn btn-success heading-btn" id="btn_imgNew">Criar Banner</button>
                                <button type="button" class="btn btn-primary heading-btn" id="btn_img">Importar</button> -->
                            @else
                                <a type="button" class="btn btn-success heading-btn" id="btn_imgDown" href="{{$cad->img()}}" download="{{$cad->name}}">Baixar Banner</a>
                                <button type="button" class="btn btn-danger heading-btn" id="btn_imgDel" onclick="FolderDel()">Excluir</button>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body p-5">
                        @if (($cad->img()==="https://www.pokerclubsapp.com.br/my/images/sem_imagem.png")||($cad->img()==="https://pokerclubsapp.com.br/my/images/sem_imagem.png"))
                            <div class="modelos" id="modelSet">
                                <h6 class="panel-title">Crie um banner apartir de um modelo ou importe:</h6>
                                <div id="mod1" class="item disable">
                                    <img src="{{ asset('/storage/tournaments-models/0001-modelo-banner-torneio.jpg') }}" class="img-responsive img-rounded">
                                </div>
                                <div id="mod2" class="item disable">
                                    <img src="{{ asset('/storage/tournaments-models/0002-modelo-banner-torneio.jpg') }}" class="img-responsive img-rounded">
                                </div>
                                <div id="mod3" class="item disable">
                                    <img src="{{ asset('/storage/tournaments-models/0003-modelo-banner-torneio.jpg') }}" class="img-responsive img-rounded">
                                </div>
                                <div class="bar">
                                    <a class="btn btn-info heading-btn disabled" id="btn_imgView" data-popup="lightbox">VISUALIZAR</a> <a class="btn btn-success heading-btn" id="btn_imgNew">CRIAR NOVO BANNER</a><br>
                                    - OU -<br>
                                    <a class="btn btn-success heading-btn" id="btn_img">IMPORTAR ARQUIVO</a>
                                </div>
                            </div>
                            <div class="modelos" id="modelSet1">
                                <div class="col-md-6 modelFrameImg">
                                    <div id="modelImage" class="modelImg1">
                                        <div id="modelTorneioPremio"></div>
                                        <div id="modelTorneioName"><div class="boxName"><div class="boxText"></div></div></div>
                                        <div id="modelTorneioDataEvento"></div>
                                        <div id="modelTorneioEstrutura1"></div>
                                        <div id="modelTorneioEstrutura2"></div>
                                        <div id="modelTorneioEstrutura3"></div>
                                        <div id="modelTorneioEstrutura4"></div>
                                        <div id="modelTorneioEstrutura5"></div>
                                        <div id="modelTorneioContato"></div>
                                        <div id="modelTorneioLogo"></div>
                                        <img id="modelImg" src="" class="img-responsive img-rounded">
                                    </div>
                                </div>
                                <div class="col-md-6 text-left">
                                    <h6 class="panel-title">Edite as informações do modelo selecionado:</h6>
                                    <h3 class="titleban">Dados do Torneio</h3>
                                    <div class="panel panel-flat border-top-lg border-top-warning">
                                        <div class="input-group">
                                            <span class="input-group-addon"> Nome do Torneio</span>
                                            <input type="text" id="banner_name" name=banner_"name" value="{{ strtoupper($cad->name) }}"
                                                   class="form-control text-blue-800" placeholder="Nome do Torneio" autofocus required>
                                        </div>
                                        <br>
                                        <div class="input-group text-blue-800">
                                            <span class="input-group-addon"> Premiação</span>
                                            <input type="text" id="banner_premio" name="banner_premio" value="100K"
                                                   class="form-control text-blue-800" placeholder="Valor da Premiação" autofocus required>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon"> Data/Período</span>
                                            <textarea rows="3" cols="5" id="banner_dataevento" name="banner_dataevento" class="form-control text-blue-800" required
                                                      placeholder="Data/Período do Torneio"></textarea>
                                        </div>
                                    </div>
                                    <h3 class="titleban">Informações do Clube</h3>
                                    <div class="panel panel-flat border-top-lg border-top-warning">
                                        <div class="input-group">
                                            <span class="input-group-addon"> Contatos</span>
                                            <textarea rows="3" cols="5" id="banner_informacoes" name="banner_informacoes" class="form-control text-blue-800" required
                                                      placeholder="Informações do Clube"></textarea>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon"> Logo</span>
                                            <input type="file" name="banner_logo" id="banner_logo" accept="image/png, image/jpeg" class="logoFile">
                                            <input type="text" name="banner_file" id="banner_file" class="form-control text-blue-800" placeholder="Logo padrão selecionada" readonly="readonly">
                                            <input type="button" class="btn btn-success heading-btn" id="btn_imgLogo" value="SELECIONAR ARQUIVO">
                                            <a class="btn btn-info heading-btn" id="btn_imgClear">UTILIZAR LOGO PADRÃO</a>
                                        </div>
                                    </div>
                                    <h3 class="titleban">Estrutura</h3>
                                    <div class="panel panel-flat border-top-lg border-top-warning">
                                        <div class="input-group">
                                            <span class="input-group-addon"> Buyin</span>
                                            <input type="text" id="banner_buyin" name="banner_buyin" value="R$ 50,00"
                                                   class="form-control text-blue-800" placeholder="Valor Buyin" autofocus required>
                                            <span class="input-group-addon"> Bônus</span>
                                            <input type="text" id="banner_bonus" name="banner_bonus" value="R$ 10,00"
                                                   class="form-control text-blue-800" placeholder="Valor Bônus" autofocus required>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon"> Rebuy</span>
                                            <input type="text" id="banner_rebuy" name="banner_rebuy" value="R$ 50,00"
                                                   class="form-control text-blue-800" placeholder="Valor Rebuy" autofocus required>
                                            <span class="input-group-addon"> Add On</span>
                                            <input type="text" id="banner_addon" name="banner_addon" value="R$ 50,00"
                                                   class="form-control text-blue-800" placeholder="Valor Add On" autofocus required>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon"> Observações</span>
                                            <textarea rows="2" cols="5" id="banner_complementoestrutura" name="banner_complementoestrutura" class="form-control text-blue-800" required
                                                      placeholder="Observações"></textarea>
                                        </div>
                                    </div>
                                    <div class="bar2">
                                        <a class="btn btn-danger heading-btn" id="btn_imgBack">VOLTAR</a>
                                        <a class="btn btn-info heading-btn" id="btn_imgDownAlta">BAIXAR IMAGEM</a>
                                        <a class="btn btn-success heading-btn" id="btn_imgCreate">FINALIZAR BANNER</a><br>
                                    </div>
                                </div>
                            </div>
                        @else
                            <center>
                                <img src="{{$cad->img()}}" class="img-responsive img-rounded">
                            </center>
                        @endif
                        <form id="frm_img" name="frm_img" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="torn_id" name="torn_id" value="{{$cad->id}}">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                            <input id="img1" name="img1" type="file" style="display: none"  accept="image/*" onchange="upload_img()" >
                        </form>
                    </div>
                    <div id="share" @if (($cad->img()==="https://www.pokerclubsapp.com.br/my/images/sem_imagem.png")||($cad->img()==="https://pokerclubsapp.com.br/my/images/sem_imagem.png")) style="display: none" @endif></div>
                </div>
            </div>

            @if ($cad->insc_app==1)
                <div class="col-md-8 col-md-offset-2 col-sm-12">
                    <!-- Media library -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h6 class="panel-title text-semibold">Lista de inscritos no torneio:
                            </h6>
                            <div class="heading-elements hidden-print">

                                <a href="{{route('club.torn.insc',['id'=>$cad->id])}}" type="button" class="btn btn-primary heading-btn" target="_blank">
                                    <i class="icon-printer"></i> Imprimir</a>
                            </div>
                        </div>

                        <table class="table table-hover datatable-responsive">
                            <thead>
                            <tr>
                                <th class="col-sm-7">Nome do Jogador</th>
                                <th class="col-sm-7">Data Evento</th>
                                <th class="col-sm-1">Carta</th>
                                <th class="col-sm-4">Prêmio</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($cad->subscription as $item)
                                <tr>
                                    <td>
                                        {{$item->user->name}}
                                    </td>
                                    <td>
                                        {{ date('d/m/Y', strtotime( $item->date_event))}}
                                    </td>
                                    <td>
                                        <?php
                                        if ($item->card){
                                            if ($item->card->card==1) echo 'Dez';
                                            elseif ($item->card->card==2) echo 'Valete';
                                            elseif ($item->card->card==3) echo 'Dama';
                                            elseif ($item->card->card==4) echo 'Rei';
                                            elseif ($item->card->card==5) echo 'Ás';
                                            elseif ($item->card->card==6) echo 'Curinga';
                                            else echo '--';
                                        }else echo '--';
                                        ?>
                                    </td>
                                    <td>
                                        @if ($item->card)
                                            {{ $item->card->premium}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        Não foi encontado inscritos neste torneio.
                                    </td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                    <!-- /media library -->
                </div>
            @endif
        </div>

    </div>

@endsection

@section('script_footer')
    <script type="text/javascript" src="{{ asset('my/js/torn_img.js')}}"></script>
    <script src="{{ asset('front/jssocials.min.js')}}"></script>
    <script src="{{ asset('front/html2canvas.min.js')}}"></script>
    <script>
        $("#share").jsSocials({
            showLabel: false,
            showCount: false,
            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
        });
        var modselect = 0;
        $("#mod1").click(function(e){
            e.preventDefault();
            modselect = 1;
            if ($("#btn_imgView").hasClass("disabled")) $("#btn_imgView").removeClass("disabled");
            $("#btn_imgView").prop("href", "{{ asset('/storage/tournaments-models/0001-modelo-banner-torneio-amplia.jpg') }}");
            $("#mod1").css("border", "3px solid #1a7c00");
            $("#mod2").css("border", "3px solid #ccc");
            $("#mod3").css("border", "3px solid #ccc");
            if ($("#mod1").hasClass("disable")) $("#mod1").removeClass("disable");
            if (!$("#mod2").hasClass("disable")) $("#mod2").addClass("disable");
            if (!$("#mod3").hasClass("disable")) $("#mod3").addClass("disable");
        });
        $("#mod2").click(function(e){
            e.preventDefault();
            modselect = 2;
            if ($("#btn_imgView").hasClass("disabled")) $("#btn_imgView").removeClass("disabled");
            $("#btn_imgView").prop("href", "{{ asset('/storage/tournaments-models/0002-modelo-banner-torneio-amplia.jpg') }}");
            $("#mod2").css("border", "3px solid #1a7c00");
            $("#mod1").css("border", "3px solid #ccc");
            $("#mod3").css("border", "3px solid #ccc");
            if ($("#mod2").hasClass("disable")) $("#mod2").removeClass("disable");
            if (!$("#mod1").hasClass("disable")) $("#mod1").addClass("disable");
            if (!$("#mod3").hasClass("disable")) $("#mod3").addClass("disable");
        });
        $("#mod3").click(function(e){
            e.preventDefault();
            modselect = 3;
            if ($("#btn_imgView").hasClass("disabled")) $("#btn_imgView").removeClass("disabled");
            $("#btn_imgView").prop("href", "{{ asset('/storage/tournaments-models/0003-modelo-banner-torneio-amplia.jpg') }}");
            $("#mod3").css("border", "3px solid #1a7c00");
            $("#mod1").css("border", "3px solid #ccc");
            $("#mod2").css("border", "3px solid #ccc");
            if ($("#mod3").hasClass("disable")) $("#mod3").removeClass("disable");
            if (!$("#mod1").hasClass("disable")) $("#mod1").addClass("disable");
            if (!$("#mod2").hasClass("disable")) $("#mod2").addClass("disable");
        });
        $("#mod1").dblclick(function(e){
            e.preventDefault();
            $("#btn_imgView").trigger('click');
        });
        $("#mod2").dblclick(function(e){
            e.preventDefault();
            $("#btn_imgView").trigger('click');
        });
        $("#mod3").dblclick(function(e){
            e.preventDefault();
            $("#btn_imgView").trigger('click');
        });
        //var dataeventoval = "";

        $("#btn_imgNew").click(function(e){
            e.preventDefault();
            if (modselect === 0) {
                modal({
                    type: 'warning',
                    title: 'AVISO',
                    text: "<p style='text-align:center'>Selecione um modelo de banner, e tente novamente...</p>",
                    center: true,
                    closeClick: true,
                    buttonText: {
                        ok: 'FECHAR JANELA'
                    }
                });
            } else {
                var dataeventoval = "";/*$cad->data()*/
                var dataeventoval2 = "1";
                @if ($cad->type==1)
                    @for($i=1;$i<=7;$i++)
                        @if ($cad->WeekCkecked($i)<>'')
                        if (dataeventoval2!=="1") {
                            dataeventoval += dataeventoval2+", ";
                        }
                        dataeventoval2 = "{{ strtoupper(diaSemanaPlural($i-1)) }}";
                        @endif
                    @endfor
                    if (dataeventoval.substr(dataeventoval.length-2, 2)===', ') {
                        dataeventoval = dataeventoval.substr(0, dataeventoval.length-2);
                        dataeventoval += ' E ' + dataeventoval2;
                    } else {
                        dataeventoval += dataeventoval2;
                    }
                    if (modselect === 1) {
                        dataeventoval = 'TODAS ÀS\n' + dataeventoval + '\n';
                    } else {
                        dataeventoval = 'TODAS ' + dataeventoval + ' ÀS ';

                    }
                    @if (date('i',strtotime($cad->week_hour))!=='00')
                        dataeventoval += "{{ date('H:i', strtotime($cad->week_hour)) }}H";
                    @else
                        dataeventoval += "{{ date('H', strtotime($cad->week_hour)) }}H";
                    @endif
                @else
                    @if (count($cad->dates)<=1)
                        @foreach($cad->dates as $day)
                            if (modselect === 1) {
                                dataeventoval += "DIA {{ date('d', strtotime($day->data) ) }}\nDE {{ strtoupper(mesSemanaEx(intval(date('m',strtotime($day->data)))-1)) }}\n";
                            } else {
                                dataeventoval += "DIA {{ date('d', strtotime($day->data) ) }} DE {{ strtoupper(mesSemanaEx(intval(date('m',strtotime($day->data)))-1)) }}, ÀS ";
                            }
                            @if (date('i',strtotime($day->data))!=='00')
                                dataeventoval += "{{ date('H:i', strtotime($day->data) ) }}H";
                            @else
                                dataeventoval += "{{ date('H', strtotime($day->data) ) }}H";
                            @endif
                        @endforeach
                    @else
                        if (modselect === 1) dataeventoval += "NOS DIAS\n";
                        else dataeventoval += "NOS DIAS ";
                        var idd = 0;
                        var idt = parseInt('{{count($cad->dates)}}');
                        @foreach($cad->dates as $day)
                            idd++;
                            if (idd>1) {
                                if (modselect === 1) dataeventoval += "\n";
                                else {
                                    if (idd===idt) dataeventoval += " E ";
                                    else dataeventoval += ", ";
                                }
                            }
                            dataeventoval += "{{ date('d/m', strtotime($day->data) ) }} ÀS ";
                            @if (date('i',strtotime($day->data))!=='00')
                                dataeventoval += "{{ date('H:i', strtotime($day->data) ) }}H";
                            @else
                                dataeventoval += "{{ date('H', strtotime($day->data) ) }}H";
                            @endif
                        @endforeach
                    @endif
                @endif
                $("#banner_complementoestrutura").html('REBUYS ATÉ O 8 BLIND)\nBLINDS: 15 MINUTOS');
                $("#banner_dataevento").html(dataeventoval);
                $("#banner_informacoes").html('{{$cad->clubdados}}');
                $('#modelTorneioLogo').css('backgroundImage','url("{{$cad->clublogo}}")');
                if (modselect===1) $("#modelImg").prop("src", "{{ asset('/storage/tournaments-models/0001-modelo-banner-torneio-base.jpg') }}");
                else if (modselect===2) $("#modelImg").prop("src", "{{ asset('/storage/tournaments-models/0002-modelo-banner-torneio-base.jpg') }}");
                else if (modselect===3) $("#modelImg").prop("src", "{{ asset('/storage/tournaments-models/0003-modelo-banner-torneio-base.jpg') }}");
                $("#modelSet").css("display", "none");
                $("#modelSet1").css("display", "block");
                $("#paneCad").css("display", "none");
                $("#paneImg").removeClass("col-md-5");
                $(".boxText").html($("#banner_name").val());
                if (modselect === 1) {
                    $("#modelTorneioPremio").html('20K');
                    $("#banner_premio").val('20K');
                } else {
                    $("#modelTorneioPremio").html('100K');
                    $("#banner_premio").val('100K');
                }
                setTextDataEvento();
                $("#modelTorneioEstrutura1").html('R$ 50,00');
                $("#modelTorneioEstrutura2").html('R$ <span>10,00</span>');
                $("#modelTorneioEstrutura3").html('R$ 50,00');
                $("#modelTorneioEstrutura4").html('R$ 50,00');
                $("#modelTorneioEstrutura5").html('REBUYS ATÉ O 8 BLIND<br>BLINDS: 15 MINUTOS');
                setTextContato();

                if ($("#modelImage").hasClass("modelImg1")) $("#modelImage").removeClass("modelImg1");
                if ($("#modelImage").hasClass("modelImg2")) $("#modelImage").removeClass("modelImg2");
                if ($("#modelImage").hasClass("modelImg3")) $("#modelImage").removeClass("modelImg3");
                $("#modelImage").addClass("modelImg"+modselect);

            }
        });

        $("#banner_name").keyup(function () {
            $(".boxText").html($("#banner_name").val().toUpperCase());
        });
        $("#modelTorneioName").click(function (e) {
            e.preventDefault();
            $('#banner_name').focus().select();
        });
        $("#banner_premio").keyup(function () {
            $("#modelTorneioPremio").html($("#banner_premio").val().toUpperCase());
            if (modselect === 1) {
                if ($("#banner_premio").val().length >= 6) {
                    $("#modelTorneioPremio").css('fontSize', '330%');
                    $("#modelTorneioPremio").css('line-height', '310%');
                } else if ($("#banner_premio").val().length > 3) {
                    $("#modelTorneioPremio").css('fontSize', '630%');
                    $("#modelTorneioPremio").css('line-height', '140%');
                } else {
                    $("#modelTorneioPremio").css('fontSize', '800%');
                    $("#modelTorneioPremio").css('line-height', '100%');
                }
            }
        });
        $("#modelTorneioPremio").click(function (e) {
            e.preventDefault();
            $('#banner_premio').focus().select();
        });
        $("#banner_dataevento").keyup(function () {
            setTextDataEvento();
        });
        $("#modelTorneioDataEvento").click(function (e) {
            e.preventDefault();
            $('#banner_dataevento').focus().select();
        });
        $("#banner_informacoes").keyup(function () {
            setTextContato();
        });
        $("#modelTorneioContato").click(function (e) {
            e.preventDefault();
            $('#banner_informacoes').focus().select();
        });
        $("#banner_buyin").keyup(function () {
            $("#modelTorneioEstrutura1").html($("#banner_buyin").val());
        });
        $("#modelTorneioEstrutura1").click(function (e) {
            e.preventDefault();
            $('#banner_buyin').focus().select();
        });
        $("#banner_bonus").keyup(function () {
            $("#modelTorneioEstrutura2").html($("#banner_bonus").val());
        });
        $("#modelTorneioEstrutura2").click(function (e) {
            e.preventDefault();
            $('#banner_bonus').focus().select();
        });
        $("#banner_rebuy").keyup(function () {
            $("#modelTorneioEstrutura3").html($("#banner_rebuy").val());
        });
        $("#modelTorneioEstrutura3").click(function (e) {
            e.preventDefault();
            $('#banner_rebuy').focus().select();
        });
        $("#banner_addon").keyup(function () {
            $("#modelTorneioEstrutura4").html($("#banner_addon").val());
        });
        $("#modelTorneioEstrutura4").click(function (e) {
            e.preventDefault();
            $('#banner_addon').focus().select();
        });
        $("#banner_complementoestrutura").keyup(function () {
            setTextCompEstrutura();
        });
        $("#modelTorneioEstrutura5").click(function (e) {
            e.preventDefault();
            $('#banner_complementoestrutura').focus().select();
        });

        function setTextCompEstrutura() {
            var complementoText = $('#banner_complementoestrutura').val();
            complementoText = complementoText.replace(/\n\r?/g, '<br />');
            $("#modelTorneioEstrutura5").html(complementoText);
        }
        function setTextContato() {
            var contatoText = $('#banner_informacoes').val();
            contatoText = contatoText.replace(/\n\r?/g, '<br />');
            $("#modelTorneioContato").html(contatoText);
        }
        function setTextDataEvento() {
            var dataeventoText = '';
            var mescss = '';
            if (modselect === 1) {
                var lines = $('#banner_dataevento').val().split(/\n/);
                dataeventoText = '<span class="dia">'+lines[0]+'</span><br>';
                if (lines[1]==='DE JANEIRO') mescss = '01';
                else if (lines[1]==='DE FEVEREIRO') mescss = '02';
                else if (lines[1]==='DE MARÇO') mescss = '03';
                else if (lines[1]==='DE ABRIL') mescss = '04';
                else if (lines[1]==='DE MAIO') mescss = '05';
                else if (lines[1]==='DE JUNHO') mescss = '06';
                else if (lines[1]==='DE JULHO') mescss = '07';
                else if (lines[1]==='DE AGOSTO') mescss = '08';
                else if (lines[1]==='DE SETEMBRO') mescss = '09';
                else if (lines[1]==='DE OUTRUBRO') mescss = '10';
                else if (lines[1]==='DE NOVEMBRO') mescss = '11';
                else if (lines[1]==='DE DEZEMBRO') mescss = '12';
                dataeventoText += '<span class="mes'+mescss+'">'+lines[1]+'</span><br>';
                dataeventoText += '<span class="hora">'+lines[2]+'</span>';
            } else {
                dataeventoText = $("#banner_dataevento").val();
            }
            $("#modelTorneioDataEvento").html(dataeventoText);
        }

        $("#btn_imgBack").click(function(e){
            e.preventDefault();
            $("#modelSet").css("display", "block");
            $("#modelSet1").css("display", "none");
            $("#modelImg").prop("src", "");
            $("#paneImg").addClass("col-md-5");
            $("#paneCad").css("display", "block");
        });

        $('#btn_imgLogo').on('click', function() {
            $('#banner_logo').trigger('click');
        });

        $("#banner_logo").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#modelTorneioLogo').css('backgroundImage','url('+e.target.result+')');
                };
                reader.readAsDataURL(this.files[0]);
                var fileName = $(this)[0].files[0].name;
                $('#banner_file').val(fileName);
            }
        });
        $("#btn_imgClear").click(function(e){
            e.preventDefault();
            $('#modelTorneioLogo').css('backgroundImage','url("{{$cad->clublogo}}")');
            $('#banner_file').val("Logo padrão selecionada");
        });
        $("#modelTorneioLogo").click(function(e){
            e.preventDefault();
            $('#banner_logo').trigger('click');
        });

        $("#btn_imgCreate").click(function(e){
            e.preventDefault();
            modal({
                type: 'confirm',
                title: 'CONCLUIR EDIÇÃO',
                text: "<H4 style='text-align:center'>Concluir edição do banner atual para o Torneio selecionado?</H4><p style='text-align:center'>Obs: Uma nova imagem será gerada e aplicada ao torneio atual.</p>",
                center: true,
                closeClick: true,
                //autoclose: true,
                buttonText: {
                    ok: 'FECHAR',
                    yes: '<strong>GERAR BANNER</strong>',
                    cancel: 'CANCELAR'
                },
                callback: function(result){
                    $('#boxpageup').css('display', 'none');
                    $('#boxpagedown').css('display', 'none');
                    if (result) {
                        html2canvas($('#modelImage')[0],{
                            //logging: true,
                            //allowTaint: true
                        }).then(function(canvas) {
                            var imagedata = canvas.toDataURL("image/png");
                            var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
                            saveImg(imgdata);
                        });
                    }
                }
            });
        });

        function saveImg(dataurl) {
            $.ajax({
                url: '/club/torn/img/save2',
                type: 'post',
                dataType: 'json',
                data: {
                    _token : $('[name="_token"]').val(),
                    torn_id : '{{$cad->id}}',
                    img1 : dataurl
                },
                success: function(data) {
                    // console.log(data);
                    if (data.result==='N'){
                        aviso('warning',data.message);
                        return false;
                    }
                    aviso('success',data.message);
                    window.location.reload();
                }
                ,error: function(XMLHttpRequest, textStatus, errorThrown){
                    aviso('warning',tratarExceptionAjax(XMLHttpRequest),'Atenção!');
                }
            });
        }

        $("#btn_imgDownAlta").click(function(e){
            e.preventDefault();
            html2canvas($('#modelImage')[0],{
                //logging: true,
                //allowTaint: true,
                scale: 2
            }).then(function(canvas) {
                var imgageData = canvas.toDataURL("image/png");
                var canvassave = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
                saveAs(canvassave, '{{ strtoupper($cad->name) }}.png');
            });
        });

        function saveAs(uri, filename) {
            var link = document.createElement('a');
            if (typeof link.download === 'string') {
                link.href = uri;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                window.open(uri);
            }
        }

    </script>
@endsection