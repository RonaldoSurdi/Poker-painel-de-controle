<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('/my/images/app-icon.png')}}">
    <meta name="theme-color" content="#000">
    <meta name="google-site-verification" content="3pRxPGi6P0hJ9j1PMBCEHRSwpYnFWh997_zoerr8YAo" />
    <link rel="manifest" href="/manifest.json">
    <!-- Global stylesheets -->
    {{--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">--}}
    <link href="https://fonts.googleapis.com/css?family=Lato:400,300,100,500,700,900" rel="stylesheet">
    <link href="{{ asset('front/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/assets/css/extras/animate.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('my/css/fonts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('my/css/my5.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.5/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/jquery.modal/css/jquery.modal.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('front/jquery.modal/css/jquery.modal.theme-xenon.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('front/jquery.modal/css/jquery.modal.theme-atlant.css') }}" type="text/css" rel="stylesheet" />
    @yield('css')
<!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('front/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/core/libraries/jquery_ui/full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/ui/nicescroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/ui/drilldown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/jquery.modal/js/jquery.modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.5/sweetalert2.min.js"></script>
    <script type="text/javascript" src="{{ asset('front/jquery.mask/jquery.mask.min.js') }}"></script>
    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('front/assets/js/core/app.js') }}"></script>
    <!-- /theme JS files -->
    @yield('script')

    <script type="text/javascript" src="{{asset('front/assets/js/plugins/forms/inputs/formatter.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/assets/js/plugins/forms/selects/select2.min.js')}}"></script>
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>--}}

<!--<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>-->
    <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-116465762-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-116465762-2');
    </script>


</head>

<body class="navbar-bottom navbar-top" data-spy="scroll">


@yield('navbar')
<!-- Main navbar -->
<div class="navbar navbar-poker navbar-fixed-top ">
    <div class="navbar-header">
        <a class="navbar-brand p-10" href="/club">
            {{--{{ config('app.name', 'Laravel') }}--}}
            <img class="navbar-logo-dark" src="{{ asset('/my/images/logo_painel.png')}}" style="height: 23px;">
        </a>
        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu2"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav navbar-right">
            {{--<li><a href="/#home">Home</a></li>--}}
            @if (!\Illuminate\Support\Facades\Auth::user()->club->premium())
                <li @if($pag=='lic')  class="active" @endif><a href="{{route('club.lic')}}">Minha Licença</a></li>
            @else
                {{--<li @if($pag=='home')   class="active" @endif><a href="{{route('club.home')}}">Inicio</a></li>--}}
                <li @if($pag=='dados')  class="active" @endif><a href="{{route('club.dados')}}" class="btn">O Clube</a></li>
                <li @if($pag=='agenda') class="active" @endif><a href="{{route('club.schedule')}}" class="btn">Agenda</a></li>
                <li @if($pag=='torn')   class="active" @endif><a href="{{route('club.torn')}}" class="btn">Torneios</a></li>
                <li @if($pag=='rank')   class="active" @endif><a href="{{route('club.blind')}}" class="btn">Blind</a></li>
                <li @if($pag=='rank')   class="active" @endif><a href="{{route('club.rank')}}" class="btn">Ranking</a></li>
                <li @if($pag=='fotos')  class="active" @endif><a href="{{route('club.gal')}}" class="btn">Fotos</a></li>
                <li @if($pag=='msgs')  class="active" @endif><a href="{{route('club.msg')}}" class="btn">Mensagens</a></li>
                <li @if($pag=='lic')  class="active" @endif>
                    <a href="{{route('club.lic')}}" class="btn">
                        Minha Licença
                    </a>
                </li>
            @endif
            <li><a href="{{ route('logout') }}" class="btn text-warning-300"><i class="icon-switch2"></i> Sair</a></li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


@yield('slide')
<!-- Page container -->
<div class="page-container">


    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <Center>
                @if (Session::has('ok'))
                    <div class="alert bg-success alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        {!!  Session::pull('ok') !!}
                    </div>
                @endif
                @if (Session::has('info'))
                    <div class="alert bg-info alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        {!!  Session::pull('info') !!}
                    </div>
                @endif
                @if (Session::has('aviso'))
                    <div class="alert alert-warning alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        {!!  Session::pull('aviso') !!}
                    </div>
                @endif
                @if (Session::has('erro'))
                    <div class="alert bg-danger-400 alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        {!!  Session::pull('erro') !!}
                    </div>
                @endif
            </Center>



            @yield('content')

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->


<!-- Footer -->
<div class="navbar navbar-poker navbar-fixed-bottom footer">
    <ul class="nav navbar-nav visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#footer"><i class="icon-circle-up2"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="footer">
        <div class="navbar-text text-center">
            &copy; 2019. <a href="#" class="navbar-link">PokerClubs</a> by <a href="http://saintec.com.br" class="navbar-link" target="_blank">SAINTEC Sistemas</a>
        </div>
    </div>
</div>
<!-- /footer -->

@yield('footer')
@yield('modal')



<script src="{{ asset('my/js/my.js') }}"></script>
<script src="{{ asset('my/js/avisos.js') }}"></script>
<script src="{{ asset('my/js/indique.js') }}"></script>
@yield('script_footer')

<script>
    setTimeout(function(){
        @if (Session::has('Sok'))
        aviso('success','{!!  Session::pull('Sok') !!}','Sucesso!');
        @endif
        @if (Session::has('Sinfo'))
        aviso('info','{!!  Session::pull('Sinfo') !!}','Informação!');
        @endif
        @if (Session::has('Saviso'))
        aviso('warning','{!!  Session::pull('Saviso') !!}','Alerta!');
        @endif
        @if (Session::has('Serro'))
        aviso('error','{!!  Session::pull('Serro') !!}','Erro!');
        @endif
    }, 100);
</script>

</body>
</html>