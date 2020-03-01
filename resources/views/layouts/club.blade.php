@extends('layouts.front')
@section('slide')
    <div class="container">


        <div class="navbar navbar-inverse bg-grey navbar-component no-margin" style="position: relative; z-index: 27;">
            <div class="navbar-header">
                <a class="navbar-brand" href="/club">
                    <b>{{ \Illuminate\Support\Facades\Auth::user()->club->name}}</b>
                </a>
                <ul class="nav navbar-nav pull-right visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-navigation"><i class="icon-menu2"></i></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse pull-right-md" id="navbar-navigation">
                <ul class="nav navbar-nav ">
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

    </div>
@endsection

