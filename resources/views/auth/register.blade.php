@extends('layouts.front')

@section('content')
    <!-- Content area -->
    <div class="content  col-sm-4 col-md-3 col-md-offset-4 col-sm-offset-3">

        <!-- Tabbed form -->
        <div class="panel login-form width-400">
            <div class="panel-body">
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="text-center">
                        <div class="icon-object border-success text-success"><i class="icon-plus3"></i></div>
                        <h5 class="content-group">Criar uma nova conta <small class="display-block">Preencha todos os campos</small></h5>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Seu Nome" required autofocus>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-user-check text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Seu e-mail" required>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-mention text-muted"></i>
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

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password_confirmation" placeholder="Confirme sua senha" required>
                        <div class="form-control-feedback">
                            <i class="icon-user-lock text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-indigo-400 btn-block">Cadastrar <i class="icon-circle-right2 position-right"></i></button>
                </form>
            </div>
        </div>
        <!-- /tabbed form -->

    </div>
    <!-- /content area -->
@endsection
