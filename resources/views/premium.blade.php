@extends('layouts.front')

@section('css')
    <link href="{{ asset('/my/css/home5.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('script')
    {{--<script type="text/javascript" src="{{ asset('front/assets/js/pages/animations_css3.js')}}"></script>--}}
@endsection

@section('slide')
    <!-- ========== Hero Cover ========== -->
    <div id="boxpageup"><img src="{{asset('/my/images/arrow_up.png')}}"></div>
    <div id="boxpagedown"><img src="{{asset('/my/images/arrow_down.png')}}"></div>
    <div class="content bg_premium">
        <div id="home" class="container app-hero" >
            <div class="container">
                <!-- Text -->
                <div class="col-sm-6 hero-text text-white">
                    <center>
                    <h1 class="text-dourado">Possui um Clube de Poker?</h1>

                    <h3  class="text-home">
                    O aplicativo utiliza das melhores tecnologias do mercado e auxilia no gerenciamento de um modo simples e prático.
                    Seja Premium e tenha um gerenciador completo em suas mãos!
                    </h3>
                    </center>

                    <br>

                    <h3>O clube na palma da sua mão.</h3>

                    <ul style="font-size: 14pt" >
                        <li>Envio de notificações e promoções para usuários;</li>
                        <li>Cadastro de torneios;</li>
                        <li>Incrições de jogadores em torneios;</li>
                        <li>Ranking dos jogadores;</li>
                        <li>Cadastro da agenda da semana;</li>
                        <li>Galeria de fotos do clube;</li>
                        <li>Sistema de busca e rota para usuários;</li>
                        <li>Informações completas do clube;</li>
                        <li>Transmissão ao vivo de eventos;</li>
                    </ul>

                    <br><br>
                    <center>
                    <a href="#cadpremium" class="btn btn-flat btn-down text-uppercase" style="width: 200px">
                         Experimente grátis</a>
                    {{--<a href="#sobre" class="btn bg-teal-800 btn-labeled btn-rounded m-5"><b><i class="icon-reading"></i></b> Saiba mais...</a>--}}
                    <br><br>
                    </center>
                </div><!-- / .col-lg-6 -->

                <!-- Image -->
                <div class="col-sm-6">
                    <center>
                        <div class="homelogin">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="text-center">

                                        <center>
                                            <img class="img-responsive mb-15" src="{{asset('my/images/ico_loginclub.png')}}" id="ico_user" >
                                        </center>
                                        <h5 class="content-group">Acesse seu gerenciador<small class="display-block">Suas credenciais</small></h5>
                                    </div>

                                    <form method="POST" action="{{ route('login') }}">
                                        {{ csrf_field() }}
                                        @if( ($errors->any()) and (($errors->has('email')) or ($errors->has('password')) ))
                                            <div class="text-left bg-dourado">
                                                <div class="alert alert-warning p-5">
                                                    {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>--}}
                                                    <h6 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Atenção</h6>
                                                    <ul >
                                                        @foreach( $errors->all() as $erro)
                                                            <li>{{$erro}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group has-feedback has-feedback-left {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Seu e-mail de acesso" value="{{ old('email') }}">
                                            <div class="form-control-feedback">
                                                <i id="ico_input" class="icon-user text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <input type="password" class="form-control" name="password" id="password"
                                                   placeholder="Sua senha" value="{{ old('password') }}">
                                            <div class="form-control-feedback">
                                                <i id="ico_input" class="icon-lock text-muted"></i>
                                            </div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button id="btn1" type="submit" class="btn bg-dourado btn-block">Entrar <i id="btn1_ico" class="icon-arrow-right14 position-right"></i></button>
                                            <a href="#cadpremium" class="btn btn-flat btn-down text-uppercase btn-block">CADASTRE-SE</a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </center>
                </div><!-- / .col-lg-6 -->
            </div><!-- / .row -->

        </div><!-- / .app-hero -->
    </div><!-- / .app-hero -->


    <!-- ========== App Download ========== -->
    <div id="cadpremium" class="bg-white">
        <center>
            <div class="container-fluid app-welcome text-center" style="max-width: 1520px; margin-bottom: -40px;">
                    <center>
                        <div style="max-width: 1000px;margin-top:60px" >
                            <div class="col-sm-3 pricing-table text-center">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="bounce" style="width: 230px; height: 320px">
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
                                            <a href="#cadastro" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Experimentar</a>
                                        </div>
                                    </div>
                                </center>
                            </div>

                            <div class="col-sm-3 pricing-table text-center">
                                <center>
                                    <div class="panel border-grey shadow1 animation" data-animation="tada" style="width: 230px; height: 320px">
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
                                            <a href="#cadastro" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                        </div>
                                    </div>
                                </center>
                            </div>

                            <div class="col-sm-3 pricing-table text-center">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="tada" style="width: 230px; height: 320px">
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
                                            <a href="#cadastro" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                        </div>
                                    </div>
                                </center>
                            </div>

                            <div class="col-sm-3 pricing-table text-center">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="tada" style="width: 230px; height: 320px">
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
                                            <a href="#cadastro" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Assinar</a>
                                        </div>
                                    </div>
                                </center>
                            </div>
                            <!--div class="col-sm-4 pricing-table">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="shake"  style="width: 230px; height: 300px">
                                        <div class="panel-body text-center">
                                            <h6 class="text-muted">Free</h6>
                                            <h1 class="pricing-table-price m-5">Grátis</h1>

                                            <br>
                                            <ul class="list-unstyled content-group no-margin">
                                            <li>Seu Clube no mapa</li>
                                            <li>Não possui gerenciador</li>
                                            </ul>
                                            <div class="ribbon-container">
                                                <div class="ribbon bg-grey">Gratuito</div>
                                            </div>
                                            <br>
                                            <a href="#" onclick="Indique()" class="btn bg-success-400 btn-lg text-uppercase text-size-small text-semibold">Cadastrar</a>
                                        </div>
                                    </div>
                                </center>
                            </div-->

                            <!--div class="col-sm-4 pricing-table myDiv">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="bounce" style="width: 230px; height: 300px">
                                        <div class="panel-body text-center">

                                            <h6 class="text-muted">Premium</h6>
                                            <h1 class="pricing-table-price m-5">30 dias</h1>
                                            <br>
                                            <ul class="list-unstyled content-group no-margin">
                                                <li>Seu Clube no mapa</li>
                                                <li>Vantagens Premium por 30 dias</li>
                                            </ul>
                                            <div class="ribbon-container">
                                                <div class="ribbon bg-grey">Gratuito</div>
                                            </div>

                                            <br>
                                            <a href="#cadastro"
                                               class="btn bg-success-400 btn-lg text-uppercase text-size-small text-semibold">Experimentar</a>
                                        </div>
                                    </div>
                                </center>
                            </div-->

                            <!--div class="col-sm-4 pricing-table text-center">
                                <center>
                                    <div class="panel border-grey shadow1 animation"  data-animation="tada" style="width: 230px; height: 300px">
                                        <div class="panel-body text-center">

                                            <h6 class="text-muted">em até 12x de</h6>

                                            <h1 class="pricing-table-price m-5">R$ 99,00</h1>
                                            {{--<h1 class="pricing-table-price"><span style="font-size: 14pt; top:-50px">12x</span> R$ 99</h1>--}}
                                            <h6 class="text-muted pull-right" style="font-size: 8pt; margin-top:-5px">Total: R$ 1.188,00</h6>
                                            <br>
                                            <ul class="list-unstyled content-group no-margin">
                                                <li>Clube destaque na localização</li>
                                                <li>Gerencie seu Clube</li>
                                            </ul>

                                            <div class="ribbon-container">
                                                <div class="ribbon bg-dourado">Premium</div>
                                            </div>
                                            <br>
                                            <a href="#cadastro" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Cadastrar</a>
                                        </div>
                                    </div>
                                </center>
                            </div-->
                        </div>
                    </center>
            </div>
            <div class = "row">
              <div class = "column">
                <span style="font-size:11px; color: #C8C8C8;">*Parcelamento sujeito a juros de 2,99% ao mês.</span>
              </div>
            </div>
        </center>
    </div>

    <div id="cadastro" class="bg-white">
        <center>
            <div class="container-fluid app-welcome text-center" style="max-width: 1520px">

                    <center>
                        @if( ($errors->any()) and (!$errors->has('email')) and (!$errors->has('password')))
                            <div class="text-left">
                                <div class="alert alert-warning alert-styled-left">
                                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                    <span class="text-semibold">Atenção!</span>
                                    <br>
                                    <ul>
                                    @foreach( $errors->all() as $erro)
                                        <li>{{$erro}}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="panel  border-dourado shadow1"  style="max-width: 650px;">
                            <div class="panel-heading bg-dourado">
                                <h5 class="panel-title"><b>Cadastre-se</b> agora e torne-se um <b>clube premium</b></h5>
                            </div>
                            <div class="panel-body text-left">

                                <form action="{{route('new.club')}}" class="form-horizontal" method="POST">
                                    {{ csrf_field() }}

                                    <input type="hidden" id="cad_id" name="cad_id" value="0">
                                    <input type="hidden" id="contract" name="contract" value="0">
                                    <input type="hidden" id="terms" name="terms" value="0">
                                    <fieldset>
                                        <legend class="text-semibold">Dados do clube</legend>

                                        <div class="form-group {!! hasErrorClass($errors,'name') !!}">
                                            <div class="col-sm-12">
                                                <label>*Nome do Clube</label>
                                                <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="" required>
                                                {!! helpBlock($errors,'name') !!}
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'doc1') !!}">
                                                <label>*CPF/CNPJ</label>
                                                <input type="text" class="form-control" name="doc1" id="doc1" value="{{old('doc1')}}" placeholder="" required>
                                                {!! helpBlock($errors,'doc1') !!}
                                            </div>
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'responsible') !!}">
                                                <label>*Responsável</label>
                                                <input type="text" class="form-control" name="responsible" id="responsible" value="{{old('responsible')}}"  placeholder="" required>
                                                {!! helpBlock($errors,'responsible') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'phone') !!}">
                                                <label>*Telefone</label>
                                                <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone')}}"  placeholder="" required>
                                                {!! helpBlock($errors,'phone') !!}
                                            </div>
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'whats') !!}">
                                                <label>Whatsapp</label>
                                                <input type="text" class="form-control" name="whats" id="whats" value="{{old('whats')}}"  placeholder="">
                                                {!! helpBlock($errors,'whats') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {!! hasErrorClass($errors,'site') !!}">
                                            <div class="col-sm-12">
                                                <label>Site</label>
                                                <input type="text" class="form-control" name="site" id="site" value="{{old('site')}}"  placeholder="" >
                                                {!! helpBlock($errors,'site') !!}
                                            </div>
                                        </div>

                                    </fieldset>
                                    <fieldset>
                                        <legend class="text-semibold">Localização</legend>

                                        <div class="form-group">
                                            <div class="col-sm-4 {!! hasErrorClass($errors,'zipcode') !!}">
                                                <label>*CEP</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{old('zipcode')}}"  placeholder="" required>
                                                    {{--<span class="input-group-btn">--}}
                                                        {{--<button class="btn btn-default" type="button"><i class="fa fa-search"></i> </button>--}}
                                                    {{--</span>--}}
                                                </div>
                                                {!! helpBlock($errors,'zipcode') !!}
                                            </div>
                                            <div class="col-sm-8 {!! hasErrorClass($errors,'city') !!}">
                                                <label>*Cidade / UF</label>
                                                <select class="form-control select2" name="city" id="city" required>
                                                    <option value=""></option>
                                                </select>
                                                {!! helpBlock($errors,'city') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-10 {!! hasErrorClass($errors,'address') !!}">
                                                <label>*Endereço</label>
                                                <input type="text" class="form-control" name="address" id="address"  value="{{old('address')}}" placeholder="" maxlength=191 required>
                                                {!! helpBlock($errors,'address') !!}
                                            </div>
                                            <div class="col-sm-2 {!! hasErrorClass($errors,'number') !!}">
                                                <label>*Número</label>
                                                <input type="text" class="form-control" name="number" id="number" value="{{old('number')}}"  placeholder="" maxlength=10 required>
                                                {!! helpBlock($errors,'number') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'district') !!}">
                                                <label>*Bairro</label>
                                                <input type="text" class="form-control" name="district" id="district" value="{{old('district')}}"  placeholder="" maxlength=50 required>
                                                {!! helpBlock($errors,'doc1') !!}
                                            </div>
                                            <div class="col-sm-6 {!! hasErrorClass($errors,'complement') !!}">
                                                <label>Complemento</label>
                                                <input type="text" class="form-control" name="complement" id="complement"  value="{{old('complement')}}" maxlength=30 placeholder="">
                                                {!! helpBlock($errors,'complement') !!}
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend class="text-semibold">Login para acesso ao gerenciador</legend>
                                        <div class="form-group">
                                            <div class="col-sm-12 {!! hasErrorClass($errors,'lic_user') !!}">
                                                <label>*E-mail / usuário</label>
                                                <input type="email" class="form-control" name="lic_user" id="lic_user"  value="{{old('lic_user')}}" placeholder="E-mail válido, será seu usuário de acesso" required>
                                                {!! helpBlock($errors,'lic_user') !!}
                                            </div>

                                        </div>

                                        <div class="form-group {!! hasErrorClass($errors,'lic_pass') !!} {!! hasErrorClass($errors,'lic_pass_confirmation') !!}">
                                            <div class="col-sm-6">
                                                <label>*Senha</label>
                                                <input type="password" class="form-control" name="lic_pass" id="lic_pass" placeholder="" required>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <label>Confirmar Senha</label>
                                                <input type="password" class="form-control" name="lic_pass_confirmation" id="lic_pass_confirmation" placeholder="" required>
                                            </div>
                                            {!! helpBlock($errors,'lic_pass') !!}
                                            {!! helpBlock($errors,'lic_pass_confirmation') !!}
                                        </div>
                                    </fieldset>

                                    <div>
                                        <br>
                                        <div id="btcontratdiv" class="col-sm-8 text-left">
                                            <button id="bt_contrato" onclick="btermos('1'); return false;" class="btn bg-slate-700">Contrato de Uso<i class="icon-checkmark2 position-right"></i></button>
                                            <button id="bt_politica" onclick="btermos('2'); return false;" class="btn bg-slate-700">Política de privacidade <i class="icon-checkmark2 position-right"></i></button>
                                        </div>
                                        <div id="ismob"><br></div>
                                        <div id="btsenddiv" class="col-sm-4 text-right">
                                            <button type="submit" onclick="if (!aceitotermos[0]||!aceitotermos[1]) { btermos('3'); return false; }" class="btn bg-grey-800">Cadastrar meu Clube <i class="icon-arrow-right14 position-right"></i></button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </center>
            </div><!-- / .container -->
        </center>
    </div><!-- / .gray-bg -->
@endsection

@section('footer')
    <!-- Footer -->
    <div class="container">
        <div class="text-center">
            <a href="http://saintec.com.br" target="_blank">
                <img src="{{asset('my/images/footer.png')}}" class="mt-20 mb-5">
            </a>
        </div>
    </div>
    <!-- /footer -->
