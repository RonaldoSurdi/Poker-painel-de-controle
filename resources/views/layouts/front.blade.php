@extends('layouts.blank')

@section('navbar')
    <!-- Main navbar -->
    <div class="navbar navbar-poker navbar-fixed-top ">
        <div class="navbar-header">
            <a class="navbar-brand p-10" href="/">
                {{--{{ config('app.name', 'Laravel') }}--}}
                <img class="navbar-logo-dark" src="{{ asset('/my/images/logo_topo.png')}}" style="height: 23px;">
            </a>
            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu2"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav navbar-right">
                {{--<li><a href="/#home">Home</a></li>--}}
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_down" class="btn btn-flat btn-down text-uppercase">Baixe grátis</a>
                </li>
                <li><a href="/#sobre">Sobre</a></li>
                <li><a href="/#funcoes">Funções</a></li>
                <li><a href="/map">Mapa</a></li>
                <li><a href="#" onclick="Indique()">Indique um Clube</a></li>

                @if (!Auth::check())
                    <li><a href="/premium">SOU CLUBE</a></li>
                @else
                    <li><a href="/club">SOU CLUBE</a></li>
                @endif


                <li><a href="/#contato">Contato</a></li>

            </ul>
        </div>
    </div>
    <!-- /main navbar -->
@endsection

@section('modal')


<!-- Vertical form modal -->
<div id="modal_indique" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content homelogin bg-grey-800">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Indique um Clube</h5>
            </div>

            <form id="formInd">
                <input type="hidden" id="ind_user_id" value="">
                <p class="m-10">Informe os dados abaixo e nossa equipe entrará em contato com o clube:</p>

                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label>Nome do Clube</label>
                            <input type="text" id='ind_name' class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label>Proprietário</label>
                            <input type="text" id='ind_resp' class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label>Telefone</label>
                            <input type="text" id='ind_phone' class="form-control" placeholder="(xx) xxxxxxxxx " required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-grey-300" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bg-dourado">Indicar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /vertical form modal -->



<!-- Vertical form modal -->
<div id="modal_cadastro" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Cadastre-se</h5>
            </div>

            <div class="modal-body">
                <h4>
                    Para utilizar o PokerClubs você deve efetuar o cadastro no aplicativo,
                    faça o download em seu celular ou Tablet, cadastre-se usando seu e-mail ou facebook, é um cadastro rápido e simples.
                    <br>Após você estar cadastrado poderá localizar os clubes e ter acesso aos seus beneficios.
                </h4>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Vertical form modal -->
<div id="modal_down" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content  bg-grey-800">
            <div class="modal-body text-center">
                <h4>Baixe o aplicativo nas Store</h4>
                <div class="col-sm-12 text-center">
                    <a href="https://itunes.apple.com/br/app/pokerclubs/id1373727131?mt=8"
                       target="_blank"><img class="m-5" src="{{ asset('/my/images/app-store.png')}}" alt="App Store"></a>

                    <a href="https://play.google.com/store/apps/details?id=com.pokerclubs.sisdex"
                       target="_blank"><img class="m-5" src="{{ asset('/my/images/google-play.png')}}" alt="Google Play"></a>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-flat border-white" data-dismiss="modal">OK</button>
            </div>

        </div>
    </div>
</div>

@endsection


@section('footer')
    <!-- Footer -->
    <div class="navbar navbar-poker navbar-fixed-bottom footer">
        <ul class="nav navbar-nav visible-xs-block">
            <li><a class="text-center collapsed" data-toggle="collapse" data-target="#footer"><i class="icon-circle-up2"></i></a></li>
        </ul>

        <div class="navbar-collapse collapse" id="footer">
            <div class="navbar-text">
                &copy; 2019. <a href="#" class="navbar-link">PokerClubs</a> by <a href="http://saintec.com.br" class="navbar-link" target="_blank">SAINTEC Sistemas</a>
            </div>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="/#home">Ir ao Topo</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /footer -->
@endsection