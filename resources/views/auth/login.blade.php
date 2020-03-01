{{--@extends('layouts.front')--}}
@include('premium')
@section('content1')
    <!-- Content area -->
    <div class="col-md-5">
        <h2>Licença {{ config('app.name', 'Laravel') }} Premium</h2>
        <h5 class="text-light">
            Adquira sua licença anual e tenha mais vantagens para seu clube.
            <br>Veja as vantagens de ser {{ config('app.name', 'Laravel') }} Premium:
            <br>
            <br>
            <b>Licenças Anual</b>
            <ul>
                <li>Informações completa clube</li>
                <li>Coloque sua logomarca</li>
                <li>Cadastro da agenda da semana</li>
                <li>Fotos do Clube</li>
                <li>Ranking dos jogadores</li>
                <li>Cadastro de torneios</li>
                <li>Envio de notificações para os usuários</li>
            </ul>
            <br>
            <b>Licenças Gratuita</b>
            <ul>
                <li>Apenas o nome do seu clube será apresentado nas buscas</li>
            </ul>
        </h5>
    </div>
    <div class="col-md-4 col-md-offset-2">

        <!-- Tabbed form -->
        <div class="panel login-form width-400">
            <div class="panel-body">
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="text-center">
                        <div class="icon-object border-slate-300 text-slate-300"><i class="icon-user-lock"></i></div>
                        <h5 class="content-group">Faça login na sua conta<small class="display-block">Suas credenciais</small></h5>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" placeholder="Seu e-mail de acesso" value="{{ old('email') }}" required autofocus>
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Sua senha" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group login-options">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="styled" checked="checked">
                                    Lembra-me
                                </label>
                            </div>

                            <div class="col-sm-6 text-right">
                                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn bg-blue btn-block">Entrar <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>

                {{--<div class="content-divider text-muted form-group"><span>ou faça login com</span></div>--}}
                {{--<ul class="list-inline form-group list-inline-condensed text-center">--}}
                    {{--<li><a href="#" class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded"><i class="icon-facebook"></i></a></li>--}}
                    {{--<li><a href="#" class="btn border-pink-300 text-pink-300 btn-flat btn-icon btn-rounded"><i class="icon-dribbble3"></i></a></li>--}}
                    {{--<li><a href="#" class="btn border-slate-600 text-slate-600 btn-flat btn-icon btn-rounded"><i class="icon-github"></i></a></li>--}}
                    {{--<li><a href="#" class="btn border-info text-info btn-flat btn-icon btn-rounded"><i class="icon-twitter"></i></a></li>--}}
                {{--</ul>--}}

                {{--<span class="help-block text-center no-margin">Ao continuar, você está confirmando que você leu nossos <a href="#">Termos e Condições e Política de Cookies</a></span>--}}
            </div>
        </div>
        <!-- /tabbed form -->

    </div>
    <!-- /content area -->

@endsection

