@extends('layouts.front')
@section('css')
    <link href="{{ asset('/my/css/home2.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('slide')
    <!-- ========== Hero Cover ========== -->
    <div class="content bg_home1">
        <div id="home" class="container app-hero" >
            <div class="container">
                <!-- Text -->
                <div class="col-sm-6 hero-text text-center">
                    <img src="{{asset('my/images/logo_center.png')}}" class="img-responsive">

                    <p>
                        Localize agora clubes de poker próximos a você.
                        <br>O PokerClubs é um aplicativo moderno feito para facilitar a vida dos amantes do  poker.
                    </p>
                    <br><br>
                    <a href="#" class="btn btn-flat btn-down text-uppercase" onclick="DownSys()"><i class="icon-download position-left"></i> Baixe grátis</a>
                    {{--<a href="#sobre" class="btn bg-teal-800 btn-labeled btn-rounded m-5"><b><i class="icon-reading"></i></b> Saiba mais...</a>--}}
                    <br><br>
                </div><!-- / .col-lg-6 -->

                <!-- Image -->
                <div class="col-sm-6">
                    <br><br>
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
                                        <div class="form-group has-feedback has-feedback-left {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Seu e-mail de acesso do app" value="{{ old('email') }}">
                                            <div class="form-control-feedback">
                                                <i id="ico_input" class="icon-user text-muted"></i>
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

@endsection


@section('script_footer')
    <script src="{{ asset('my/js/home2.js') }}"></script>
@endsection