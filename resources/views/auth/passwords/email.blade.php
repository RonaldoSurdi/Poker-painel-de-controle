@extends('layouts.front')

@section('content')
    <!-- Content area -->
    <div class="content  col-sm-4 col-md-3 col-md-offset-4 col-sm-offset-3">

        <!-- Tabbed form -->
        <div class="panel login-form width-400">
            <div class="panel-body">

                <!-- Password recovery -->
                <form method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <div class="text-center">
                        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                        <h5 class="content-group">Redefinir Senha <small class="display-block">Nós lhe enviaremos instruções em e-mail</small></h5>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe seu e-mail" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-blue btn-block">Enviar link de redefinição de senha <i class="icon-arrow-right14 position-right"></i></button>
                </form>
                <!-- /password recovery -->

            </div>
        </div>
    </div>
@endsection
