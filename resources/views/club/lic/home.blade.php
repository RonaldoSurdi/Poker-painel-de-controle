@extends('layouts.painelclub')
@php($pag='lic')
@section('css')
    <style>
    #mylic, #mycad {
        display: none;
    }
    #cadmsg {
        display: none;
        font-size: 18px;
        color: red;
        font-weight: bold;
        text-align: center;
    }
    label {
        margin-bottom: 0;
        margin-top: 7px;
        font-weight: 400;
    }
    </style>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection
@section('content')

    <div class="row">
        <center>
        <h4>Licenças de Uso</h4>
        </center>
        <div class="row">
            <center>
            <div style="max-width:1000px">
                @if ($lic)
                    {{--Se For premium--}}
                    @if ($lic->vencida())
                        <div class="alert alert-warning alert-styled-left">
                            <span class="text-semibold">Atenção!</span>
                            Para continuar gerenciando seu clube você necessita ter uma licença ativa:
                            <br>Sua licença {{$lic->_type()}} venceu em {{ date('d/m/Y H:i:s', strtotime($lic->due_date)) }}
                        </div>
                    @else
                        <div class="panel panel-info text-left" style="max-width: 300px">
                            <div class="panel-heading">
                                <h5 class="panel-title">Licença ativa</h5>
                            </div>
                            <div class="panel-body p-5">
                                <h5>
                                    <table>
                                        <tr>
                                            <td class="p-5">Licença atual:</td>
                                            <td class="p-5"><span class="text-primary">{{$lic->_type()}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="p-5">Contratada:</td>
                                            <td class="p-5"><span class="text-primary">{{ date('d/m/Y', strtotime($lic->start_date)) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="p-5">Validade:</td>
                                            <td class="p-5"><span class="text-primary">{{ date('d/m/Y', strtotime($lic->due_date)) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="p-5">Dias:</td>
                                            <td class="p-5"><span class="text-primary">{{ $lic->dias() }}</span></td>
                                        </tr>
                                    </table>
                                </h5>
                            </div>
                        </div>
                    @endif
                @else
                    {{--SE naõ tem nenhuma licença--}}
                    <div class="alert alert-warning alert-styled-left">
                        <h3 class="text-semibold">Atenção!</h3>
                        <h5>
                            Para gerenciar seu clube você necessita ter uma licença ativa, escolha entre as opções abaixo disponíveis:
                        </h5>
                    </div>
                @endif
            </div>
            </center>
        </div>
        <div class="row">
            <center>
            <div class="panel panel-success" style="max-width:1000px">
                <div class="panel-heading" onclick="setMyCad()">
                    <h5 class="panel-title" id="titcad"><i class="icon-plus-circle2"></i> Dados Cadastrais</h5>
                </div>
                <div class="panel-body p-10" id="mycad" style="text-align: left">
                    <div id="cadmsg"></div>
                    <form id="frm_cad">
                        {{ csrf_field() }}
                        <input type="hidden" id="club_id" name="club_id" value="{{$cad->id}}">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>*Nome do Clube</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{$cad->name}}" placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>*CPF/CNPJ</label>
                                <input type="text" class="form-control" name="doc1" id="doc1" value="{{$cad->doc1}}" placeholder="" required>
                            </div>
                            <div class="col-sm-6">
                                <label>*Responsável</label>
                                <input type="text" class="form-control" name="responsible" id="responsible" value="{{$cad->responsible}}"  placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>*Telefone</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="{{$cad->phone}}"  placeholder="" required>
                            </div>
                            <div class="col-sm-6">
                                <label>Whatsapp</label>
                                <input type="text" class="form-control" name="whats" id="whats" value="{{$cad->whats}}"  placeholder="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Site</label>
                                <input type="text" class="form-control" name="site" id="site" value="{{$cad->site}}"  placeholder="" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>*CEP</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{$cad->zipcode}}"  placeholder="" required>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>*Cidade / UF</label>
                                <select class="form-control select2" name="city" id="city" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-10">
                                <label>*Endereço</label>
                                <input type="text" class="form-control" name="address" id="address"  value="{{$cad->address}}" placeholder="" maxlength=191 required>
                            </div>
                            <div class="col-sm-2">
                                <label>*Número</label>
                                <input type="text" class="form-control" name="number" id="number" value="{{$cad->number}}"  placeholder="" maxlength=10 required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>*Bairro</label>
                                <input type="text" class="form-control" name="district" id="district" value="{{$cad->district}}"  placeholder="" maxlength=50 required>
                            </div>
                            <div class="col-sm-6">
                                <label>Complemento</label>
                                <input type="text" class="form-control" name="complement" id="complement"  value="{{$cad->complement}}" maxlength=30 placeholder="">
                            </div>
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <br>
                            <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                    onclick="SetCadData()" id="btn_cad">Atualizar Dados</button>
                        </div>
                    </div>
                </div>
            </div>
            </center>
        </div>
        <div class="row">
            <center>
            <div class="panel panel-info" style="max-width:1000px">
                <div class="panel-heading" onclick="setMyLic()">
                    <h5 class="panel-title" id="titlic"><i class="icon-plus-circle2"></i> Minhas Licenças</h5>
                </div>
                <div class="panel-body p-5" id="mylic">
                    <table class="table table-hover datatable-responsive">
                        <thead>
                        <tr>
                            {{--<th class="hidden-xs" style="width: 120px">Preview</th>--}}
                            <th>Licença</th>
                            <th>Status</th>
                            <th class="hidden-xs">Contratado</th>
                            <th>Validade</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lista as $item)
                            <tr @if ($item->status==1) class="text-success"
                                @elseif ($item->status==2) class="text-warning"
                                @elseif ($item->status==4) class="text-info"
                                @elseif (($item->status==3)||($item->status==9)) class="text-danger" @endif>
                                <td>{{$item->_type()}}</td>
                                <td>
                                    @if (($item->status==0)&&($item->paymentStatus==1)&&($item->paymentLink!==''))
                                        {{$item->_status()}} - <a href="javascript:abrirpopup('{{$item->paymentLink}}');" class="btn btn-sm btn-primary">Boleto</a>
                                    @elseif ($item->status==0)
                                        {{$item->_status()}} - <a href="javascript:addlic(3);" class="btn btn-sm btn-primary">Pagar</a>
                                    @else
                                        {{$item->_status()}}
                                    @endif
                                </td>
                                <td  class="hidden-xs">
                                    @if ($item->start_date)
                                        {{ date('d/m/Y', strtotime($item->start_date)) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->due_date)
                                        {{ date('d/m/Y', strtotime($item->due_date)) }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>Você não possui licenças! Escolha entre uma das opções abaixo.</h5>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </center>
        </div>


        <div class="row" id="div_compra" style="display: none">

            <center>
                <div style="max-width:1000px;margin-top:60px;" >

                    <div class="col-sm-3 pricing-table text-center">
                        <center>
                            <div class="panel border-grey shadow1 animation"  data-animation="bounce" style="width: 230px; height: 350px;">
                                <div class="panel-body text-center">

                                    <h6 class="text-muted" style="margin-top: 20px;">Premium</h6>
                                    <h1 class="pricing-table-price m-5" style="font-size: 30px">30 dias</h1>

                                    <ul class="list-unstyled content-group no-margin">
                                        <li>Seu Clube no mapa</li>
                                        <li>Acesso gratuito por 30 dias</li>
                                        <li><br></li>
                                    </ul>
                                    <div class="ribbon-container">
                                        <div class="ribbon bg-grey">Gratuito</div>
                                    </div>

                                    <br>

                                    @if (!$lic30)
                                        <a href="{{route('club.lic.free')}}"  class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Adquirir</a>
                                    @else
                                        <b class="text-danger">Você já utilizou de <br>{{ date('d/m/Y', strtotime($lic30->start_date)) }} até {{ date('d/m/Y', strtotime($lic30->due_date)) }}.</b>
                                    @endif

                                </div>
                            </div>
                        </center>

                    </div>

                    <div class="col-sm-3 pricing-table text-center">

                        <center>
                            <div class="panel border-grey shadow1 animation" data-animation="tada" style="width: 230px; height: 350px;">
                                  <div class="panel-body text-center">
                                    <h6 class="text-muted" style="margin-top: 20px;"><br></h6>
                                    <h1 class="pricing-table-price m-5" style="font-size: 30px">R$ 129,00</h1>
                                    <!--h6 class="text-muted pull-right" style="font-size: 8pt; margin-top:-8px">Total: R$ 129,00</h6-->

                                    <ul class="list-unstyled content-group no-margin">
                                        <li>Clube destaque na localização</li>
                                        <li>Gerencie seu Clube</li>
                                        <li>Parcele em até 12x no cartão*</li>
                                    </ul>

                                    <div class="ribbon-container">
                                        <div class="ribbon bg-gray" style="background-color: #00a8d7">Mensal</div>
                                    </div>
                                    <br>
                                    <a onclick="addlic(1)" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                </div>
                            </div>
                        </center>

                    </div>

                    <div class="col-sm-3 pricing-table text-center">

                        <center>
                            <div class="panel border-grey shadow1 animation"  data-animation="tada" style="width: 230px; height: 350px;">
                                <div class="panel-body text-center">
                                    <h6 class="text-muted" style="margin-top: 20px;"><br></h6>
                                    <h1 class="pricing-table-price m-5" style="font-size: 30px">R$ 714,00</h1>
                                    <!--h6 class="text-muted pull-right" style="font-size: 8pt; margin-top:-8px">Total: R$ 714,00</h6-->

                                    <ul class="list-unstyled content-group no-margin">
                                        <li>Clube destaque na localização</li>
                                        <li>Gerencie seu Clube</li>
                                        <li>Parcele em até 12x no cartão*</li>
                                    </ul>

                                    <div class="ribbon-container">
                                        <div class="ribbon bg-gray" style="background-color: #3c763d">Semestral</div>
                                    </div>
                                    <br>
                                    <a onclick="addlic(2)" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                </div>
                            </div>
                        </center>

                    </div>

                    <div class="col-sm-3 pricing-table text-center">

                        <center>
                            <div class="panel border-grey shadow1 animation"  data-animation="tada" style="width: 230px; height: 350px;">
                                <div class="panel-body text-center">
                                    <h6 class="text-muted" style="margin-top: 20px;"><br></h6>
                                    <h1 class="pricing-table-price m-5" style="font-size: 30px">R$ 1.188,00</h1>
                                    <!--h6 class="text-muted pull-right" style="font-size: 8pt; margin-top:-8px">Total: R$ 1.188,00</h6-->

                                    <ul class="list-unstyled content-group no-margin">
                                        <li>Clube destaque na localização</li>
                                        <li>Gerencie seu Clube</li>
                                        <li>Parcele em até 12x no cartão*</li>
                                    </ul>

                                    <div class="ribbon-container">
                                        <div class="ribbon bg-gray" style="background-color: #000000">Anual</div>
                                    </div>
                                    <br>
                                    <a onclick="addlic(3)" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                </div>
                            </div>
                        </center>

                    </div>
                </div>
            </center>
        </div>
        <div class = "row">
          <center>
          <div class = "column">
            <span style="font-size:11px; color: #C8C8C8;">*Parcelamento sujeito a juros de 2,99% ao mês.</span>
          </div>
          </center>
        </div>
    </div>


@endsection

@section('script_footer')
    <script src="{{asset('my/js/animate_price.js')}}"></script>
    <script src="{{asset('my/js/people_edit.js')}}"></script>
    <script src="{{asset('my/js/valida_cpf_cnpj.js')}}"></script>
    <script>
        var PhoneMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            PhoneOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(PhoneMaskBehavior.apply({}, arguments), options);
                }
            };

        var DocMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length < 12 ? '000.000.000-00999' : '00.000.000/0000-00';
            },
            DocOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(DocMaskBehavior.apply({}, arguments), options);
                }
            };

        $('#whats').mask('(00) 00000-0000');
        $('#phone').mask(PhoneMaskBehavior, PhoneOptions);
        $('#doc1').mask(DocMaskBehavior, DocOptions, {reverse: true});
        $('#zipcode').mask('00000-000');
        $('#number').mask("#.##0", {reverse: true});
        SelCity( '{{ $cad->city_id }}' );
        @if (old('city') )
            SelCity( '{{ old('city') }}' );
        @endif

        var mycadstatus = false;
        function setMyCad() {
            if (mycadstatus) {
                $('#mycad').css('display','none');
                $('#titcad').html('<i class="icon-plus-circle2"></i> Dados Cadastrais');
                mycadstatus = false;
            } else {
                $('#mycad').css('display','block');
                $('#titcad').html('<i class="icon-minus-circle2"></i> Dados Cadastrais');
                mycadstatus = true;
                if (mylicstatus) setMyLic();
            }
        }

        var mylicstatus = false;
        function setMyLic() {
            if (mylicstatus) {
                $('#mylic').css('display','none');
                $('#titlic').html('<i class="icon-plus-circle2"></i> Minhas Licenças');
                mylicstatus = false;
            } else {
                $('#mylic').css('display','block');
                $('#titlic').html('<i class="icon-minus-circle2"></i> Minhas Licenças');
                mylicstatus = true;
                if (mycadstatus) setMyCad();
            }
        }

        function validDoc(msg) {
            var testreturn = true;
            $('#cadmsg').css('display','none');
            var docval = $('#doc1').val();
            var cnpjcpf = verifica_cpf_cnpj(docval);
            if (cnpjcpf === 'CPF') {
                if (!valida_cpf(docval)) {
                    if (!mycadstatus) setMyCad();
                    if (msg) aviso('warning','CPF digitado é inválido!!!');
                    else {
                        $('#cadmsg').html('<i class="icon-warning"></i> CPF digitado é inválido!!!');
                        $('#cadmsg').css('display','block');
                    }
                    $('#doc1').focus();
                    $('#doc1').select();
                    testreturn = false;
                }
            } else if (cnpjcpf === 'CNPJ') {
                if (!valida_cnpj(docval)) {
                    if (!mycadstatus) setMyCad();
                    if (msg) aviso('warning','CNPJ digitado é inválido!!!');
                    else {
                        $('#cadmsg').html('<i class="icon-warning"></i> CNPJ digitado é inválido!!!');
                        $('#cadmsg').css('display','block');
                    }
                    $('#doc1').focus();
                    $('#doc1').select();
                    testreturn = false;
                }
            } else {
                if (!mycadstatus) setMyCad();
                if (msg) aviso('warning','Digite o CPF/CNPJ do Clube!!!');
                else {
                    $('#cadmsg').html('<i class="icon-warning"></i> Digite o CPF/CNPJ do Clube!!!');
                    $('#cadmsg').css('display','block');
                }
                $('#doc1').focus();
                $('#doc1').select();
                testreturn = false;
            }
            return testreturn;
        }

        function SetCadData() {

            //if (!validDoc(true)) return;

            var btn_id = '#btn_cad';
            var btn_html = $(btn_id).html();
            $(btn_id).html('<i class="icon-spinner2 spinner"></i>');

            console.log(btn_html);

            var datasend = $('#frm_cad').serialize();
            console.log(datasend);

            $.ajax({
                url: '/lic/setCadData',
                type: 'post',
                dataType: 'json',
                data: datasend,
                success: function(data) {
                    // console.log(data);
                    console.log('result:'+data.result);
                    if (data.result=='N'){
                        aviso('warning',data.message);
                        $(btn_id).html(btn_html);
                        return false;
                    }
                    $(btn_id).html(btn_html);
                    aviso('success',data.message);
                }
                ,error: function(XMLHttpRequest, textStatus, errorThrown){
                    /**** deu erro na requisição web *****/
                    //aviso('warning',tratarExceptionAjax(XMLHttpRequest));
                    aviso('warning','Erro ao gravar dados');
                    $(btn_id).html(btn_html);
                }
            });

        }
        function abrirpopup(url) {
            window.open(url, 'boletoPagSeguro', 'width=825, height=1000, top=0, left=0, scrollbars=yes, status=no, toolbar=no, location=yes, directories=no, menubar=no, resizable=no, fullscreen=no')
        }
        function addlic(id) {

            if (!validDoc(true)) return;

            var urlLic = '';
            if (id === 1) {
                urlLic = 'premium1';
            } else if (id === 2) {
                urlLic = 'premium2';
            } else if (id === 3) {
                urlLic = 'premium';
            }
            $.ajax({
                url: '/lic/'+urlLic,
                type: 'post',
                dataType: 'json',
                data: {
                    _token : '{{ csrf_token() }}'
                },
                success: function(data) {
                    // console.log(data);
                    if (data.result=='N'){
                        aviso('warning',data.message);
                        return false;
                    }
                    aviso('success',data.message);
                    var code = data.code;
                    /*var callback = {
                        success : function(transactionCode) {
                            document.location.reload(true);
                        },
                        abort : function() {
                            document.location.reload(true);
                        }
                    };
                    var isOpenLightbox = PagSeguroLightbox({
                        code: code
                    }, callback);
                    if (!isOpenLightbox){*/
                    location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code=" + code;
                    console.log("Redirecionamento")
                    //}
                }
                ,error: function(XMLHttpRequest, textStatus, errorThrown){
                    /**** deu erro na requisição web *****/
                    aviso('warning',tratarExceptionAjax(XMLHttpRequest));
                }
            });
        }

        @if ($lic)
            @if ($lic->dias()<=30)
                $('#div_compra').show();
            @endif
        @else
            $('#div_compra').show();
        @endif

        validDoc(false);
    </script>
@endsection