@endsection


@section('script_footer')
    {{--<script src="{{asset('material/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('my/js/people_edit.js')}}"></script>
    <script src="{{asset('my/js/animate_price.js')}}"></script>
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

        @if (old('city') )
            SelCity( '{{ old('city') }}' );
        @endif
        if ($(window).width()< 600) {
            $('#ismob').css('display', 'block');
            $("#btcontratdiv").removeClass("text-left");
            $("#btcontratdiv").addClass("text-center");
            $("#btsenddiv").removeClass("text-right");
            $("#btsenddiv").addClass("text-center");
        }
        var aceitotermos = [false,false];
        <?
        $meses = array(
            '',
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        );
        $dias_da_semana = array(
            'Domingo',
            'Segunda-Feira',
            'Terça-Feira',
            'Quarta-Feira',
            'Quinta-Feira',
            'Sexta-Feira',
            'Sábado'
        );
        //'casado, portador do RG nº ___________ e CPF nº ___________________.</p>' +
        ?>
        function btermos(test) {
            if (test=="1") {
                var msgerror = '';
                var docmets = $("#doc1").val();
                if (docmets=='') msgerror += '<br>CPF/CNPJ';
                var clubenm = $("#name").val();
                if (clubenm=='') msgerror += '<br>Nome do Clube';
                var endnumb = $("#number").val();
                if ((endnumb!='0')&&(endnumb!='')) endnumb = ', ' + endnumb;
                var enddistrict = $("#district").val();
                if (enddistrict!='') enddistrict = ', ' + enddistrict;
                var endcomplement = $("#complement").val();
                if (endcomplement!='') endcomplement = ', ' + endcomplement;
                var enderec = $("#address").val() + endnumb + enddistrict + endcomplement;
                if (enderec=='') msgerror += '<br>Endereço completo';
                var endcity = $("#city").val();
                if (endcity=='') msgerror += '<br>Cidade/UF';
                var responsibleclub = $("#responsible").val();
                if (responsibleclub=='') msgerror += '<br>Responsável Legal';
                if (msgerror != '') {
                    msgerror = "<p style='text-align:center'><strong>CAMPOS OBRIGATÓRIOS:</strong><br/><br/>"+msgerror+"</p>";
                    modal({
                        type: 'warning',
                        title: 'AVISO',
                        text: msgerror,
                        center: true,
                        buttonText: {
                            ok: 'FECHAR JANELA'
                        }
                    });
                } else {
                    var cnpjcpf = verifica_cpf_cnpj(docmets);
                    var tx1 = 'jurídica';
                    if (cnpjcpf == 'CPF') {
                        tx1 = 'física';
                    }
                    var contrato = '';
                    var contrato_title = '';
                    if ($(window).width()> 600) {
                        contrato_title = 'CONTRATO DE PRESTAÇÃO DE SERVIÇOS E LICENCIAMENTO DE DIREITO DE USO';
                        contrato = '<div style="width:50%;float:left;text-align:justify;padding:10px 10px 10px 10px">' +
                            '<p><strong>CONTRATANTE: ' + clubenm + '</strong>, pessoa ' + tx1 + ' de direito privado, devidamente inscrita no ' + cnpjcpf + ' sob o nº ' + docmets +
                            ', com sede à ' + enderec + ', na cidade de  ' + endcity + ', neste ato representada pelo Sr.' + responsibleclub + ', Brasileiro.</p>' +
                            '<p><strong>CONTRATADO: SAINTEC APP DESENVOLVIMENTO E LICENCIAMENTO DE SISTEMAS LTDA. </strong>, pessoa jurídica de direito privado,  devidamente inscrita no CNPJ sob o nº  31.203.005/0001-45,  com sede à Rua Dr. Maruri, 1204, Sala A, Centro, Concórdia – SC, neste ato  representado pelo Sr. Robson Fernando Schneider<strong><em>, </em></strong>Brasileiro, casado,  portador do RG nº 3.527.295 SSP/SC e CPF nº 029.091.329-27, tem entre os  mesmos, de maneira justa e acordada, o presente <strong>CONTRATO DE LICENÇA DE USO DE SISTEMA E PRESTAÇÃO DE SERVIÇOS POR PRAZO  DETERMINADO</strong>, ficando desde já aceito, pelas cláusulas abaixo descritas.</p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>CLÁUSULA 1ª – DO OBJETO</strong></p>' +
                            '<p>O  presente instrumento tem como objeto o direito de uso por prazo determinado,  oneroso, intransferível e não exclusivo de 01 (um) PLATAFORMA WEB GERENCIADOR  DE CLUBES DE POKER – SISTEMA POKER CLUBS - MULTIUSUÁRIO de propriedade do <strong>CONTRATADO </strong>para desempenho das atividades  empresariais do <strong>CONTRATANTE</strong>, bem  como a prestação de serviços descritos nos moldes da Cláusula 4ª e Parágrafos.<strong></strong></p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Entenda-se  por sistema MULTIUSUÁRIO o direito de utilizar o Software contratado por mais  de 01 (um) terminal desde que pertencente à mesma empresa CONTRATANTE <strong>(mesmo CNPJ)</strong> e vinculado diretamente ao  mesmo servidor de dados.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O  objeto do presente instrumento com sua respectiva versão, será requerido <em>on-line</em>, via acesso remoto e após o  devido pagamento será libera para uso através de <em>login</em> e senha.<strong></strong></p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Todas  as características estruturais e funcionais com as especificações quanto à  versão e potencialidades da plataforma contratado, bem como as configurações  mínimas de hardware necessárias ao apropriado funcionamento do software, estarão  devidamente especificadas no &ldquo;ANEXO A&rdquo; que por sua vez faz parte integrante do  presente contrato, estando a ele devidamente agrupado.</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Para  fins deste instrumento constitui versão, o conjunto de características  estruturais e funcionais da plataforma em determinado estágio. A partir da  versão ora contratada, poderá a plataforma vir a ter novas versões, resultantes  de modificações em suas atuais características, por razões técnicas de  compatibilização com a evolução de seus recursos e plataformas de geração e  operação, e principalmente, objetivando a sua própria evolução tecnológica.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Fica  estabelecido que as futuras versões, caso sejam de interesse do <strong>CONTRATANTE</strong>, serão objeto de orçamento  para sua prévia e expressa aprovação, caso o mesmo não possuir com o <strong>CONTRATADO</strong> o contrato de personalização  e/ou customização, caso possua, a atualização se dará sem ônus, e de maneira automática.</p>' +
                            '<p><strong>PARÁGRAFO SEXTO:</strong> Considerar-se-á como aceite de todos os termos deste contrato, o pagamento da  fatura pelo CONTRATANTE, obrigando-se desde então as partes contratadas. O uso  dos serviços ofertados configurará a aceitação tácita dos termos contratuais  estabelecidos neste instrumento.</p>' +
                            '<p><strong>CLÁUSULA  2ª – DO PREÇO, FORMA DE PAGAMENTO E EMISSÃO DA NOTA FISCAL CORRESPONDENTE<br />' +
                            '  </strong><br />' +
                            '  O <strong>CONTRATANTE</strong> pagará ao <strong>CONTRATADO</strong> pela licença de uso do  sistema os valores descritos no *ANEXO A*, através de boleto bancário a ser  emitido pelo <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Obriga-se  ainda o <strong>CONTRATANTE</strong> a pagar  mensalmente ao <strong>CONTRATADO</strong>, referente  taxa de liberação de uso mensal que engloba os serviços descritos na cláusula  4ª e seus Parágrafos, a importância mensal descrita  no *ANEXO A*.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O pagamento descrito no parágrafo anterior será realizado até o vencimento  de cada mês vincendo, através de boleto bancário a ser emitido pelo <strong>CONTRATADO</strong> e devidamente entregue ou  disponibilizado ao <strong>CONTRATANTE</strong>, com  antecedência no mínimo de 05 (cinco) dias da data do respectivo vencimento.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO:</strong> As notas fiscais, referentes às taxas de liberação de  uso mensal do sistema em si, estarão disponíveis  através do site da empresa (http://www.pokerclubsapp.com.br/), apenas em versão  eletrônica (NF-e).</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Os valores descritos no parágrafo Primeiro desta Cláusula que não  forem tempestivamente pagos, ficarão sujeitos a correção monetária de acordo  com a variação do IGP-M/FGV, contados a partir da data do vencimento até a data  do efetivo pagamento, bem como multa de 2% (dois por cento) sobre o montante  atualizado, e juros de mora de 0,33% ao dia<a name="_GoBack" id="_GoBack"></a> pró-rata. Em  caso de atraso o CONTRATANTE estará sujeitando-se ainda ao registro de seu nome  no Serviço de Proteção ao Crédito – SPC.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>O não pagamento das mensalidades em suas datas próprias acarretará,  após o 5º dia útil do vencimento, o bloqueio/suspensão imediato do direito de  uso do sistema e de serviços prestados pelo <strong>CONTRATADO, </strong>no que concerne a plataforma objeto do presente  instrumento, bem como os demais serviços por ele habitualmente prestados ao <strong>CONTRATANTE.<br />' +
                            '  <br />' +
                            '  PARÁGRAFO SEXTO: </strong>Caso o sistema venha a ser bloqueado pelo atraso no  pagamento do boleto da mensalidade, a SAINTEC SISTEMAS tem um prazo de até 48  horas para efetivar a liberação do mesmo, após identificação pelo sistema  bancário da quitação do que estiver em atraso por parte do <strong>CONTRATANTE</strong>, sendo que a liberação pode ser feita a qualquer  momento neste período.</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO: </strong>O valor da mensalidade, descrito no &ldquo;ANEXO A&rdquo;, será reajustado  anualmente de acordo com as taxas praticadas comercialmente, tal reajuste será  informado com antecedência mínima de 30 dias.</p>' +
                            '<p><strong>CLÁUSULA 3ª – DO PRAZO,  RENOVAÇÃO E RESCISÃO</strong></p>' +
                            '<p>O  presente instrumento vigorará pelo prazo mínimo de 01 (um) ano a contar da data  do pagamento do boleto bancário que será emitido pela <strong>CONTRATADA </strong>em até 03 (três) dias após o devido ACEITE ON LINE  efetuado pelo <strong>CONTRATANTE.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: Havendo interesse de  ambas as partes, o presente contrato poderá ser renovado, por igual período (ou  seja, 12 (doze) meses), mediante solicitação por escrito do CONTRATANTE, com  antecedência mínima de 30 (trinta) dias da data prevista para o término do  contrato, <u>hipótese em que poderá haver reajustamento da taxa de manutenção</u>.  (conforme descrito na CLÁUSULA 2ª, PARÁGRAFO SÉTIMO).</strong></p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: C</strong>aso  nenhuma das partes comunique a outra, com antecedência mínima de 30 dias da  data prevista para o término do contrato, a vigência deste contrato <strong><u>passará a ser mensal, renovável  automaticamente caso o CONTRATADO esteja de acordo, enquanto o pagamento  estiver sendo realizado</u>.</strong></p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Caso qualquer  das partes deseje rescindir o presente instrumento, antes de findar o prazo  mínimo descrito no <em>caput </em>da presente  cláusula, qual seja 01 (um) ano, pagará à <strong>parte  contrária</strong> a título de multa, o equivalente a 03 (três) mensalidades de  acordo com os valores descritos no parágrafo primeiro da cláusula 2ª;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>O  presente instrumento poderá ainda ser rescindido de pleno direito nas seguintes  hipóteses:</p>' +
                            '<ol>' +
                            '  <li>Se  qualquer das partes, sem prévia e expressa autorização da outra, ceder,  transferir ou caucionar a terceiros, no todo ou em parte, os direitos e  obrigações derivados deste instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Por  descumprimento de qualquer das partes das obrigações, condições descritas nas  cláusulas deste instrumento;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Por  solicitação do <strong>CONTRATANTE,</strong><u> em  custas ou multas</u>, desde que esteja em dia com as mensalidades previstas na  cláusula 2ª, Parágrafo Primeiro, após ter utilizado o sistema por um período  mínimo de 12 (doze) meses, conforme contratado; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Por  solicitação do <strong>CONTRATADO, </strong><u>sem  custas ou multas</u>, durante o período de em que passar a viger a renovação  mensal, mencionada na Cláusula 3ª Parágrafo Segundo, mediante notificação ao  CONTRATANTE sobre a rescisão, com antecedência mínima de 30 (trinta) dias.</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Decretação  de recuperação judicial, falência ou dissolução de uma das partes;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li> No caso do não pagamento por parte do <strong>CONTRATANTE</strong> de 02 (duas) mensalidades,  consecutivas ou não;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLAUSULA 4ª – DOS SERVIÇOS E  DA MANUTENÇÃO</strong></p>' +
                            '<p>O <strong>CONTRATADO</strong> prestará assistência  técnica, por sua iniciativa, quando se fizer necessário, e por solicitação do <strong>CONTRATANTE</strong>, neste caso no período  agendado, conforme a natureza e a complexidade do serviço relatado.</p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO:</strong> O  sistema será fornecido por meio eletrônico, sem mídias, para garantir que o <strong>CONTRATANTE</strong> tenha sempre a versão mais  atualizada do sistema.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O <strong>CONTRATADO</strong> é responsável pela  manutenção do <strong>SISTEMA POKER CLUBS</strong>,  excluindo dessa responsabilidade a configuração de outros equipamentos e outros  serviços, tais como mikrotik, access points, servidores de proxy, servidores de  DNS, servidores web, servidores FTP, roteadores, etc. Ficando excluídos também  qualquer tipo de treinamento sobre itens relacionados, tais como: rede,  mikrotik, cabeamento, Linux, PHP, hospedagem, etc.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Os  serviços serão realizados por profissionais habilitados, sempre <em>online</em>, para manter a agilidade e baixo  custo ao <strong>CONTRATANTE</strong>.<br />' +
                            '  <br />' +
                            '  <strong>PARÁGRAFO QUARTO: </strong>Quaisquer  alterações ou ampliações do sistema original poderão ser efetuadas mediante a  avaliação do <strong>CONTRATADO</strong> e orçadas  para prévia-aprovação pelo <strong>CONTRATANTE</strong> em <u>faturamento à parte</u>.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Nos  termos do parágrafo anterior, compreende-se alteração ou ampliação do sistema o  projeto de desenvolvimento de novos módulos/rotinas inexistentes na versão  atual do sistema POKER CLUBS.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SEXTO:</strong> Caso  seja necessária a manutenção do sistema por erro ou falha do <strong>CONTRATANTE</strong>, por qualquer que seja o  motivo, o processo terá o custo de 01 (uma) mensalidade negociada no ato da manutenção,  que será feito somente <em>online,</em> a ser  quitada antes do agendamento, independente do valor mensal que o <strong>CONTRATANTE</strong> já tenha se comprometido a  pagar.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO:</strong> O <strong>CONTRATADO</strong> poderá analisar pedidos de  implementações e adaptações do sistema solicitados pelo<strong> CONTRATANTE</strong>, mas, contudo, somente os implementará caso realmente  necessários, às expensas do <strong>CONTRATANTE </strong>e  após ter decorrido período mínimo de 04 (quatro) meses de efetiva vigência  contratual.  </p>' +
                            '<p><strong>PARÁGRAFO OITAVO: </strong>O<strong> CONTRATADO</strong> dará manutenção apenas no  que se refere ao SISTEMA O<br />' +
                            '  POKER  CLUBS, ficando excluídos de tais manutenções o suporte e assistência na  configuração de equipamentos tais como roteadores, access points, servidores  Linux e demais hardwares. </p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO NONO:</strong> Para  o fim de realização de eventual manutenção presencial, correrão por conta do <strong>CONTRATANTE </strong>todos os custos e despesas  eventualmente suportadas pelo <strong>CONTRATADO</strong> e seus prepostos, tais como transporte, viagem, alimentação e estadia.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO DÉCIMO: </strong>Caso o <strong>CONTRATADO</strong> venha a arcar com  qualquer dos custos e despesas descritos no parágrafo anterior, o <strong>CONTRATANTE</strong> obriga-se a reembolsar  imediatamente o <strong>CONTRATADO</strong>, dos  valores por ele despendidos, mediante apresentação dos respectivos comprovantes  de despesas correspondentes;</p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>PARÁGRAFO DÉCIMO PRIMEIRO: </strong>Caso o <strong>CONTRATADO</strong> forneça acesso ao <em>login</em> e senha do Sistema POKER CLUBS à  terceiros, a SAINTEC SISTEMAS fica automaticamente isenta de qualquer incidente  que venha ocorrer, tais como: vazamento de informações, alterações de dados de  cadastros, <em>logins</em> de acessos, etc. e  também a SAINTEC SISTEMAS não terá a obrigação de corrigir qualquer falha que  venha a ocorrer proveniente de alguma alteração no sistema operacional ou itens  instalados efetuada por tais acessos, bem como essas alterações implicará ao <strong>CONTRATANTE</strong> custos adicionais e sanções  contratuais.</p>' +
                            '</div>' +
                            '<div style="width:50%;float:left;text-align:justify;padding:10px 10px 10px 10px">' +
                            '<p><strong>CLÁUSULA 5ª – DAS OBRIGAÇÕES  DO CONTRATADO</strong></p>' +
                            '<p>O <strong>CONTRATADO </strong>mediante  contraprestação mensal obriga-se a:</p>' +
                            '<ol>' +
                            '  <li>Realizar a liberação de <em>login</em> e senha do SISTEMA POKER CLUBS, objeto do presente instrumento, ao <strong>CONTRATANTE</strong>;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Realizar o treinamento dos indivíduos/usuários indicados pelo <strong>CONTRATANTE</strong> que irão utilizar o sistema,  mediante prévia solicitação com antecedência de 10 (dez) dias e de forma <em>on line</em>;<br />' +
                            '  </li>' +
                            '  <li>Promover a reciclagem dos indivíduos/usuários, na hipótese do <strong>CONTRATANTE </strong>adquirir versões mais  atualizadas do sistema;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Promover as devidas correções no que concerne às falhas e/ou  impropriedades do sistema, bem como atualizar o mesmo, por razão de erro não detectado  anteriormente;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Fornecer suporte técnico ao <strong>CONTRATANTE, </strong>ou qualquer outro atendimento ou consulta, referente ao sistema, de segunda-feira  à sexta-feira, das 08:00hs às 12:00hs e das 14:00hs às 18:00hs (horário de  Brasília - DF);</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Guardar, proteger, todos os dados ou informações do <strong>CONTRATANTE</strong> e de seus clientes,  contidos no banco de dados e/ou obtidos por força do presente instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLÁUSULA 6ª – DAS OBRIGAÇÕES  DO CONTRATANTE</strong></p>' +
                            '<p>O <strong>CONTRATANTE</strong> obriga-se  a:</p>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Remunerar o <strong>CONTRATADO</strong>,  nos termos descritos na cláusula 2ª e parágrafos;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Utilizar o sistema contratado de acordo com suas finalidades e  exigências técnicas;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Disponibilizar  o meio adequado para a implantação e utilização do(s) sistema(s), tais como: <em>hardware, </em>rede, pessoas  capacitadas, entre outros; </li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Responsabilizar-se  legalmente pelos dados e informações armazenados no sistema contratado,  inclusive por imagens e declarações postadas no SISTEMA POKER CLUBS de sua  gerência, isentando assim de qualquer responsabilidade de danos a terceiros por  tais usos indevidos desses dados, a SAINTEC SISTEMAS; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Arcar  com os prejuízos advindos da danificação permanente e irreparável de banco de  dados quando estes advierem por sua própria responsabilidade (não efetuação de  backups, danos físicos em unidades de armazenamento, vírus), a recuperação de  qualquer informação, sendo ela possível ou não, gerará custos ao <strong>CONTRATANTE</strong>;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Expor  todas as informações indispensáveis e atinentes à assistência prestada pelo <strong>CONTRATADO </strong>para que este possa vir a  solucionar correções no sistemacontratado,  caso seja necessário; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Responsabilizar-se  por qualquer infração legal, nos âmbitos civil, penal, autoral e todos os  demais, que, eventualmente, venha a ser cometida com a utilização do sistema  contratado; </li>' +
                            '</ol>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>É vedado ainda ao <strong>CONTRATANTE</strong>,  sem prévia e escrita autorização do <strong>CONTRATADO</strong>:</p>' +
                            '<ol>' +
                            '  <li>Divulgar, revelar ou disponibilizar o sistema, objeto do presente  instrumento, a qualquer terceiro, salvo de acordo com o expressamente previsto  neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Utilizar, vender, distribuir, sublicenciar, alugar, arrendar,  emprestar, dar, dispor, ceder ou de qualquer forma transferir total ou  parcialmente o sistema objeto deste contrato e/ou quaisquer direitos a ele  relativos salvo se e de acordo com o expressamente previsto neste instrumento;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Copiar, adaptar, aprimorar, alterar, corrigir, traduzir,  atualizar, desenvolver novas versões ou elaborar obras derivadas do sistema,  objeto deste contrato, ou ainda de qualquer de suas partes e componentes salvo  se e de acordo com o expressamente previsto neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Desmontar, descompilar, fazer engenharia reversa do sistema, ou  por intermédio de qualquer outra forma, obter, acessar ou tentar obter ou  acessar o código-fonte do sistema e/ou qualquer dado ou informação confidencial  relativa ao sistema, objeto do presente contrato;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Remover os avisos de direitos autorais ou quaisquer outros avisos  de direitos de propriedade contidos no sistema, objeto do presente instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>A ocorrência de tais hipóteses previstas acima acarretará a  aplicação inicial de multa, equivalente a 10 (dez) vezes o valor do presente  instrumento mais a somatória de possíveis perdas e danos e do direito da <strong>CONTRATADA</strong>, de rescindir o presente  contrato imediatamente. </p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>A transferência pelo <strong>CONTRATANTE</strong> a terceiros, a qualquer título, da posse ou propriedade de qualquer equipamento  ou titularidade de sua empresa, não implicará cessão ou transferência da  licença de uso do SISTEMA POKER CLUBS conferida ao mesmo;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Na hipótese do <strong>CONTRATANTE</strong>,  pretender transferir a terceiros a titularidade de seu estabelecimento, deverá  obrigatoriamente comunicar tal fato prévia e expressamente ao <strong>CONTRATADO</strong>, ficando a transferência da  licença de uso do sistema sujeita a celebração entre o <strong>CONTRATADO</strong> e o terceiro-adquirente de um novo contrato de licença  de uso, bem como ao pagamento dos valores que venham a ser ajustados entre  estes;</p>' +
                            '<p><strong>CLÁUSULA 7ª – DA PROPRIEDADE  INTELECTUAL E CONFIDENCIALIDADE</strong><br />' +
                            '  Todos os direitos e propriedade intelectual no tocante ao SISTEMA  POKER CLUBS, objeto do presente contrato, são e permanecerão na propriedade  exclusiva do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Incluí-se na determinação do <em>caput </em>da presente cláusula, quaisquer aprimoramentos, correções, traduções, alterações,  novas versões ou obras derivadas, realizadas pelo <strong>CONTRATADO</strong>, isoladamente ou em conjunto com o <strong>CONTRATANTE</strong> ou ainda qualquer terceiro.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O SISTEMA  POKER CLUBS, objeto do presente contrato é de titularidade e propriedade do <strong>CONTRATADO</strong>, de forma que os direitos  autorais e outros direitos de propriedade intelectual relativos ao mesmo são  iguais aos conferidos às obras literárias nos moldes da legislação de direitos  autorais vigentes no país, conforme expressa determinação do Artigo 2º e  Parágrafos da Lei 9.609/98.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO:</strong> O <strong>CONTRATANTE</strong> obriga-se a guardar e a  manter o sigilo e a confidencialidade de todas as informações e/ou dados de  natureza confidencial, que lhe seja divulgado pelo <strong>CONTRATADO </strong>ou aos quais venha a ter acesso sob e em função deste  contrato.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>O <strong>CONTRATANTE</strong> obriga-se ainda a utilizar  informações referentes ao objeto deste contrato, apenas e tão somente o  estritamente necessário para o desempenho de suas atividades, adotando ainda  todas as precauções necessárias para evitar que tais dados/informações sejam  utilizadas, reproduzidas, publicadas ou divulgadas sem expressa autorização por  escrito do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Em  caso de término e/ou rescisão do presente instrumento, seja por qual motivo  for, o <strong>CONTRATANTE, </strong>deverá  imediatamente interromper o uso do sistema e devolver ao <strong>CONTRATADO</strong> todos os materiais e meios físicos que constituam e/ou  incorporem a propriedade intelectual deste, ou ainda, inutilizá-las, a  exclusivo critério do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO SEXTO: </strong>Todas  as obrigações contidas nesta cláusula permanecerão em vigor, não só durante a  vigência do presente instrumento, como também por um período de 05 (cinco) anos  contados da data de seu término. </p>' +
                            '<p><strong>CLÁUSULA 8ª - DAS DISPOSIÇÕES GERAIS</strong></p>' +
                            '<p>Caso o <strong>CONTRATADO</strong>, por qualquer motivo,  deixe de atuar na área de informática, ou de prestar os serviços relativos ao sistema  em questão, serão indicados novos representantes para que possam continuar o  suporte, assegurando ao <strong>CONTRATANTE</strong> a continuidade na prestação de serviços técnicos relativos ao adequado  funcionamento/manutenção do programa, consideradas as suas especificações, durante  o prazo de validade técnica da respectiva versão contratada, nos moldes do  artigo 8º da Lei 9.609/98.</p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>O <strong>CONTRATANTE </strong>concorda que o <strong>CONTRATADO </strong>pode coletar e usar  informações técnicas que sejam fornecidas como parte dos serviços de suporte ou  outros, relacionados ao objeto do presente instrumento. Frisando que poderá  usar essas informações somente para aprimorar seus produtos ou para fornecer  serviços personalizados ou tecnologias, e não poderá divulgar essas informações  de modo que possam identificá-lo pessoalmente.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>Eventuais  omissões ou meras tolerâncias das partes no exigir o estrito e pleno  cumprimento dos termos e condições deste contrato ou de prerrogativas  decorrentes dele ou de lei, não constituirão novação ou renúncia, nem afetarão  o exercício de quaisquer direitos, que poderão ser plena e integralmente  exercidos, a qualquer tempo.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Nenhuma  das partes será responsável por qualquer atraso ou falha no cumprimento de suas  obrigações descritas no presente instrumento, caso tal atraso ou falha seja  resultante de fatos alheios à vontade das partes, ou de seu controle razoável,  incluindo casos fortuitos e/ou eventos de força maior.</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Fica  terminantemente proibido ao <strong>CONTRATANTE, </strong>realizar a contratação de qualquer empregado/parceiro do <strong>CONTRATADO</strong>, que esteja ou tenha se  envolvido na prestação de serviços ou cumprimento de qualquer obrigação  estabelecida neste instrumento, seja durante a vigência do presente e por um  período de 05 (cinco) anos contados do seu término/rescisão.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Qualquer  prejuízo que o <strong>CONTRATANTE,</strong> vier a  experimentar, pelo uso inadequado e ou cadastramento incorreto de dados  bancários (boletos) não será de responsabilidade do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO SEXTO: </strong>O <strong>CONTRATADO</strong> não estará obrigado a  efetuar qualquer ressarcimento financeiro que venha ser solicitado pelo <strong>CONTRATANTE, </strong>posto ser de conhecimento  das partes que os valores pagos se referem a serviços já realizados pelo <strong>CONTRATADO </strong>em função da utilização  periódica do sistema.</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO: </strong>A  responsabilidade do <strong>CONTRATADO</strong> por  quaisquer eventuais prejuízos ou danos, de qualquer natureza, comprovadamente  resultantes da concessão da licença e prestação dos serviços, de acordo com  este contrato, não excederá o preço de implantação estabelecido na cláusula 2ª  deste.</p>' +
                            '<p><strong>CLÁUSULA 9ª - </strong>O <strong>CONTRATADO</strong>, não terá qualquer  responsabilidade perante o <strong>CONTRATANTE</strong> e/ou terceiros, no tocante a qualquer ação que resulte de:</p>' +
                            '<ol>' +
                            '  <li>Qualquer  violação pelo <strong>CONTRATANTE</strong> de suas  obrigações descritas neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Mau  uso do sistema, caracterizado pelo uso em desacordo com as especificações  técnicas aplicáveis;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Qualquer  alteração, modificação ou ajuste do objeto do presente contrato executado, por  terceiro que não autorizado por escrito pelo <strong>CONTRATADO</strong>;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Combinação,  conexão, operação ou uso de qualquer componente do sistema com equipamento ou  documentação não fornecido pelo <strong>CONTRATADO</strong>;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Liberação  pelo <strong>CONTRATANTE</strong> de acesso ao sistema  por terceiros em seu estabelecimento ou em qualquer outro lugar ou equipamento  que o sistema possa ser acessado;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLÁUSULA 10ª - </strong>A  presente avença não poderá, sob nenhum aspecto, ser interpretada como uma  associação ou um ato de sociedade entre as partes, para todo e qualquer fim de  direito.</p>' +
                            '<p><strong>CLÁUSULA 11ª - </strong>A  CONTRATADA não se responsabiliza por imagens, declarações e informações  postadas no SISTEMA POKER CLUBS de sua gerência;</p>' +
                            '<p><strong>CLÁUSULA 12ª - </strong>O  presente instrumento encontra-se registrado no Cartório de Registro de Títulos  e documentos, Comarca de Concórdia – SC.</p>' +
                            '<p><strong>CLÁUSULA 13ª - </strong>As  partes elegem o foro da Comarca de Concórdia - SC como o competente para  dirimir quaisquer dúvidas ou controvérsias advindas do presente contrato, em  preferência a qualquer outro, por mais privilegiado que seja ou venha a ser.</p>' +
                            '<p>&nbsp;</p>' +
                            '<strong><u><br clear="all" />' +
                            '</u></strong>' +
                            '</div>' +
                            '<div style="display:inline-block;float:none;width:100%;text-align:justify;padding:10px 10px 10px 10px">' +
                            '<p align="center"><strong><u>ANEXO A<br />' +
                            '  </u></strong><br />' +
                            '  Este  Anexo é parte integrante do <strong><u>Contrato  de Prestação de Serviços e Licenciamento de Direito de Uso do SISTEMA POKER  CLUBS por prazo determinado</u></strong>, estando a ele devidamente agrupado.</p>' +
                            '<p>O  presente anexo tem como objetivo definir as características estruturais e  funcionais com as especificações quanto à versão e potencialidade do sistema  contratado, as configurações mínimas de hardware necessárias ao apropriado  funcionamento, bem como, o preço dos serviços de licença de uso e manutenção,  conforme definido abaixo.</p>' +
                            '<p><strong><u>PREÇO DA LICENÇA:</u></strong></p>' +
                            '<ol>' +
                            '  <li>Serviço  de <strong>licença de uso e</strong> <strong>manutenção</strong> no Sistema Poker Clubs, sem  limite de terminais e/ou usuários para o mesmo ponto do servidor é de <strong>R$ 129,00 (cento e vinte e nove reais)</strong>,  sendo que o pagamento ocorra até a data de vencimento de cada mês, o  CONTRATANTE pagará valor com desconto promocional que é de <strong>R$ 99,00 (noventa e nove reais), mensais (reajustado  anualmente na renovação); O valor promocional é válido para o primeiro ano de  contrato;</strong></li>' +
                            '  <li>Serviço  de <strong>liberação de login e senha e  treinamento de uso</strong> do sistema: <strong>R$ 99,00  (noventa e nove reais);</strong></li>' +
                            '  <li>As  Taxas acima descritas deveram ser pagas através de boleto bancário/cartão de  crédito/ ou outro serviço de pagamento indicado pela SAINTEC SISTEMAS.</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p>Concórdia / SC, <? echo date("d") . ' de ' . $meses[date("n")] . ' de ' . date("Y"); ?>.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p>_________________________________________<br />' +
                            '  Contratante: ' + clubenm + '<br />' +
                            '  ' + cnpjcpf + ' sob o nº ' + docmets + '<br />' +
                            '  <br />' +
                            '  <br />' +
                            '  ____________________________________________________________________________________<br />' +
                            '  Contratado:  SAINTEC APP DESENVOLVIMENTO E LICENCIAMENTO DE SISTEMAS LTDA.<br />' +
                            '  CNPJ:  31.203.005/0001-45 </p>' +
                            '</div>';
                    } else {
                        $('#boxpagedown').css('display', 'block');
                        contrato_title = 'CONTRATO DE SERVIÇOS';
                        contrato = '<p><strong>CONTRATANTE: ' + clubenm + '</strong>, pessoa ' + tx1 + ' de direito privado, devidamente inscrita no ' + cnpjcpf + ' sob o nº ' + docmets +
                            ', com sede à ' + enderec + ', na cidade de  ' + endcity + ', neste ato representada pelo Sr.' + responsibleclub + ', Brasileiro.</p>' +
                            '<p><strong>CONTRATADO: SAINTEC APP DESENVOLVIMENTO E LICENCIAMENTO DE SISTEMAS LTDA. </strong>, pessoa jurídica de direito privado,  devidamente inscrita no CNPJ sob o nº  31.203.005/0001-45,  com sede à Rua Dr. Maruri, 1204, Sala A, Centro, Concórdia – SC, neste ato  representado pelo Sr. Robson Fernando Schneider<strong><em>, </em></strong>Brasileiro, casado,  portador do RG nº 3.527.295 SSP/SC e CPF nº 029.091.329-27, tem entre os  mesmos, de maneira justa e acordada, o presente <strong>CONTRATO DE LICENÇA DE USO DE SISTEMA E PRESTAÇÃO DE SERVIÇOS POR PRAZO  DETERMINADO</strong>, ficando desde já aceito, pelas cláusulas abaixo descritas.</p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>CLÁUSULA 1ª – DO OBJETO</strong></p>' +
                            '<p>O  presente instrumento tem como objeto o direito de uso por prazo determinado,  oneroso, intransferível e não exclusivo de 01 (um) PLATAFORMA WEB GERENCIADOR  DE CLUBES DE POKER – SISTEMA POKER CLUBS - MULTIUSUÁRIO de propriedade do <strong>CONTRATADO </strong>para desempenho das atividades  empresariais do <strong>CONTRATANTE</strong>, bem  como a prestação de serviços descritos nos moldes da Cláusula 4ª e Parágrafos.<strong></strong></p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Entenda-se  por sistema MULTIUSUÁRIO o direito de utilizar o Software contratado por mais  de 01 (um) terminal desde que pertencente à mesma empresa CONTRATANTE <strong>(mesmo CNPJ)</strong> e vinculado diretamente ao  mesmo servidor de dados.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O  objeto do presente instrumento com sua respectiva versão, será requerido <em>on-line</em>, via acesso remoto e após o  devido pagamento será libera para uso através de <em>login</em> e senha.<strong></strong></p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Todas  as características estruturais e funcionais com as especificações quanto à  versão e potencialidades da plataforma contratado, bem como as configurações  mínimas de hardware necessárias ao apropriado funcionamento do software, estarão  devidamente especificadas no &ldquo;ANEXO A&rdquo; que por sua vez faz parte integrante do  presente contrato, estando a ele devidamente agrupado.</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Para  fins deste instrumento constitui versão, o conjunto de características  estruturais e funcionais da plataforma em determinado estágio. A partir da  versão ora contratada, poderá a plataforma vir a ter novas versões, resultantes  de modificações em suas atuais características, por razões técnicas de  compatibilização com a evolução de seus recursos e plataformas de geração e  operação, e principalmente, objetivando a sua própria evolução tecnológica.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Fica  estabelecido que as futuras versões, caso sejam de interesse do <strong>CONTRATANTE</strong>, serão objeto de orçamento  para sua prévia e expressa aprovação, caso o mesmo não possuir com o <strong>CONTRATADO</strong> o contrato de personalização  e/ou customização, caso possua, a atualização se dará sem ônus, e de maneira automática.</p>' +
                            '<p><strong>PARÁGRAFO SEXTO:</strong> Considerar-se-á como aceite de todos os termos deste contrato, o pagamento da  fatura pelo CONTRATANTE, obrigando-se desde então as partes contratadas. O uso  dos serviços ofertados configurará a aceitação tácita dos termos contratuais  estabelecidos neste instrumento.</p>' +
                            '<p><strong>CLÁUSULA  2ª – DO PREÇO, FORMA DE PAGAMENTO E EMISSÃO DA NOTA FISCAL CORRESPONDENTE<br />' +
                            '  </strong><br />' +
                            '  O <strong>CONTRATANTE</strong> pagará ao <strong>CONTRATADO</strong> pela licença de uso do  sistema os valores descritos no *ANEXO A*, através de boleto bancário a ser  emitido pelo <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Obriga-se  ainda o <strong>CONTRATANTE</strong> a pagar  mensalmente ao <strong>CONTRATADO</strong>, referente  taxa de liberação de uso mensal que engloba os serviços descritos na cláusula  4ª e seus Parágrafos, a importância mensal descrita  no *ANEXO A*.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O pagamento descrito no parágrafo anterior será realizado até o vencimento  de cada mês vincendo, através de boleto bancário a ser emitido pelo <strong>CONTRATADO</strong> e devidamente entregue ou  disponibilizado ao <strong>CONTRATANTE</strong>, com  antecedência no mínimo de 05 (cinco) dias da data do respectivo vencimento.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO:</strong> As notas fiscais, referentes às taxas de liberação de  uso mensal do sistema em si, estarão disponíveis  através do site da empresa (http://www.pokerclubsapp.com.br/), apenas em versão  eletrônica (NF-e).</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Os valores descritos no parágrafo Primeiro desta Cláusula que não  forem tempestivamente pagos, ficarão sujeitos a correção monetária de acordo  com a variação do IGP-M/FGV, contados a partir da data do vencimento até a data  do efetivo pagamento, bem como multa de 2% (dois por cento) sobre o montante  atualizado, e juros de mora de 0,33% ao dia<a name="_GoBack" id="_GoBack"></a> pró-rata. Em  caso de atraso o CONTRATANTE estará sujeitando-se ainda ao registro de seu nome  no Serviço de Proteção ao Crédito – SPC.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>O não pagamento das mensalidades em suas datas próprias acarretará,  após o 5º dia útil do vencimento, o bloqueio/suspensão imediato do direito de  uso do sistema e de serviços prestados pelo <strong>CONTRATADO, </strong>no que concerne a plataforma objeto do presente  instrumento, bem como os demais serviços por ele habitualmente prestados ao <strong>CONTRATANTE.<br />' +
                            '  <br />' +
                            '  PARÁGRAFO SEXTO: </strong>Caso o sistema venha a ser bloqueado pelo atraso no  pagamento do boleto da mensalidade, a SAINTEC SISTEMAS tem um prazo de até 48  horas para efetivar a liberação do mesmo, após identificação pelo sistema  bancário da quitação do que estiver em atraso por parte do <strong>CONTRATANTE</strong>, sendo que a liberação pode ser feita a qualquer  momento neste período.</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO: </strong>O valor da mensalidade, descrito no &ldquo;ANEXO A&rdquo;, será reajustado  anualmente de acordo com as taxas praticadas comercialmente, tal reajuste será  informado com antecedência mínima de 30 dias.</p>' +
                            '<p><strong>CLÁUSULA 3ª – DO PRAZO,  RENOVAÇÃO E RESCISÃO</strong></p>' +
                            '<p>O  presente instrumento vigorará pelo prazo mínimo de 01 (um) ano a contar da data  do pagamento do boleto bancário que será emitido pela <strong>CONTRATADA </strong>em até 03 (três) dias após o devido ACEITE ON LINE  efetuado pelo <strong>CONTRATANTE.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: Havendo interesse de  ambas as partes, o presente contrato poderá ser renovado, por igual período (ou  seja, 12 (doze) meses), mediante solicitação por escrito do CONTRATANTE, com  antecedência mínima de 30 (trinta) dias da data prevista para o término do  contrato, <u>hipótese em que poderá haver reajustamento da taxa de manutenção</u>.  (conforme descrito na CLÁUSULA 2ª, PARÁGRAFO SÉTIMO).</strong></p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: C</strong>aso  nenhuma das partes comunique a outra, com antecedência mínima de 30 dias da  data prevista para o término do contrato, a vigência deste contrato <strong><u>passará a ser mensal, renovável  automaticamente caso o CONTRATADO esteja de acordo, enquanto o pagamento  estiver sendo realizado</u>.</strong></p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Caso qualquer  das partes deseje rescindir o presente instrumento, antes de findar o prazo  mínimo descrito no <em>caput </em>da presente  cláusula, qual seja 01 (um) ano, pagará à <strong>parte  contrária</strong> a título de multa, o equivalente a 03 (três) mensalidades de  acordo com os valores descritos no parágrafo primeiro da cláusula 2ª;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>O  presente instrumento poderá ainda ser rescindido de pleno direito nas seguintes  hipóteses:</p>' +
                            '<ol>' +
                            '  <li>Se  qualquer das partes, sem prévia e expressa autorização da outra, ceder,  transferir ou caucionar a terceiros, no todo ou em parte, os direitos e  obrigações derivados deste instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Por  descumprimento de qualquer das partes das obrigações, condições descritas nas  cláusulas deste instrumento;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Por  solicitação do <strong>CONTRATANTE,</strong><u> em  custas ou multas</u>, desde que esteja em dia com as mensalidades previstas na  cláusula 2ª, Parágrafo Primeiro, após ter utilizado o sistema por um período  mínimo de 12 (doze) meses, conforme contratado; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Por  solicitação do <strong>CONTRATADO, </strong><u>sem  custas ou multas</u>, durante o período de em que passar a viger a renovação  mensal, mencionada na Cláusula 3ª Parágrafo Segundo, mediante notificação ao  CONTRATANTE sobre a rescisão, com antecedência mínima de 30 (trinta) dias.</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Decretação  de recuperação judicial, falência ou dissolução de uma das partes;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li> No caso do não pagamento por parte do <strong>CONTRATANTE</strong> de 02 (duas) mensalidades,  consecutivas ou não;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLAUSULA 4ª – DOS SERVIÇOS E  DA MANUTENÇÃO</strong></p>' +
                            '<p>O <strong>CONTRATADO</strong> prestará assistência  técnica, por sua iniciativa, quando se fizer necessário, e por solicitação do <strong>CONTRATANTE</strong>, neste caso no período  agendado, conforme a natureza e a complexidade do serviço relatado.</p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO:</strong> O  sistema será fornecido por meio eletrônico, sem mídias, para garantir que o <strong>CONTRATANTE</strong> tenha sempre a versão mais  atualizada do sistema.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O <strong>CONTRATADO</strong> é responsável pela  manutenção do <strong>SISTEMA POKER CLUBS</strong>,  excluindo dessa responsabilidade a configuração de outros equipamentos e outros  serviços, tais como mikrotik, access points, servidores de proxy, servidores de  DNS, servidores web, servidores FTP, roteadores, etc. Ficando excluídos também  qualquer tipo de treinamento sobre itens relacionados, tais como: rede,  mikrotik, cabeamento, Linux, PHP, hospedagem, etc.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Os  serviços serão realizados por profissionais habilitados, sempre <em>online</em>, para manter a agilidade e baixo  custo ao <strong>CONTRATANTE</strong>.<br />' +
                            '  <br />' +
                            '  <strong>PARÁGRAFO QUARTO: </strong>Quaisquer  alterações ou ampliações do sistema original poderão ser efetuadas mediante a  avaliação do <strong>CONTRATADO</strong> e orçadas  para prévia-aprovação pelo <strong>CONTRATANTE</strong> em <u>faturamento à parte</u>.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Nos  termos do parágrafo anterior, compreende-se alteração ou ampliação do sistema o  projeto de desenvolvimento de novos módulos/rotinas inexistentes na versão  atual do sistema POKER CLUBS.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SEXTO:</strong> Caso  seja necessária a manutenção do sistema por erro ou falha do <strong>CONTRATANTE</strong>, por qualquer que seja o  motivo, o processo terá o custo de 01 (uma) mensalidade negociada no ato da manutenção,  que será feito somente <em>online,</em> a ser  quitada antes do agendamento, independente do valor mensal que o <strong>CONTRATANTE</strong> já tenha se comprometido a  pagar.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO:</strong> O <strong>CONTRATADO</strong> poderá analisar pedidos de  implementações e adaptações do sistema solicitados pelo<strong> CONTRATANTE</strong>, mas, contudo, somente os implementará caso realmente  necessários, às expensas do <strong>CONTRATANTE </strong>e  após ter decorrido período mínimo de 04 (quatro) meses de efetiva vigência  contratual.  </p>' +
                            '<p><strong>PARÁGRAFO OITAVO: </strong>O<strong> CONTRATADO</strong> dará manutenção apenas no  que se refere ao SISTEMA O<br />' +
                            '  POKER  CLUBS, ficando excluídos de tais manutenções o suporte e assistência na  configuração de equipamentos tais como roteadores, access points, servidores  Linux e demais hardwares. </p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO NONO:</strong> Para  o fim de realização de eventual manutenção presencial, correrão por conta do <strong>CONTRATANTE </strong>todos os custos e despesas  eventualmente suportadas pelo <strong>CONTRATADO</strong> e seus prepostos, tais como transporte, viagem, alimentação e estadia.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO DÉCIMO: </strong>Caso o <strong>CONTRATADO</strong> venha a arcar com  qualquer dos custos e despesas descritos no parágrafo anterior, o <strong>CONTRATANTE</strong> obriga-se a reembolsar  imediatamente o <strong>CONTRATADO</strong>, dos  valores por ele despendidos, mediante apresentação dos respectivos comprovantes  de despesas correspondentes;</p>' +
                            '<p><strong>&nbsp;</strong></p>' +
                            '<p><strong>PARÁGRAFO DÉCIMO PRIMEIRO: </strong>Caso o <strong>CONTRATADO</strong> forneça acesso ao <em>login</em> e senha do Sistema POKER CLUBS à  terceiros, a SAINTEC SISTEMAS fica automaticamente isenta de qualquer incidente  que venha ocorrer, tais como: vazamento de informações, alterações de dados de  cadastros, <em>logins</em> de acessos, etc. e  também a SAINTEC SISTEMAS não terá a obrigação de corrigir qualquer falha que  venha a ocorrer proveniente de alguma alteração no sistema operacional ou itens  instalados efetuada por tais acessos, bem como essas alterações implicará ao <strong>CONTRATANTE</strong> custos adicionais e sanções  contratuais.</p>' +
                            '<p><strong>CLÁUSULA 5ª – DAS OBRIGAÇÕES  DO CONTRATADO</strong></p>' +
                            '<p>O <strong>CONTRATADO </strong>mediante  contraprestação mensal obriga-se a:</p>' +
                            '<ol>' +
                            '  <li>Realizar a liberação de <em>login</em> e senha do SISTEMA POKER CLUBS, objeto do presente instrumento, ao <strong>CONTRATANTE</strong>;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Realizar o treinamento dos indivíduos/usuários indicados pelo <strong>CONTRATANTE</strong> que irão utilizar o sistema,  mediante prévia solicitação com antecedência de 10 (dez) dias e de forma <em>on line</em>;<br />' +
                            '  </li>' +
                            '  <li>Promover a reciclagem dos indivíduos/usuários, na hipótese do <strong>CONTRATANTE </strong>adquirir versões mais  atualizadas do sistema;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Promover as devidas correções no que concerne às falhas e/ou  impropriedades do sistema, bem como atualizar o mesmo, por razão de erro não detectado  anteriormente;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Fornecer suporte técnico ao <strong>CONTRATANTE, </strong>ou qualquer outro atendimento ou consulta, referente ao sistema, de segunda-feira  à sexta-feira, das 08:00hs às 12:00hs e das 14:00hs às 18:00hs (horário de  Brasília - DF);</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Guardar, proteger, todos os dados ou informações do <strong>CONTRATANTE</strong> e de seus clientes,  contidos no banco de dados e/ou obtidos por força do presente instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLÁUSULA 6ª – DAS OBRIGAÇÕES  DO CONTRATANTE</strong></p>' +
                            '<p>O <strong>CONTRATANTE</strong> obriga-se  a:</p>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Remunerar o <strong>CONTRATADO</strong>,  nos termos descritos na cláusula 2ª e parágrafos;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Utilizar o sistema contratado de acordo com suas finalidades e  exigências técnicas;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Disponibilizar  o meio adequado para a implantação e utilização do(s) sistema(s), tais como: <em>hardware, </em>rede, pessoas  capacitadas, entre outros; </li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Responsabilizar-se  legalmente pelos dados e informações armazenados no sistema contratado,  inclusive por imagens e declarações postadas no SISTEMA POKER CLUBS de sua  gerência, isentando assim de qualquer responsabilidade de danos a terceiros por  tais usos indevidos desses dados, a SAINTEC SISTEMAS; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Arcar  com os prejuízos advindos da danificação permanente e irreparável de banco de  dados quando estes advierem por sua própria responsabilidade (não efetuação de  backups, danos físicos em unidades de armazenamento, vírus), a recuperação de  qualquer informação, sendo ela possível ou não, gerará custos ao <strong>CONTRATANTE</strong>;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Expor  todas as informações indispensáveis e atinentes à assistência prestada pelo <strong>CONTRATADO </strong>para que este possa vir a  solucionar correções no sistemacontratado,  caso seja necessário; </li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Responsabilizar-se  por qualquer infração legal, nos âmbitos civil, penal, autoral e todos os  demais, que, eventualmente, venha a ser cometida com a utilização do sistema  contratado; </li>' +
                            '</ol>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>É vedado ainda ao <strong>CONTRATANTE</strong>,  sem prévia e escrita autorização do <strong>CONTRATADO</strong>:</p>' +
                            '<ol>' +
                            '  <li>Divulgar, revelar ou disponibilizar o sistema, objeto do presente  instrumento, a qualquer terceiro, salvo de acordo com o expressamente previsto  neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Utilizar, vender, distribuir, sublicenciar, alugar, arrendar,  emprestar, dar, dispor, ceder ou de qualquer forma transferir total ou  parcialmente o sistema objeto deste contrato e/ou quaisquer direitos a ele  relativos salvo se e de acordo com o expressamente previsto neste instrumento;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Copiar, adaptar, aprimorar, alterar, corrigir, traduzir,  atualizar, desenvolver novas versões ou elaborar obras derivadas do sistema,  objeto deste contrato, ou ainda de qualquer de suas partes e componentes salvo  se e de acordo com o expressamente previsto neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Desmontar, descompilar, fazer engenharia reversa do sistema, ou  por intermédio de qualquer outra forma, obter, acessar ou tentar obter ou  acessar o código-fonte do sistema e/ou qualquer dado ou informação confidencial  relativa ao sistema, objeto do presente contrato;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Remover os avisos de direitos autorais ou quaisquer outros avisos  de direitos de propriedade contidos no sistema, objeto do presente instrumento;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>A ocorrência de tais hipóteses previstas acima acarretará a  aplicação inicial de multa, equivalente a 10 (dez) vezes o valor do presente  instrumento mais a somatória de possíveis perdas e danos e do direito da <strong>CONTRATADA</strong>, de rescindir o presente  contrato imediatamente. </p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>A transferência pelo <strong>CONTRATANTE</strong> a terceiros, a qualquer título, da posse ou propriedade de qualquer equipamento  ou titularidade de sua empresa, não implicará cessão ou transferência da  licença de uso do SISTEMA POKER CLUBS conferida ao mesmo;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Na hipótese do <strong>CONTRATANTE</strong>,  pretender transferir a terceiros a titularidade de seu estabelecimento, deverá  obrigatoriamente comunicar tal fato prévia e expressamente ao <strong>CONTRATADO</strong>, ficando a transferência da  licença de uso do sistema sujeita a celebração entre o <strong>CONTRATADO</strong> e o terceiro-adquirente de um novo contrato de licença  de uso, bem como ao pagamento dos valores que venham a ser ajustados entre  estes;</p>' +
                            '<p><strong>CLÁUSULA 7ª – DA PROPRIEDADE  INTELECTUAL E CONFIDENCIALIDADE</strong><br />' +
                            '  Todos os direitos e propriedade intelectual no tocante ao SISTEMA  POKER CLUBS, objeto do presente contrato, são e permanecerão na propriedade  exclusiva do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>Incluí-se na determinação do <em>caput </em>da presente cláusula, quaisquer aprimoramentos, correções, traduções, alterações,  novas versões ou obras derivadas, realizadas pelo <strong>CONTRATADO</strong>, isoladamente ou em conjunto com o <strong>CONTRATANTE</strong> ou ainda qualquer terceiro.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>O SISTEMA  POKER CLUBS, objeto do presente contrato é de titularidade e propriedade do <strong>CONTRATADO</strong>, de forma que os direitos  autorais e outros direitos de propriedade intelectual relativos ao mesmo são  iguais aos conferidos às obras literárias nos moldes da legislação de direitos  autorais vigentes no país, conforme expressa determinação do Artigo 2º e  Parágrafos da Lei 9.609/98.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO:</strong> O <strong>CONTRATANTE</strong> obriga-se a guardar e a  manter o sigilo e a confidencialidade de todas as informações e/ou dados de  natureza confidencial, que lhe seja divulgado pelo <strong>CONTRATADO </strong>ou aos quais venha a ter acesso sob e em função deste  contrato.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>O <strong>CONTRATANTE</strong> obriga-se ainda a utilizar  informações referentes ao objeto deste contrato, apenas e tão somente o  estritamente necessário para o desempenho de suas atividades, adotando ainda  todas as precauções necessárias para evitar que tais dados/informações sejam  utilizadas, reproduzidas, publicadas ou divulgadas sem expressa autorização por  escrito do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Em  caso de término e/ou rescisão do presente instrumento, seja por qual motivo  for, o <strong>CONTRATANTE, </strong>deverá  imediatamente interromper o uso do sistema e devolver ao <strong>CONTRATADO</strong> todos os materiais e meios físicos que constituam e/ou  incorporem a propriedade intelectual deste, ou ainda, inutilizá-las, a  exclusivo critério do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO SEXTO: </strong>Todas  as obrigações contidas nesta cláusula permanecerão em vigor, não só durante a  vigência do presente instrumento, como também por um período de 05 (cinco) anos  contados da data de seu término. </p>' +
                            '<p><strong>CLÁUSULA 8ª - DAS DISPOSIÇÕES GERAIS</strong></p>' +
                            '<p>Caso o <strong>CONTRATADO</strong>, por qualquer motivo,  deixe de atuar na área de informática, ou de prestar os serviços relativos ao sistema  em questão, serão indicados novos representantes para que possam continuar o  suporte, assegurando ao <strong>CONTRATANTE</strong> a continuidade na prestação de serviços técnicos relativos ao adequado  funcionamento/manutenção do programa, consideradas as suas especificações, durante  o prazo de validade técnica da respectiva versão contratada, nos moldes do  artigo 8º da Lei 9.609/98.</p>' +
                            '<p><strong>PARÁGRAFO PRIMEIRO: </strong>O <strong>CONTRATANTE </strong>concorda que o <strong>CONTRATADO </strong>pode coletar e usar  informações técnicas que sejam fornecidas como parte dos serviços de suporte ou  outros, relacionados ao objeto do presente instrumento. Frisando que poderá  usar essas informações somente para aprimorar seus produtos ou para fornecer  serviços personalizados ou tecnologias, e não poderá divulgar essas informações  de modo que possam identificá-lo pessoalmente.</p>' +
                            '<p><strong>PARÁGRAFO SEGUNDO: </strong>Eventuais  omissões ou meras tolerâncias das partes no exigir o estrito e pleno  cumprimento dos termos e condições deste contrato ou de prerrogativas  decorrentes dele ou de lei, não constituirão novação ou renúncia, nem afetarão  o exercício de quaisquer direitos, que poderão ser plena e integralmente  exercidos, a qualquer tempo.</p>' +
                            '<p><strong>PARÁGRAFO TERCEIRO: </strong>Nenhuma  das partes será responsável por qualquer atraso ou falha no cumprimento de suas  obrigações descritas no presente instrumento, caso tal atraso ou falha seja  resultante de fatos alheios à vontade das partes, ou de seu controle razoável,  incluindo casos fortuitos e/ou eventos de força maior.</p>' +
                            '<p><strong>PARÁGRAFO QUARTO: </strong>Fica  terminantemente proibido ao <strong>CONTRATANTE, </strong>realizar a contratação de qualquer empregado/parceiro do <strong>CONTRATADO</strong>, que esteja ou tenha se  envolvido na prestação de serviços ou cumprimento de qualquer obrigação  estabelecida neste instrumento, seja durante a vigência do presente e por um  período de 05 (cinco) anos contados do seu término/rescisão.</p>' +
                            '<p><strong>PARÁGRAFO QUINTO: </strong>Qualquer  prejuízo que o <strong>CONTRATANTE,</strong> vier a  experimentar, pelo uso inadequado e ou cadastramento incorreto de dados  bancários (boletos) não será de responsabilidade do <strong>CONTRATADO.</strong></p>' +
                            '<p><strong>PARÁGRAFO SEXTO: </strong>O <strong>CONTRATADO</strong> não estará obrigado a  efetuar qualquer ressarcimento financeiro que venha ser solicitado pelo <strong>CONTRATANTE, </strong>posto ser de conhecimento  das partes que os valores pagos se referem a serviços já realizados pelo <strong>CONTRATADO </strong>em função da utilização  periódica do sistema.</p>' +
                            '<p><strong>PARÁGRAFO SÉTIMO: </strong>A  responsabilidade do <strong>CONTRATADO</strong> por  quaisquer eventuais prejuízos ou danos, de qualquer natureza, comprovadamente  resultantes da concessão da licença e prestação dos serviços, de acordo com  este contrato, não excederá o preço de implantação estabelecido na cláusula 2ª  deste.</p>' +
                            '<p><strong>CLÁUSULA 9ª - </strong>O <strong>CONTRATADO</strong>, não terá qualquer  responsabilidade perante o <strong>CONTRATANTE</strong> e/ou terceiros, no tocante a qualquer ação que resulte de:</p>' +
                            '<ol>' +
                            '  <li>Qualquer  violação pelo <strong>CONTRATANTE</strong> de suas  obrigações descritas neste contrato;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Mau  uso do sistema, caracterizado pelo uso em desacordo com as especificações  técnicas aplicáveis;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Qualquer  alteração, modificação ou ajuste do objeto do presente contrato executado, por  terceiro que não autorizado por escrito pelo <strong>CONTRATADO</strong>;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<ol>' +
                            '  <li>Combinação,  conexão, operação ou uso de qualquer componente do sistema com equipamento ou  documentação não fornecido pelo <strong>CONTRATADO</strong>;</li>' +
                            '</ol>' +
                            '<ol>' +
                            '  <li>Liberação  pelo <strong>CONTRATANTE</strong> de acesso ao sistema  por terceiros em seu estabelecimento ou em qualquer outro lugar ou equipamento  que o sistema possa ser acessado;</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            '<p><strong>CLÁUSULA 10ª - </strong>A  presente avença não poderá, sob nenhum aspecto, ser interpretada como uma  associação ou um ato de sociedade entre as partes, para todo e qualquer fim de  direito.</p>' +
                            '<p><strong>CLÁUSULA 11ª - </strong>A  CONTRATADA não se responsabiliza por imagens, declarações e informações  postadas no SISTEMA POKER CLUBS de sua gerência;</p>' +
                            '<p><strong>CLÁUSULA 12ª - </strong>O  presente instrumento encontra-se registrado no Cartório de Registro de Títulos  e documentos, Comarca de Concórdia – SC.</p>' +
                            '<p><strong>CLÁUSULA 13ª - </strong>As  partes elegem o foro da Comarca de Concórdia - SC como o competente para  dirimir quaisquer dúvidas ou controvérsias advindas do presente contrato, em  preferência a qualquer outro, por mais privilegiado que seja ou venha a ser.</p>' +
                            '<p>&nbsp;</p>' +
                            '<strong><u><br clear="all" />' +
                            '</u></strong>' +
                            '<p align="center"><strong><u>ANEXO A<br />' +
                            '  </u></strong><br />' +
                            '  Este  Anexo é parte integrante do <strong><u>Contrato  de Prestação de Serviços e Licenciamento de Direito de Uso do SISTEMA POKER  CLUBS por prazo determinado</u></strong>, estando a ele devidamente agrupado.</p>' +
                            '<p>O  presente anexo tem como objetivo definir as características estruturais e  funcionais com as especificações quanto à versão e potencialidade do sistema  contratado, as configurações mínimas de hardware necessárias ao apropriado  funcionamento, bem como, o preço dos serviços de licença de uso e manutenção,  conforme definido abaixo.</p>' +
                            '<p><strong><u>PREÇO DA LICENÇA:</u></strong></p>' +
                            '<ol>' +
                            '  <li>Serviço  de <strong>licença de uso e</strong> <strong>manutenção</strong> no Sistema Poker Clubs, sem  limite de terminais e/ou usuários para o mesmo ponto do servidor é de <strong>R$ 129,00 (cento e vinte e nove reais)</strong>,  sendo que o pagamento ocorra até a data de vencimento de cada mês, o  CONTRATANTE pagará valor com desconto promocional que é de <strong>R$ 99,00 (noventa e nove reais), mensais (reajustado  anualmente na renovação); O valor promocional é válido para o primeiro ano de  contrato;</strong></li>' +
                            '  <li>Serviço  de <strong>liberação de login e senha e  treinamento de uso</strong> do sistema: <strong>R$ 99,00  (noventa e nove reais);</strong></li>' +
                            '  <li>As  Taxas acima descritas deveram ser pagas através de boleto bancário/cartão de  crédito/ ou outro serviço de pagamento indicado pela SAINTEC SISTEMAS.</li>' +
                            '</ol>' +
                            '<p>&nbsp;</p>' +
                            // '<p>Concórdia / SC, <? echo date("d") . ' de ' . $meses[date("n")] . ' de ' . date("Y"); ?>.</p>' +
                            '<p>&nbsp;</p>' +
                            '<p>_________________________________________<br />' +
                            '  Contratante: ' + clubenm + '<br />' +
                            '  '+ cnpjcpf + ' sob o nº ' + docmets + '<br />' +
                            '  <br />' +
                            '  <br />' +
                            '  ____________________________________________________________________________________<br />' +
                            '  Contratado:  SAINTEC APP DESENVOLVIMENTO E LICENCIAMENTO DE SISTEMAS LTDA.<br />' +
                            '  CNPJ:  31.203.005/0001-45 </p>';
                    }
                    modal({
                        type: 'confirm', //Type of Modal Box (alert | confirm | prompt | success | warning | error | info | inverted | primary)
                        title: contrato_title, //Modal Title
                        text: contrato,
                        size: 'large', //Modal Size (normal | large | small)
                        center: true, //Center Modal Box?
                        autoclose: false, //Auto Close Modal Box?
                        callback: function (result) {
                            $('#boxpageup').css('display', 'none');
                            $('#boxpagedown').css('display', 'none');
                            if (result) {
                                if (!aceitotermos[0]) {
                                    $("#bt_contrato").removeClass("bg-slate-700");
                                    $("#bt_contrato").addClass("bg-green-800");
                                    aceitotermos[0] = true;
                                    $("input[name=contract]:hidden").val('1');
                                }
                            } else {
                                if (aceitotermos[0]) {
                                    $("#bt_contrato").removeClass("bg-green-800");
                                    $("#bt_contrato").addClass("bg-slate-700");
                                    aceitotermos[0] = false;
                                    $("input[name=contract]:hidden").val('0');
                                }
                            }
                        }, //Callback Function after close Modal (ex: function(result){alert(result);})
                        onShow: function (r) {
                        }, //After show Modal function
                        closeClick: false, //Close Modal on click near the box
                        closable: true, //If Modal is closable
                        theme: 'xenon', //Modal Custom Theme
                        animate: true, //Slide animation
                        background: 'rgba(0,0,0,0.35)', //Background Color, it can be null
                        zIndex: 1050, //z-index
                        buttonText: {
                            ok: 'OK',
                            yes: 'CONCORDO OS TERMOS DO CONTRATO',
                            cancel: 'NÃO CONCORDO'
                        },
                        template: '<div class="modal-box"><div class="modal-inner"><div class="modal-title"><a class="modal-close-btn"></a></div><div class="modal-text"></div><div class="modal-buttons"></div></div></div>',
                        _classes: {
                            box: '.modal-box',
                            boxInner: ".modal-inner",
                            title: '.modal-title',
                            content: '.modal-text',
                            buttons: '.modal-buttons',
                            closebtn: '.modal-close-btn'
                        }
                    });
                }
            } else if (test=="2") {
                var politicaprivacidade = '';
                if ($(window).width()> 600) {
                    politicaprivacidade = '<div style="display:inline-block;float:none;width:100%;text-align:justify;padding:10px 10px 10px 10px">' +
                        '<p><strong>INFORMAÇÕES</strong></p>' +
                        '<p>Somos rigorosos em relação aos nossos  termos de privacidade e entendemos que você também o seja. Portanto, mantemos  seus dados em absoluto sigilo.<br />' +
                        '  Por favor, reserve alguns minutos  para ler os nossos procedimentos de privacidade de forma a se assegurar que  estamos fornecendo o melhor serviço possível.<br />' +
                        '  LEIA ATENTAMENTE ESTE CONTRATO. AO  UTILIZAR ESTA PLATAFORMA, ALÉM DE LER ESTE CONTRATO, INDICAR A ACEITAÇÃO DESTE  ACORDO, UTILIZAR O PORTAL POKER CLUBS, VOCÊ DECLARA EXPRESSAMENTE QUE ESTÁ DE  ACORDO COM OS TERMOS DESTE CONTRATO CELEBRADO COM A OFERTANTE, LEGALMENTE  VINCULANTE ENTRE AS PARTES, ACEITANDO EXPRESSAMENTE O CUMPRIMENTO DE TODOS OS  TERMOS DE USO AQUI ESTABELECIDOS E DE QUALQUER DE SUAS ALTERAÇÕES FUTURAS (QUE  SERÃO TIDAS POR ACEITAS, MEDIANTE A CONTINUIDADE DA UTILIZAÇÃO DO PORTAL POKER  CLUBS).<br />' +
                        '  Se você não concorda com os presentes  Termos de Uso, você não poderá fazer uso do PORTAL POKER CLUBS.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>CADASTRO</strong></p>' +
                        '<p>Para contratar o Portar Poker Clubs,  o Usuário deve criar uma conta no Portal <a href="http://www.pokerclubsapp.com.br">www.pokerclubsapp.com.br</a>, em &quot;SOU  CLUBE&quot;, cadastrando-se por meio desta plataforma, preenchendo manualmente  os campos requeridos, cuja efetivação do cadastramento dependerá de confirmação  do Usuário, via e-mail que lhe será enviado no momento do cadastramento, ou feita  por colaboradores da PokerClubs via contato telefônico.<br />' +
                        '  Ao se cadastrar como Usuário ou, de  qualquer outra forma, fazer uso do PokerClubs, você declara e garante à  Ofertante, que possui plena capacidade jurídica para firmar o presente  contrato, sob pena das sanções civis, administrativas e criminais cabíveis, e  se compromete perante à Ofertante que: fornecerá informações verdadeiras,  precisas, atuais e completas, e que atualizará suas informações sempre que  necessário, a fim de que seja mantida sua veracidade, integridade e precisão e  seu cadastro e uso da Plataforma do PokerClubs, onde envolve no mínimo um  número de telefone móvel e endereço de e-mail verdadeiro que são de sua  titularidade e são por você utilizados, endereço este que não sofre qualquer  tipo de restrição e que não é proibido por lei, também deverão ser fornecido  CPF, RG, Nome da Mãe e demais dados necessários para regar contrato entre as  partes e emissão de boleto de cobrança, tais como dados empresariais.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>USO DA CONTA NA PLATAFORMA POKERCLUBS</strong></p>' +
                        '<p>O Usuário utilizará a Plataforma do  PokerClubs, obrigando-se a não revelar os dados de acesso a terceiros. O  Usuário reconhece e afirma que todos os acessos realizados após a autenticação  digital bem sucedida são interpretados como tendo sido feitos por ele próprio  de forma irrepudiável. Por isso, o Usuário será responsável por todos os  acessos e operações praticados em sua Conta na Plataforma do PokerClubs,  inclusive aqueles derivados de uso indevido ou divulgação para terceiros.<br />' +
                        '  Caso o Usuário suspeite que a  confidencialidade de sua senha foi quebrada, deverá proceder sua troca o mais  rápido possível. Se não conseguir acessar a Internet, deverá buscar atendimento  dentre os canais disponíveis.<br />' +
                        '  O Usuário é ciente de que toda e  qualquer comunicação que deseje estabelecer com a Ofertante – como, por  exemplo, dúvidas, sugestões, suporte, etc. – deverá ser feita mediante resposta  ao e-mail que será enviado ao Usuário, no momento da assinatura.<br />' +
                        '  As respostas às comunicações  estabelecidas com a Ofertante, por meio do do e-mail, serão enviadas para o  e-mail constante do cadastro do Usuário, sendo considerada válida a comunicação  ainda que o e-mail do Usuário esteja desatualizado, visto que o Usuário  reconhece ser de sua responsabilidade a atualização e a veracidade das  informações fornecidas à Ofertante.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>COOKIES</strong></p>' +
                        '<p>A Saintec Sistemas por vezes usa  cookies para armazenar e monitorar informações e preferências de seus usuários.  Esses cookies nos permitem personalizar em termos visuais suas visitas ao nosso  ambiente on-line, tão logo você seja identificado. Além disto, podemos dessa  forma monitorar seus acessos e entender o que você busca, quanto tempo fica em  cada página, dentre outras coisas importantes para sempre o atendermos da  melhor forma possível.<br />' +
                        '  A despeito da maioria dos navegadores  de internet estarem inicialmente configurados para aceitar cookies, se você preferir  pode negar seu armazenamento em disco rígido, utilizando alguma forma  alternativa. Contudo, todos os usuários devem entender que áreas do  ambiente&nbsp;<u>pokerclubsapp.com.br </u>e seus subdomínios estão sujeitas a um funcionamento não otimizado neste  caso.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>ENVIO DE DADOS</strong></p>' +
                        '<p>Você não será requisitado a informar  nada além de seus dados pessoais e cadastrais de sua empresa, quando houver,  usuário e senha para acessar a área exclusiva de assinantes de nosso website.  Seus dados cadastrais, informados no momento da contratação de seu plano,  permanecem em sigilo conosco e não serão trocados via e-mail em nenhuma  hipótese.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>COMPRAS ON-LINE</strong></p>' +
                        '<p>Todos os dados que você nos enviar  pela internet através de formulários de nosso website serão transferidos a nós  via SSL – um protocolo encriptado – e armazenados em bancos de dados que não  podem ser acessados em ambiente externo ao nosso firewall.<br />' +
                        '  Em outras palavras, todo e qualquer  dado que você nos enviar estará devidamente codificado de forma a não poder ser  decifrado e lido por quaisquer agentes mal intencionados. Nosso firewall é um  mecanismo que previne acesso aos nossos servidores por pessoas que não  componham o quadro de funcionários da Saintec Sistemas, e está ativo  ininterruptamente.<br />' +
                        '  A Saintec Sistemas não vende ou aluga  seus contatos a absolutamente ninguém, ou seja, apenas nossos funcionários  poderão eventualmente ter acesso aos dados que você fornecer. Nós poderemos  ocasionalmente utilizar seu nome e endereço de e-mail para enviar informações  relativas a conteúdo do PokerClubs que possam lhe interessar. Se, a qualquer  tempo você desejar ser removido de nossa lista de distribuição de conteúdo  gratuito, por favor nos contate via e-mail para contatar nosso departamento de  atendimento ao assinante.</p>' +
                        '<p><strong>E-MAIL</strong><br />' +
                        '  Aceitando este termo você opta por  receber nossas&nbsp;<em>newsletters</em>, seu nome e endereço de e-mail serão  armazenados em um banco de dados interno. Bem como toda e qualquer outra  informação pessoal, apenas funcionários da Saintec Sistemas poderão ter acesso  a esses dados.<br />' +
                        '  Todo o conteúdo de e-mail por você  enviado à Saintec Sistemas é confidencial. Não adicionaremos o seu endereço de  e-mail às nossas listas de distribuição sem sua prévia permissão. Por razões de  segurança, caso sua requisição seja sobre apenas uma de nossas&nbsp;<em>newsletters</em>,  nós poderemos requisitar outras informações além das previamente fornecidas de  forma a averiguar sua identidade. É importante ressaltar que neste caso nós  confirmaremos apenas informações que você já tenha previamente nos informado.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>MENSAGENS DE E-MAIL NÃO SOLICITADAS</strong></p>' +
                        '<p>Política contra a promoção de  nosso&nbsp;<em>website</em>&nbsp;utilizando mensagens de e-mail não desejadas<br />' +
                        '  Nós exigimos que todas as mensagens  de e-mail promovendo o PokerClubs ou seus produtos sejam enviadas apenas a  pessoas que já tenham aceitado receber tal conteúdo. É expressamente proibido  qualquer tipo de promoção da marca da Saintec Sistemas e da PokerClubs ou de  nosso website através de mensagens de e-mail indesejadas. O não cumprimento  desta política resultará no cancelamento de parceria e/ou rescisão de conta  afiliada.<br />' +
                        '  Caso você entenda que já tenha  recebido alguma mensagem não solicitada por parte da Saintec Sistemas, por  favor, envie-nos seu relato por e-mail através do&nbsp;<u><a href="mailto:contato@pokerclubsapp.com.br">contato@pokerclubsapp.com.br</a></u>. Nós analisaremos o conteúdo de imediato. Se não desejar mais receber  nenhuma de nossas&nbsp;<em>newsletters</em>, basta clicar no link na parte  inferior de todos os nossos e-mails enviados.</p>' +
                        '<p><strong>SMS E WHATSAPP</strong></p>' +
                        '<p>Esporadicamente, a Saintec Sistemas e  o PokerClubs poderão enviar mensagens de texto aos assinantes cadastrados. As  mensagens poderão conter ofertas de assinaturas, avisos ou informações  relevantes. Nós não compartilharemos os seus dados telefônicos com ninguém.<br />' +
                        '  A Saintec Sistemas e o PokerClubs se  utilizam da ferramenta WhatsApp Business e tele vendas como canais de vendas e  para comunicações informativas com os assinantes.<br />' +
                        '  Caso você não queira mais receber  comunicações através desses meios, envie-nos seu relato por e-mail, endereçado  para&nbsp;<u><a href="mailto:contato@pokerclubsapp.com.br">contato@pokerclubsapp.com.br</a></u>. Nós analisaremos o conteúdo de imediato.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>PUPLICAÇÕES E USO DE IMAGENS</strong></p>' +
                        '<p>O assinante responsabilizar-se  legalmente pelos dados e informações armazenados no sistema contratado,  inclusive por imagens e declarações postadas no SISTEMA POKER CLUBS de sua  gerência, isentando assim de qualquer responsabilidade de danos a terceiros por  tais usos indevidos desses dados, a SAINTEC SISTEMAS.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>PROPRIEDADE MATERIAL E INTELECTUAL</strong></p>' +
                        '<p>Todo conteúdo disponível no Portal  PokerClubs, incluindo, mas não se limitando a: marcas, logotipos, textos,  gráficos, fotografias, vídeos, conteúdos de áudio, telas, programas de  computador, bancos de dados, arquivos de transmissão e demais instrumentos que  permitam ao Usuário acessar e utilizar sua conta são de titularidade exclusiva  da Saintec Sistemas ou de parceiros comerciais dela, conforme o caso. É  proibida a sua cópia, reprodução, distribuição, transmissão, publicação,  conexão ou qualquer outro tipo de uso sem a prévia e expressa autorização do  detentor dos respectivos direitos.<br />' +
                        '  O Usuário declara e reconhece que a  reprodução de qualquer conteúdo do Portal PokerClubs não lhe confere sua  titularidade.<br />' +
                        '  Quaisquer marcas exibidas no Portal  PokerClubs, ou qualquer outro site operado em conjunto com o Portal PokerClubs  não devem ser consideradas como de domínio público e são de titularidade  exclusiva da Saintec Sistemas ou de parceiros comerciais dela, conforme o caso.<br />' +
                        '  A reprodução do conteúdo disponível  no Portal PokerClubs sem a devida autorização, ou ainda sem a devida citação e  referência ao Portal PokerClubs constituirá infração aos direitos de  propriedade intelectual e sujeitará o Usuário às sanções administrativas, civis  e criminais cabíveis.<br />' +
                        '  As funcionalidades que compõem o Portal  PokerClubs são oferecidas na forma de prestação de serviço, não conferindo ao  Usuário nenhum direito sobre o software utilizado pela Saintec Sistemas ou  sobre suas estruturas de informática que sustentam o Portal PokerClubs.<br />' +
                        '  Exceto nas hipóteses explicitamente  permitidas nestes Termos de Uso, é expressamente vedado qualquer tipo de  utilização, exploração, cópia, criação de trabalhos derivados, transmissão,  publicação, ligação, ligação direta, redistribuição, venda, descompilação,  modificação, engenharia reversa, tradução ou desmontagem do software ou de  conteúdo da plataforma.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>OS  DADOS COLETADOS DOS USUÁRIOS PODERÃO SER UTILIZADOS PARA AS SEGUINTES  FINALIDADES:</strong></p>' +
                        '<p>Identificar e autenticá-los  adequadamente;<br />' +
                        '  Atender adequadamente às suas  solicitações e dúvidas;<br />' +
                        '  Manter atualizados seus cadastros  para fins de contato por telefone, correio eletrônico, SMS, mala direta ou por  outros meios de comunicação;<br />' +
                        '  Aperfeiçoar o uso e a experiência  interativa durante sua navegação no Portal PokerClubs;<br />' +
                        '  Efetuar estatísticas, estudos,  pesquisas e levantamentos pertinentes às atividades de seus comportamentos ao  utilizarem o Portal PokerClubs, realizando tais operações de forma anonimizada;<br />' +
                        '  Promover os serviços do Portal  PokerClubs e de seus parceiros, além de informar sobre novidades,  funcionalidades, conteúdos, notícias e demais informações relevantes para a  manutenção do relacionamento com a Ofertante;<br />' +
                        '  Resguardar a Ofertante direitos e  obrigações relacionadas ao uso do Portal PokerClubs;<br />' +
                        '  Colaborar e/ou cumprir ordem judicial  ou requisição por autoridade administrativa.<br />' +
                        '  Ao aceitar estes Termos de Uso  e&nbsp;Política&nbsp;de Privacidade, o Usuário concede à Ofertante licença para  sublicenciar, usar, copiar, modificar, criar obras derivadas, distribuir,  publicar, exibir, executar em público e, de qualquer outro modo, explorar  comercialmente, nos formatos e canais de distribuição atualmente conhecidos ou  que venham a ser criados, toda e qualquer informação, texto, mensagens, fotos  ou material fornecido pelo Usuário, sendo desnecessário aviso prévio ou  consentimento. Esta licença se dá de forma mundial, perpétua, irrevogável,  transferível, isenta de royalties ou de qualquer outro pagamento ao Usuário ou  outra pessoa.<br />' +
                        '  O Portal PokerClubs utiliza  ferramentas de monitoramento online comuns, tais como cookies, com objetivo de  acompanhar as interações dos Usuários com o Programa. Neste monitoramento podem  ser incluídas informações como o endereço de protocolo internet (IP), histórico  de navegação, tipo de aparelho, operadora ou provedor de acesso à internet,  localização física do Usuário, entre outras, dentro dos limites legais.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>O PORTAL POKERCLUBS REGISTRARÁ AS ATIVIDADES EFETUADAS PELO USUÁRIO  CRIANDO LOGS QUE CONTERÃO:</strong></p>' +
                        '<p>Endereço IP do Usuário;<br />' +
                        '  Ações efetuadas pelo Usuário no Portal  PokerClubs;<br />' +
                        '  Funções e ferramentas acessadas e  utilizadas pelo Usuário no Portal PokerClubs;<br />' +
                        '  Datas e horários de cada ação do  Usuário no Portal PokerClubs;<br />' +
                        '  Session ID, quando disponível.<br />' +
                        '  As informações sobre os Usuários e  seus respectivos hábitos de consumo poderão ser coletadas e armazenadas por  outras formas de interação do Usuário com o Portal PokerClubs, tais como, mas  não somente, durante o cadastro para criação de conta, envio de e-mails via Portal  PokerClubs, ou para a Ofertante.<br />' +
                        '  Desde já o Usuário fica ciente que a  Ofertante e/ou parceiros comerciais dela poderão promover enriquecimento da sua  base de dados, adicionando informações oriundas de outras fontes legítimas,  localizadas no Brasil e no exterior, manifestando seu consentimento expresso ao  concordar com estes Termo de Uso.<br />' +
                        '  O Usuário poderá, a qualquer tempo,  editar os dados pessoais que lhe dizem respeito constantes junto ao Portal  PokerClubs, por meio de ferramenta própria para edição do Perfil do Usuário.<br />' +
                        '  Pela mesma ferramenta de edição do  Perfil do Usuário, o Usuário poderá requerer a exclusão de seu cadastro no Portal  PokerClubs, ocasionando a exclusão de todos os seus dados pessoais coletados e  registrados pelo Portal PokerClubs, desde que esteja com todos os seus débitos  junto a Ofertante quitados e que o contrato entre as partes não esteja mais em  vigor.<br />' +
                        '  Em caso de auditoria e eventual  preservação de direitos, a Ofertante poderá permanecer com o histórico de  registro dos dados do Usuário pelo período máximo de 5 (cinco) anos, podendo  ser estendido por prazo maior nas hipóteses que a lei ou norma regulatória  assim estabelecer ou para preservação de direitos, possuindo a Ofertante  faculdade de excluí-los definitivamente segundo sua conveniência em prazo  menor.<br />' +
                        '  Pelo presente, o Usuário declara que  leu e entendeu todos os termos e que concorda com a&nbsp;Política&nbsp;de  Privacidade, e que também concorda com a coleta e uso de suas informações  pessoais e não pessoais para os propósitos estabelecidos nos Termos de Uso  e&nbsp;Política&nbsp;de Privacidade.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>LEI APLICÁVEL E FORO PARA RESOLUÇÃO DE CONTROVÉRSIAS</strong></p>' +
                        '<p>Os Termos e Condições de Uso aqui  descritos são interpretados segundo a legislação brasileira, no idioma  português, sendo eleito o Foro da Comarca de Concórdia do estado de Santa  Catarina para dirimir qualquer litígio ou controvérsia envolvendo o presente  documento, salvo ressalva específica de competência pessoal, territorial ou  funcional pela legislação aplicável.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p>&nbsp;</p>' +
                        '<p>Concórdia / SC, <? echo date("d") . ' de ' . $meses[date("n")] . ' de ' . date("Y"); ?>.</p>' +
                        '<p>&nbsp;</p>' +
                        '</div>';
                } else {
                    $('#boxpagedown').css('display', 'block');
                    politicaprivacidade = '<p><strong>INFORMAÇÕES</strong></p>' +
                        '<p>Somos rigorosos em relação aos nossos  termos de privacidade e entendemos que você também o seja. Portanto, mantemos  seus dados em absoluto sigilo.<br />' +
                        '  Por favor, reserve alguns minutos  para ler os nossos procedimentos de privacidade de forma a se assegurar que  estamos fornecendo o melhor serviço possível.<br />' +
                        '  LEIA ATENTAMENTE ESTE CONTRATO. AO  UTILIZAR ESTA PLATAFORMA, ALÉM DE LER ESTE CONTRATO, INDICAR A ACEITAÇÃO DESTE  ACORDO, UTILIZAR O PORTAL POKER CLUBS, VOCÊ DECLARA EXPRESSAMENTE QUE ESTÁ DE  ACORDO COM OS TERMOS DESTE CONTRATO CELEBRADO COM A OFERTANTE, LEGALMENTE  VINCULANTE ENTRE AS PARTES, ACEITANDO EXPRESSAMENTE O CUMPRIMENTO DE TODOS OS  TERMOS DE USO AQUI ESTABELECIDOS E DE QUALQUER DE SUAS ALTERAÇÕES FUTURAS (QUE  SERÃO TIDAS POR ACEITAS, MEDIANTE A CONTINUIDADE DA UTILIZAÇÃO DO PORTAL POKER  CLUBS).<br />' +
                        '  Se você não concorda com os presentes  Termos de Uso, você não poderá fazer uso do PORTAL POKER CLUBS.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>CADASTRO</strong></p>' +
                        '<p>Para contratar o Portar Poker Clubs,  o Usuário deve criar uma conta no Portal <a href="http://www.pokerclubsapp.com.br">www.pokerclubsapp.com.br</a>, em &quot;SOU  CLUBE&quot;, cadastrando-se por meio desta plataforma, preenchendo manualmente  os campos requeridos, cuja efetivação do cadastramento dependerá de confirmação  do Usuário, via e-mail que lhe será enviado no momento do cadastramento, ou feita  por colaboradores da PokerClubs via contato telefônico.<br />' +
                        '  Ao se cadastrar como Usuário ou, de  qualquer outra forma, fazer uso do PokerClubs, você declara e garante à  Ofertante, que possui plena capacidade jurídica para firmar o presente  contrato, sob pena das sanções civis, administrativas e criminais cabíveis, e  se compromete perante à Ofertante que: fornecerá informações verdadeiras,  precisas, atuais e completas, e que atualizará suas informações sempre que  necessário, a fim de que seja mantida sua veracidade, integridade e precisão e  seu cadastro e uso da Plataforma do PokerClubs, onde envolve no mínimo um  número de telefone móvel e endereço de e-mail verdadeiro que são de sua  titularidade e são por você utilizados, endereço este que não sofre qualquer  tipo de restrição e que não é proibido por lei, também deverão ser fornecido  CPF, RG, Nome da Mãe e demais dados necessários para regar contrato entre as  partes e emissão de boleto de cobrança, tais como dados empresariais.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>USO DA CONTA NA PLATAFORMA POKERCLUBS</strong></p>' +
                        '<p>O Usuário utilizará a Plataforma do  PokerClubs, obrigando-se a não revelar os dados de acesso a terceiros. O  Usuário reconhece e afirma que todos os acessos realizados após a autenticação  digital bem sucedida são interpretados como tendo sido feitos por ele próprio  de forma irrepudiável. Por isso, o Usuário será responsável por todos os  acessos e operações praticados em sua Conta na Plataforma do PokerClubs,  inclusive aqueles derivados de uso indevido ou divulgação para terceiros.<br />' +
                        '  Caso o Usuário suspeite que a  confidencialidade de sua senha foi quebrada, deverá proceder sua troca o mais  rápido possível. Se não conseguir acessar a Internet, deverá buscar atendimento  dentre os canais disponíveis.<br />' +
                        '  O Usuário é ciente de que toda e  qualquer comunicação que deseje estabelecer com a Ofertante – como, por  exemplo, dúvidas, sugestões, suporte, etc. – deverá ser feita mediante resposta  ao e-mail que será enviado ao Usuário, no momento da assinatura.<br />' +
                        '  As respostas às comunicações  estabelecidas com a Ofertante, por meio do do e-mail, serão enviadas para o  e-mail constante do cadastro do Usuário, sendo considerada válida a comunicação  ainda que o e-mail do Usuário esteja desatualizado, visto que o Usuário  reconhece ser de sua responsabilidade a atualização e a veracidade das  informações fornecidas à Ofertante.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>COOKIES</strong></p>' +
                        '<p>A Saintec Sistemas por vezes usa  cookies para armazenar e monitorar informações e preferências de seus usuários.  Esses cookies nos permitem personalizar em termos visuais suas visitas ao nosso  ambiente on-line, tão logo você seja identificado. Além disto, podemos dessa  forma monitorar seus acessos e entender o que você busca, quanto tempo fica em  cada página, dentre outras coisas importantes para sempre o atendermos da  melhor forma possível.<br />' +
                        '  A despeito da maioria dos navegadores  de internet estarem inicialmente configurados para aceitar cookies, se você preferir  pode negar seu armazenamento em disco rígido, utilizando alguma forma  alternativa. Contudo, todos os usuários devem entender que áreas do  ambiente&nbsp;<u>pokerclubsapp.com.br </u>e seus subdomínios estão sujeitas a um funcionamento não otimizado neste  caso.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>ENVIO DE DADOS</strong></p>' +
                        '<p>Você não será requisitado a informar  nada além de seus dados pessoais e cadastrais de sua empresa, quando houver,  usuário e senha para acessar a área exclusiva de assinantes de nosso website.  Seus dados cadastrais, informados no momento da contratação de seu plano,  permanecem em sigilo conosco e não serão trocados via e-mail em nenhuma  hipótese.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>COMPRAS ON-LINE</strong></p>' +
                        '<p>Todos os dados que você nos enviar  pela internet através de formulários de nosso website serão transferidos a nós  via SSL – um protocolo encriptado – e armazenados em bancos de dados que não  podem ser acessados em ambiente externo ao nosso firewall.<br />' +
                        '  Em outras palavras, todo e qualquer  dado que você nos enviar estará devidamente codificado de forma a não poder ser  decifrado e lido por quaisquer agentes mal intencionados. Nosso firewall é um  mecanismo que previne acesso aos nossos servidores por pessoas que não  componham o quadro de funcionários da Saintec Sistemas, e está ativo  ininterruptamente.<br />' +
                        '  A Saintec Sistemas não vende ou aluga  seus contatos a absolutamente ninguém, ou seja, apenas nossos funcionários  poderão eventualmente ter acesso aos dados que você fornecer. Nós poderemos  ocasionalmente utilizar seu nome e endereço de e-mail para enviar informações  relativas a conteúdo do PokerClubs que possam lhe interessar. Se, a qualquer  tempo você desejar ser removido de nossa lista de distribuição de conteúdo  gratuito, por favor nos contate via e-mail para contatar nosso departamento de  atendimento ao assinante.</p>' +
                        '<p><strong>E-MAIL</strong><br />' +
                        '  Aceitando este termo você opta por  receber nossas&nbsp;<em>newsletters</em>, seu nome e endereço de e-mail serão  armazenados em um banco de dados interno. Bem como toda e qualquer outra  informação pessoal, apenas funcionários da Saintec Sistemas poderão ter acesso  a esses dados.<br />' +
                        '  Todo o conteúdo de e-mail por você  enviado à Saintec Sistemas é confidencial. Não adicionaremos o seu endereço de  e-mail às nossas listas de distribuição sem sua prévia permissão. Por razões de  segurança, caso sua requisição seja sobre apenas uma de nossas&nbsp;<em>newsletters</em>,  nós poderemos requisitar outras informações além das previamente fornecidas de  forma a averiguar sua identidade. É importante ressaltar que neste caso nós  confirmaremos apenas informações que você já tenha previamente nos informado.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>MENSAGENS DE E-MAIL NÃO SOLICITADAS</strong></p>' +
                        '<p>Política contra a promoção de  nosso&nbsp;<em>website</em>&nbsp;utilizando mensagens de e-mail não desejadas<br />' +
                        '  Nós exigimos que todas as mensagens  de e-mail promovendo o PokerClubs ou seus produtos sejam enviadas apenas a  pessoas que já tenham aceitado receber tal conteúdo. É expressamente proibido  qualquer tipo de promoção da marca da Saintec Sistemas e da PokerClubs ou de  nosso website através de mensagens de e-mail indesejadas. O não cumprimento  desta política resultará no cancelamento de parceria e/ou rescisão de conta  afiliada.<br />' +
                        '  Caso você entenda que já tenha  recebido alguma mensagem não solicitada por parte da Saintec Sistemas, por  favor, envie-nos seu relato por e-mail através do&nbsp;<u><a href="mailto:contato@pokerclubsapp.com.br">contato@pokerclubsapp.com.br</a></u>. Nós analisaremos o conteúdo de imediato. Se não desejar mais receber  nenhuma de nossas&nbsp;<em>newsletters</em>, basta clicar no link na parte  inferior de todos os nossos e-mails enviados.</p>' +
                        '<p><strong>SMS E WHATSAPP</strong></p>' +
                        '<p>Esporadicamente, a Saintec Sistemas e  o PokerClubs poderão enviar mensagens de texto aos assinantes cadastrados. As  mensagens poderão conter ofertas de assinaturas, avisos ou informações  relevantes. Nós não compartilharemos os seus dados telefônicos com ninguém.<br />' +
                        '  A Saintec Sistemas e o PokerClubs se  utilizam da ferramenta WhatsApp Business e tele vendas como canais de vendas e  para comunicações informativas com os assinantes.<br />' +
                        '  Caso você não queira mais receber  comunicações através desses meios, envie-nos seu relato por e-mail, endereçado  para&nbsp;<u><a href="mailto:contato@pokerclubsapp.com.br">contato@pokerclubsapp.com.br</a></u>. Nós analisaremos o conteúdo de imediato.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>PUPLICAÇÕES E USO DE IMAGENS</strong></p>' +
                        '<p>O assinante responsabilizar-se  legalmente pelos dados e informações armazenados no sistema contratado,  inclusive por imagens e declarações postadas no SISTEMA POKER CLUBS de sua  gerência, isentando assim de qualquer responsabilidade de danos a terceiros por  tais usos indevidos desses dados, a SAINTEC SISTEMAS.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>PROPRIEDADE MATERIAL E INTELECTUAL</strong></p>' +
                        '<p>Todo conteúdo disponível no Portal  PokerClubs, incluindo, mas não se limitando a: marcas, logotipos, textos,  gráficos, fotografias, vídeos, conteúdos de áudio, telas, programas de  computador, bancos de dados, arquivos de transmissão e demais instrumentos que  permitam ao Usuário acessar e utilizar sua conta são de titularidade exclusiva  da Saintec Sistemas ou de parceiros comerciais dela, conforme o caso. É  proibida a sua cópia, reprodução, distribuição, transmissão, publicação,  conexão ou qualquer outro tipo de uso sem a prévia e expressa autorização do  detentor dos respectivos direitos.<br />' +
                        '  O Usuário declara e reconhece que a  reprodução de qualquer conteúdo do Portal PokerClubs não lhe confere sua  titularidade.<br />' +
                        '  Quaisquer marcas exibidas no Portal  PokerClubs, ou qualquer outro site operado em conjunto com o Portal PokerClubs  não devem ser consideradas como de domínio público e são de titularidade  exclusiva da Saintec Sistemas ou de parceiros comerciais dela, conforme o caso.<br />' +
                        '  A reprodução do conteúdo disponível  no Portal PokerClubs sem a devida autorização, ou ainda sem a devida citação e  referência ao Portal PokerClubs constituirá infração aos direitos de  propriedade intelectual e sujeitará o Usuário às sanções administrativas, civis  e criminais cabíveis.<br />' +
                        '  As funcionalidades que compõem o Portal  PokerClubs são oferecidas na forma de prestação de serviço, não conferindo ao  Usuário nenhum direito sobre o software utilizado pela Saintec Sistemas ou  sobre suas estruturas de informática que sustentam o Portal PokerClubs.<br />' +
                        '  Exceto nas hipóteses explicitamente  permitidas nestes Termos de Uso, é expressamente vedado qualquer tipo de  utilização, exploração, cópia, criação de trabalhos derivados, transmissão,  publicação, ligação, ligação direta, redistribuição, venda, descompilação,  modificação, engenharia reversa, tradução ou desmontagem do software ou de  conteúdo da plataforma.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>OS  DADOS COLETADOS DOS USUÁRIOS PODERÃO SER UTILIZADOS PARA AS SEGUINTES  FINALIDADES:</strong></p>' +
                        '<p>Identificar e autenticá-los  adequadamente;<br />' +
                        '  Atender adequadamente às suas  solicitações e dúvidas;<br />' +
                        '  Manter atualizados seus cadastros  para fins de contato por telefone, correio eletrônico, SMS, mala direta ou por  outros meios de comunicação;<br />' +
                        '  Aperfeiçoar o uso e a experiência  interativa durante sua navegação no Portal PokerClubs;<br />' +
                        '  Efetuar estatísticas, estudos,  pesquisas e levantamentos pertinentes às atividades de seus comportamentos ao  utilizarem o Portal PokerClubs, realizando tais operações de forma anonimizada;<br />' +
                        '  Promover os serviços do Portal  PokerClubs e de seus parceiros, além de informar sobre novidades,  funcionalidades, conteúdos, notícias e demais informações relevantes para a  manutenção do relacionamento com a Ofertante;<br />' +
                        '  Resguardar a Ofertante direitos e  obrigações relacionadas ao uso do Portal PokerClubs;<br />' +
                        '  Colaborar e/ou cumprir ordem judicial  ou requisição por autoridade administrativa.<br />' +
                        '  Ao aceitar estes Termos de Uso  e&nbsp;Política&nbsp;de Privacidade, o Usuário concede à Ofertante licença para  sublicenciar, usar, copiar, modificar, criar obras derivadas, distribuir,  publicar, exibir, executar em público e, de qualquer outro modo, explorar  comercialmente, nos formatos e canais de distribuição atualmente conhecidos ou  que venham a ser criados, toda e qualquer informação, texto, mensagens, fotos  ou material fornecido pelo Usuário, sendo desnecessário aviso prévio ou  consentimento. Esta licença se dá de forma mundial, perpétua, irrevogável,  transferível, isenta de royalties ou de qualquer outro pagamento ao Usuário ou  outra pessoa.<br />' +
                        '  O Portal PokerClubs utiliza  ferramentas de monitoramento online comuns, tais como cookies, com objetivo de  acompanhar as interações dos Usuários com o Programa. Neste monitoramento podem  ser incluídas informações como o endereço de protocolo internet (IP), histórico  de navegação, tipo de aparelho, operadora ou provedor de acesso à internet,  localização física do Usuário, entre outras, dentro dos limites legais.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>O PORTAL POKERCLUBS REGISTRARÁ AS ATIVIDADES EFETUADAS PELO USUÁRIO  CRIANDO LOGS QUE CONTERÃO:</strong></p>' +
                        '<p>Endereço IP do Usuário;<br />' +
                        '  Ações efetuadas pelo Usuário no Portal  PokerClubs;<br />' +
                        '  Funções e ferramentas acessadas e  utilizadas pelo Usuário no Portal PokerClubs;<br />' +
                        '  Datas e horários de cada ação do  Usuário no Portal PokerClubs;<br />' +
                        '  Session ID, quando disponível.<br />' +
                        '  As informações sobre os Usuários e  seus respectivos hábitos de consumo poderão ser coletadas e armazenadas por  outras formas de interação do Usuário com o Portal PokerClubs, tais como, mas  não somente, durante o cadastro para criação de conta, envio de e-mails via Portal  PokerClubs, ou para a Ofertante.<br />' +
                        '  Desde já o Usuário fica ciente que a  Ofertante e/ou parceiros comerciais dela poderão promover enriquecimento da sua  base de dados, adicionando informações oriundas de outras fontes legítimas,  localizadas no Brasil e no exterior, manifestando seu consentimento expresso ao  concordar com estes Termo de Uso.<br />' +
                        '  O Usuário poderá, a qualquer tempo,  editar os dados pessoais que lhe dizem respeito constantes junto ao Portal  PokerClubs, por meio de ferramenta própria para edição do Perfil do Usuário.<br />' +
                        '  Pela mesma ferramenta de edição do  Perfil do Usuário, o Usuário poderá requerer a exclusão de seu cadastro no Portal  PokerClubs, ocasionando a exclusão de todos os seus dados pessoais coletados e  registrados pelo Portal PokerClubs, desde que esteja com todos os seus débitos  junto a Ofertante quitados e que o contrato entre as partes não esteja mais em  vigor.<br />' +
                        '  Em caso de auditoria e eventual  preservação de direitos, a Ofertante poderá permanecer com o histórico de  registro dos dados do Usuário pelo período máximo de 5 (cinco) anos, podendo  ser estendido por prazo maior nas hipóteses que a lei ou norma regulatória  assim estabelecer ou para preservação de direitos, possuindo a Ofertante  faculdade de excluí-los definitivamente segundo sua conveniência em prazo  menor.<br />' +
                        '  Pelo presente, o Usuário declara que  leu e entendeu todos os termos e que concorda com a&nbsp;Política&nbsp;de  Privacidade, e que também concorda com a coleta e uso de suas informações  pessoais e não pessoais para os propósitos estabelecidos nos Termos de Uso  e&nbsp;Política&nbsp;de Privacidade.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p><strong>LEI APLICÁVEL E FORO PARA RESOLUÇÃO DE CONTROVÉRSIAS</strong></p>' +
                        '<p>Os Termos e Condições de Uso aqui  descritos são interpretados segundo a legislação brasileira, no idioma  português, sendo eleito o Foro da Comarca de Concórdia do estado de Santa  Catarina para dirimir qualquer litígio ou controvérsia envolvendo o presente  documento, salvo ressalva específica de competência pessoal, territorial ou  funcional pela legislação aplicável.</p>' +
                        '<p>&nbsp;</p>' +
                        '<p>&nbsp;</p>' +
                        '<p>Concórdia / SC, <? echo date("d") . ' de ' . $meses[date("n")] . ' de ' . date("Y"); ?>.</p>' +
                        '<p>&nbsp;</p>';
                }
                modal({
                    type: 'confirm', //Type of Modal Box (alert | confirm | prompt | success | warning | error | info | inverted | primary)
                    title: 'POLITICA DE PRIVACIDADE', //Modal Title
                    text: politicaprivacidade,
                    size: 'large', //Modal Size (normal | large | small)
                    center: true, //Center Modal Box?
                    autoclose: false, //Auto Close Modal Box?
                    callback: function(result){
                        $('#boxpageup').css('display', 'none');
                        $('#boxpagedown').css('display', 'none');
                        if (result) {
                            if (!aceitotermos[1]) {
                                $("#bt_politica").removeClass("bg-slate-700");
                                $("#bt_politica").addClass("bg-green-800");
                                aceitotermos[1] = true;
                                $("input[name=terms]:hidden").val('1');
                            }
                        } else {
                            if (aceitotermos[1]) {
                                $("#bt_politica").removeClass("bg-green-800");
                                $("#bt_politica").addClass("bg-slate-700");
                                aceitotermos[1] = false;
                                $("input[name=terms]:hidden").val('0');
                            }
                        }
                    }, //Callback Function after close Modal (ex: function(result){alert(result);})
                    onShow: function(r){}, //After show Modal function
                    closeClick: false, //Close Modal on click near the box
                    closable: true, //If Modal is closable
                    theme: 'xenon', //Modal Custom Theme
                    animate: true, //Slide animation
                    background: 'rgba(0,0,0,0.35)', //Background Color, it can be null
                    zIndex: 1050, //z-index
                    buttonText: {
                        ok: 'OK',
                        yes: 'CONCORDO COM OS TERMOS',
                        cancel: 'NÃO CONCORDO'
                    },
                    template: '<div class="modal-box"><div class="modal-inner"><div class="modal-title"><a class="modal-close-btn"></a></div><div class="modal-text"></div><div class="modal-buttons"></div></div></div>',
                    _classes: {
                        box: '.modal-box',
                        boxInner: ".modal-inner",
                        title: '.modal-title',
                        content: '.modal-text',
                        buttons: '.modal-buttons',
                        closebtn: '.modal-close-btn'
                    }
                });
            } else if (test=="3") {
                var msgerror = '';
                if (!aceitotermos[0]) msgerror = "<p style='text-align:center'>Para continuar aceite os termos do <strong>Contrato de Uso</strong>...</p>";
                else msgerror = "<p style='text-align:center'>Para continuar leia a <strong>Política de Privacidade</strong>...</p>";
                modal({
                    type: 'warning',
                    title: 'AVISO',
                    text: msgerror,
                    center: true,
                    buttonText: {
                        ok: 'FECHAR JANELA'
                    }
                });
            }
        }

        $("#boxpageup").bind("click", function(event) {
            event.preventDefault();
            $('#boxpageup').css('display', 'none');
            $('#boxpagedown').css('display', 'block');
            $("#modal-window").animate({
                scrollTop: $(".modal-inner").offset().top
            });
        });
        $("#boxpagedown").bind("click", function(event) {
            event.preventDefault();
            $('#boxpageup').css('display', 'block');
            $('#boxpagedown').css('display', 'none');
            $("#modal-window").animate({
                scrollTop: $(".modal-buttons").offset().top
            });
        });
    </script>
@endsection
