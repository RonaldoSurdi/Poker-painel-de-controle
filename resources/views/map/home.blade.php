@extends('layouts.front')
@section('css')
    <style>
        .banner{
            position: fixed;
            top: 0;
            left: 0;
            min-height: 100%;
            min-width: 100%;
            overflow: hidden;
            display: inline-block;
            background-position: top left;
            z-index: -1;
        }
        .map-container {
            height: 82vh !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

    </style>
@endsection

@section('script')
    {{--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMXQkmRy0119nG-T7bxe-odgSrm6oES7c">AIzaSyCg7ETqX223hQP_ScD_C1_j9T8sEbJbVnw</script>--}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCu-GdAWOloRPGNQwCIDx4HjRk3MiDrki8"></script>
    <script type="text/javascript" src="{{ asset('my/js/map_lista.js')}}"></script>
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/sliders/ion_rangeslider.min.js')}}"></script>
@endsection


@section('slide')
<div class="container-fluid row" style="padding-top: 25px">

    <div class="col-sm-4 col-md-3">

        <div class="tabbable tab-content-bordered">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="active"><a href="#bordered-tab1" data-toggle="tab" id="tab_gps">Minha localização</a></li>
                <li><a href="#bordered-tab2" data-toggle="tab" id="tab_cidade">Por cidade</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane has-padding active no-margin no-padding p-10" id="bordered-tab1">
                    <div class="row no-margin">
                        {{--<div class="col-sm-8">--}}
                        <h5 class="panel-title">Club's proximos a sua localização</h5>
                        <p class="text-muted mt-5">
                            No mapa serão listados os club's de poker próximos a você,
                            ajuste o raio para ver mais clubes ou use a opção acima "Por cidade".
                        </p>
                        {{--</div>--}}
                        {{--<div class="col-sm-8">--}}
                        <form method="POST" action="{{route('user.raio')}}">
                            {{ csrf_field() }}
                            <div class="panel panel-body border-top-danger p-5 no-margin">
                                <div class="text-center">
                                    <h6 class="no-margin text-semibold">Raio de localização</h6>
                                    <p class="text-muted">Será listado os clubs em um raio de:</p>
                                </div>

                                <input type="text" class="form-control" id="radius" name="radius">
                                <p class="text-center mt-10">
                                    <button class="btn btn-primary"><i class="icon-search4"></i> Localizar</button>
                                </p>
                            </div>
                        </form>
                        {{--</div>--}}
                    </div>
                </div>

                <div class="tab-pane has-padding" id="bordered-tab2">
                    <h5 class="panel-title">Club's por cidade</h5>
                    <p  class="text-muted mt-5">
                        Selecione a cidade que deseja ver os clubs:
                    </p>
                    <div class="form-group">
                        <label>Cidade / UF</label>
                        <select class="select2 cidade" name='city' id='city' onchange="SelectCity()" style="width: 100%">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="panel panel-body bg-success-400 has-bg-image">
            <div class="media no-margin">
                <div class="media-right media-middle">
                    {{--<i class="icon-location4 icon-3x opacity-75"></i>--}}
                    <h1 class="no-margin no-padding" id="map_qtd">*</h1>
                </div>

                <div class="media-body text-right">
                    <span class="text-uppercase text-size-mini" id="map_city">Localizando...</span>
                </div>
            </div>
        </div>

    </div>
    <div class="col-sm-8 col-md-9">
        <!-- Geolocation -->
        <div class="panel panel-flat">
            <div class="panel-body p-5">
                <form id="frm1">
                    {{ csrf_field() }}
                    <input type="hidden" id="uid" name="uid" value="{{\Illuminate\Support\Facades\Session::get('userapp_id')}}">
                </form>
                <div class="alert bg-info alert-styled-left" style="max-width: 500px" id="load_mapa">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <i class="icon-spinner2 spinner"></i> Carregando Mapa
                </div>

                <div class="map-container map-geolocation" id="map"></div>

                <div class="alert alert-warning alert-styled-left" style="display:none; max-width: 500px" id="aviso_mapa">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <spam id="aviso_text">Não foi possivel montar o mapa, tente novamente atualizando a página (F5).</spam>
                </div>
            </div>
        </div>
        <!-- /geolocation -->
    </div>



</div>


@endsection


@section('script_footer')
    <!-- Vertical form modal -->
    <div id="modal_club" class="modal fade" >
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header hidden">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Visualizar Club</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="premium_id">
                        <div class="navbar navbar-inverse bg-primary navbar-component no-margin" style="position: relative; z-index: 27;">
                            <div class="navbar-header"><b>
                                <a class="navbar-brand" href="/club" id="premium_title">

                                </a></b>
                                <ul class="nav navbar-nav pull-right visible-xs-block">
                                    <li><a data-toggle="collapse" data-target="#navbar-navigation"><i class="icon-menu2"></i></a></li>
                                </ul>
                            </div>

                            <div class="navbar-collapse collapse pull-right-md" id="navbar-navigation">
                                <ul class="nav navbar-nav ">
                                    <li><a href="#" onclick="clubPre(1)">O Clube</a></li>
                                    <li><a href="#" onclick="clubPre(2)">Agenda</a></li>
                                    <li><a href="#" onclick="clubPre(3)">Torneios</a></li>
                                    <li><a href="#" onclick="clubPre(4)">Ranking</a></li>
                                    <li><a href="#" onclick="clubPre(5)">Fotos</a></li>
                                    <li><a href="#" onclick="clubPre(6)">Mensagens</a></li>
                                </ul>
                            </div>
                        </div>
                    <div id="club_div"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- /vertical form modal -->
    <script type="text/javascript" src="{{ asset('my/js/map_home.js')}}"></script>
    <script>
        $("#radius").ionRangeSlider({
            min: 1,
            max: 10000,
            from:parseInt('{{ $raio }}'),
            postfix: " Km"
        });


        function clubPre(page){
            $('#club_div').html(page);
        }

    </script>
@endsection
