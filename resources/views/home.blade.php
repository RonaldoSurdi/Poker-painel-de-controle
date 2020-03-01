@extends('layouts.front')
@section('css')
    <link href="{{ asset('/my/css/home5.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('slide')


    <!-- ========== Hero Cover ========== -->
    <div class="content bg_home1">
        <div id="home" class="container app-hero" >
            <div class="container">
                <!-- Text -->
                <div class="col-sm-7 hero-text text-center">
                    <center>
                    <img src="{{asset('my/images/logo_center2.png')}}" class="img-responsive">
                    </center>
                    <p class="text-home">
                        O PokerClubs é um aplicativo moderno e desenvolvido para facilitar a<br class="hidden-sm hidden-xs">
                        vida dos amantes do Poker.  Prático, ele te leva ao clube* mais próximo<br class="hidden-sm hidden-xs">
                        através do Google Maps, Waze ou Uber. Fique por dentro de<br class="hidden-sm hidden-xs">
                        novidades, torneios, eventos e muito mais! Baixe o nosso aplicativo e<br class="hidden-sm hidden-xs">
                        tenha uma ferramenta que vai facilitar a sua vida.
                    </p>
                    <br>
                    <a href="#sobre" class="btn btn-flat btn-down text-uppercase bt-150 mt-10">SAIBA MAIS</a>
                    <b class="mr-15"></b>
                    <a href="#" data-toggle="modal" data-target="#modal_down"  class="btn btn-flat btn-down text-uppercase bt-150 mt-10">Baixe grátis</a>
                    {{--<a href="#sobre" class="btn bg-teal-800 btn-labeled btn-rounded m-5"><b><i class="icon-reading"></i></b> Saiba mais...</a>--}}
                    <br><br>
                </div><!-- / .col-lg-6 -->

                <!-- Image -->
                <div class="col-sm-5">
                    <center>
                        <div class="homelogin">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="text-center">
                                        {{--<div class="icon-object border-dourado text-dourado"><i id="ico_user" class="icon-user text-dourado"></i></div>--}}
                                        <center>
                                            <img class="img-responsive mb-15" src="{{asset('my/images/qtd_user.png')}}" id="ico_user" >
                                        </center>
                                        <h5 class="content-group">Faça login na sua conta<small class="display-block">Suas credenciais</small></h5>
                                    </div>

                                    <form onSubmit="Entrar(); return false" method="POST">
                                        <div class="form-group has-feedback has-feedback-left {{ $errors->has('user_app') ? ' has-error' : '' }}">
                                            <input type="email" class="form-control" name="user_app" id="user_app"
                                                   placeholder="Seu usuário de acesso do app" value="{{ old('user_app') }}">
                                            <div class="form-control-feedback">
                                                <i id="ico_input" class="icon-user text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback has-feedback-left {{ $errors->has('senha_app') ? ' has-error' : '' }}">
                                            <input type="password" class="form-control" name="senha_app" id="senha_app"
                                                   placeholder="Sua senha do app" value="{{ old('senha_app') }}">
                                            <div class="form-control-feedback">
                                                <i id="ico_input" class="icon-lock text-muted"></i>
                                            </div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button id="btn1" type="submit" class="btn bg-dourado btn-block">Entrar <i id="btn1_ico" class="icon-arrow-right14 position-right"></i></button>
                                            <a href="#" onclick="LogarFace()" class="btn bg-facebook btn-block btn-labeled"><b><i class="icon-facebook"></i></b> Entre com seu Facebook</a>
                                            <a href="#" data-toggle="modal" data-target="#modal_cadastro" class="btn bg-black-200 btn-block">Cadastrar-se <i class="icon-user-plus position-right"></i></a>
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
    <div id="sobre" class="bg-white">
        <div class="container app-welcome">

            <div class="row text-center">
                <br>
                <img src="{{ asset('/my/images/app-icon.png')}}">
                <h1 class="text-semibold">POSSUI UM <br class="visible-xs visible-sm hidden">CLUBE DE POKER?</h1>
                <h6 class="mb-15 col-sm-8 col-sm-offset-2 text-home">
                    Então, conheça o aplicativo PokerClubs. Com sistema de gestão integrado, o APP utiliza<br class="hidden-sm hidden-xs">
                    das melhores tecnologias do mercado e auxilia no gerenciamento de um modo prático e<br class="hidden-sm hidden-xs">
                    simples o seu negócio. Crie torneios e promoções, mantenha contato com seus usuários,<br class="hidden-sm hidden-xs">
                    envie mensagens exclusivas e muito mais com o uso do aplicativo. PokerClubs tem<br class="hidden-sm hidden-xs">
                    estratégias para agilizar seu negócio. Gerencie AGORA e torne-se um usuário Premium!
                </h6>

                <p>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/ms8IJh4XKd0?rel=0&amp;showinfo=0" frameborder="0" allow="encrypted-media" allowfullscreen style="max-width:100%"></iframe>
                </p>

                <a href="/premium/#cadpremium" class="btn btn-flat btn-down text-uppercase text-dourado border-dourado" style="width: 300px"><h4 class="no-padding no-margin">Experimente 30 dias grátis</h4></a>
            </div><!-- / .row -->

        </div><!-- / .container -->
    </div><!-- / .gray-bg -->

    <!-- ========== Features Icons + Image ========== -->
    <div id="funcoes" class="dark-bg1">
        <div class="container app-welcome text-dourado pb-5">
            <div class="row text-center">
                <div class="col-sm-3 mt-20">
                    <center><img class="img img-responsive mb-15" src="{{asset('my/images/qtd_casa.png')}}"> </center>
                    <H4 class="no-margin text-white">Clubes Cadastrados</H4>
                    <h1 class="m-5"><b id="qtd_clubs">*</b></h1>
                </div>
                <div class="col-sm-3 mt-20">
                    <center><img class="img img-responsive mb-15" src="{{asset('my/images/qtd_pais.png')}}"> </center>
                    <H4 class="no-margin text-white">Estados do Brasil</H4>
                    <h1 class="m-5"><b id="qtd_ufs">*</b></h1>
                </div>
                <div class="col-sm-3 mt-20">
                    <center><img class="img img-responsive mb-15" src="{{asset('my/images/qtd_user.png')}}"> </center>
                    <H4 class="no-margin text-white">Usuários do APP</H4>
                    <h1 class="m-5"><b id="qtd_users">*</b></h1>
                </div>
                <div class="col-sm-3 mt-20">
                    <center><img class="img img-responsive mb-15" src="{{asset('my/images/qtd_premium.png')}}"> </center>
                    <H4 class="no-margin text-white">Clubes Premiuns</H4>
                    <h1 class="m-5"><b id="qtd_premium">*</b></h1>
                </div>

            </div><!-- / .row -->

        </div><!-- / .container -->
        <div class="container-fluid">
            <hr class="hr-dourado">
        </div><!-- / .container -->
        <div class="container">

            <div class="row text-white m-20 p-20">

                <header class="col-sm-12 text-center mb-20">
                    <h1 class="text-uppercase text-dourado">Conheça o nosso Aplicativo</h1>
                    <h4>Conheça as funcionalidades do aplicativo PokerClubs e saiba como ele pode ajudar você!</h4>
                    <br><br>
                </header>

                <div class="col-sm-12 visible-sm visible-xs hidden">
                    <center>
                    <img src="{{asset('/my/images/celular_alpha.png')}}" style="max-height: 500px"
                         class="img-responsive center-block wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                    </center>
                    <ul class="media-list">
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_casa.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">O CLUBE</h4>
                                <h5 class="no-margin">Veja as comodidades que o Clube<br>
                                    oferece e demais informações.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_star.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">SEGUIR</h4>
                                <h5 class="no-margin">Siga seus Clubes favoritos e <br>
                                    tenha vantagens exclusivas.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_copas.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">PRÊMIOS</h4>
                                <h5 class="no-margin">Inscreva-se em torneios pelo App<br>
                                    e receba Prêmios exclusivos.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_copas.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">AO VIVO</h4>
                                <h5 class="no-margin">Acompanhe torneios e eventos ao<br>
                                    vivo nas transmissões do clube.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_msg.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">MENSAGENS</h4>
                                <h5 class="no-margin">Receba mensagens exclusivas <br>
                                    dos seus clubes favoritos.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_agenda.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">AGENDA</h4>
                                <h5 class="no-margin">Acompanhe a agenda semanal de<br>
                                    cada clube e os próximos torneios.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_hank.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">RANKING</h4>
                                <h5 class="no-margin">Veja a classificação por etapa e a <br>
                                    classificação final de cada torneio.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_fotos.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">FOTOS</h4>
                                <h5 class="no-margin">Galerias de fotos de cada <br>
                                    torneio ou evento do Clube.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="#"><img src="{{ asset('my/images/icon_rota.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">TRAÇAR ROTA</h4>
                                <h5 class="no-margin">Facilidade para quem não é da<br> cidade, traçe a rota até o clube.</h5>
                            </center>
                        </li>
                        <li class="media text-center">
                            <center>
                                <a href="/premium/#cadpremium"><img src="{{ asset('my/images/icon_premium.png') }}" class=""></a>
                                <h4 class="media-heading text-dourado mt-10">EXPERIMENTE</h4>
                                <h5 class="no-margin">Seja Premium grátis por
                                    <br class="visible-xs visible-sm hidden">
                                    30 dias. E tenha um gerenciador
                                    <br class="visible-xs visible-sm hidden">completo para seu clube.</h5>
                            </center>
                        </li>
                    </ul>
                </div>


                <!-- Feature 1 -->
                <div class="col-sm-4 hidden-xs hidden-sm" style="margin-left: 3%">
                    <ul class="media-list mt-20">

                        <li class="media text-right">
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">O CLUBE</h5>
                                <h6 class="no-margin">Veja as comodidades que o Clube<br>
                                    oferece e demais informações.</h6>
                            </div>
                            <div class="media-right">
                                <a href="#"><img src="{{ asset('my/images/icon_casa.png') }}" class=""></a>
                            </div>
                        </li>

                        <li class="media text-right">
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">SEGUIR</h5>
                                <h6 class="no-margin">Siga seus Clubes favoritos e <br>
                                    tenha vantagens exclusivas.</h6>
                            </div>
                            <div class="media-right">
                                <a href="#"><img src="{{ asset('my/images/icon_star.png') }}" class=""></a>
                            </div>
                        </li>


                        <li class="media text-right">
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">PRÊMIOS</h5>
                                <h6 class="no-margin">Inscreva-se em torneios pelo App<br>
                                    e receba Prêmios exclusivos.</h6>
                            </div>
                            <div class="media-right">
                                <a href="#"><img src="{{ asset('my/images/icon_copas.png') }}" class=""></a>
                            </div>
                        </li>

                        <li class="media text-right">
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">AO VIVO</h5>
                                <h6 class="no-margin">Acompanhe torneios e eventos ao <br>
                                    vivo nas transmissões do clube.</h6>
                            </div>
                            <div class="media-right">
                                <a href="#"><img src="{{ asset('my/images/icon_live.png') }}" class=""></a>
                            </div>
                        </li>
                        <li class="media text-right">
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">MENSAGENS</h5>
                                <h6 class="no-margin">Receba mensagens exclusivas dos <br>
                                    seus clubes favoritos.</h6>
                            </div>
                            <div class="media-right">
                                <a href="#"><img src="{{ asset('my/images/icon_msg.png') }}" class=""></a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Center Image -->
                <div class="col-sm-3 text-center hidden-xs hidden-sm">
                    <img src="{{asset('/my/images/celular_alpha.png')}}" style="max-height: 500px"
                         class="img-responsive center-block wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                </div>

                <!-- Features Left -->
                <div class="col-sm-4 pl-10 hidden-xs hidden-sm">
                    <ul class="media-list  mt-20">

                        <li class="media">
                            <div class="media-left">
                                <a href="#"><img src="{{ asset('my/images/icon_agenda.png') }}" class=""></a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">AGENDA</h5>
                                <h6 class="no-margin">Acompanhe a agenda semanal de cada<br>
                                    clube e os próximos torneios.</h6>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-left">
                                <a href="#"><img src="{{ asset('my/images/icon_hank.png') }}" class=""></a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">RANKING</h5>
                                <h6 class="no-margin">Veja a classificação por etapa e a <br>
                                    classificação final de cada torneio.</h6>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-left">
                                <a href="#"><img src="{{ asset('my/images/icon_fotos.png') }}" class=""></a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">FOTOS</h5>
                                <h6 class="no-margin">Galerias de fotos de cada <br>
                                    torneio ou evento do Clube.</h6>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-left">
                                <a href="#"><img src="{{ asset('my/images/icon_rota.png') }}" class=""></a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">TRAÇAR ROTA</h5>
                                <h6 class="no-margin">Facilidade para quem não é da cidade,<br>
                                    traçe a rota até o clube.</h6>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-left">
                                <a href="/premium/#cadpremium"><img src="{{ asset('my/images/icon_premium.png') }}" class=""></a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading text-dourado mt-5">EXPERIMENTE</h5>
                                <h6 class="no-margin">Seja Premium grátis por 30 dias. E tenha
                                    <br>um gerenciador completo para seu clube.</h6>
                            </div>
                        </li>

                    </ul>
                </div>
            </div><!-- / .row -->

            <br><br>

        </div><!-- / .container -->
    </div><!-- / .dark-bg -->


    <div id="contato" class="bg-white">
        <div class="container app-welcome">

            <div class="row m-15 p-20">
                <h1 class="text-semibold">Entre em contato conosco</h1>

                <form action="#" class="form-horizontal" name="frm_contact" id="frm_contact" onsubmit="EnviarContato(); return false" method="POST">
                    {{ csrf_field() }}
                    <div class="panel no-border col-md-6">


                        <div class="panel-body">

                            <div class="form-group">
                                <h4 class="control-label">Seu nome:</h4>
                                <input type="text" class="form-control" name="ct_name" id="ct_name" required>
                            </div>

                            <div class="form-group">
                                <h4 class="control-label">Seu E-mail:</h4>
                                <input type="email" class="form-control" name="ct_email" id="ct_email" required>
                            </div>

                            <div class="form-group">
                                <h4 class="control-label">Telefone:</h4>
                                <input type="text" class="form-control" name="ct_fone" id="ct_fone" required>
                            </div>

                            <div class="form-group">
                                <h4 class="control-label">Sua mensagem:</h4>
                                <textarea rows="5" cols="5" class="form-control" name="ct_msg" id="ct_msg" required></textarea>
                            </div>

                            <div class="text-right">
                                <!--button type="submit" class="btn bg-grey-800">Enviar<i class="icon-arrow-right14 position-right"></i></button-->
                                <button type="submit" class="btn bg-dourado btn-lg text-uppercase text-size-small text-semibold">Enviar<i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-md-offset-2">
                        <br><br><img src="{{asset('my/images/contato_whats.png')}}" class="img-responsive">
                        <br><br><img src="{{asset('my/images/contato_local.png')}}" class="img-responsive">
                        <br><br><a href="https://facebook.com/PokerClubsApp" target="_blank">
                            <img src="{{asset('my/images/contato_face.png')}}" class="img-responsive" ></a>
                        <br><br><a href="https://www.instagram.com/pokerclubsapp/" target="_blank">
                            <img src="{{asset('my/images/contato_insta.png')}}" class="img-responsive"></a>

                    </div>
                </form>


            </div><!-- / .row -->

        </div><!-- / .container -->
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
    <script src="{{ asset('my/js/home3.js') }}"></script>
@endsection