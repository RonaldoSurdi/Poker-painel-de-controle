@extends('layouts.front')

@section('content')
    <!-- Content area -->
    <div class="content  col-sm-4 col-md-3 col-md-offset-4 col-sm-offset-3">

        <!-- Tabbed form -->
        <div class="panel login-form width-400">
            <div class="panel-body">

                <!-- Password recovery -->
                <form method="POST" action="{{ route('password.request') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="text-center">
                        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                        <h5 class="content-group">Redefinir Senha <small class="display-block">Informe sua nova senha</small></h5>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Informe seu e-mail" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Crie uma senha" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-user-lock text-muted"></i>
                        </div>
                    </div>


                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme sua senha" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-user-lock text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-blue btn-block">Salvar<i class="icon-arrow-right14 position-right"></i></button>
                </form>
                <!-- /password recovery -->

            </div>
        </div>
    </div>

@endsection
