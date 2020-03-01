@extends('layouts.painelclub')
@php($pag='fotos')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/listies.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Galerias de Fotos</h3>

        <div class="row">

            <!-- Media library -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title text-semibold">Galerias de fotos do seu clube
                        @if (isset($busca)) <span class="text-blue-800"> - pesquisando por {{$busca}}</span> @endif
                    </h6>
                    <div class="heading-elements">

                        <form class="heading-form mr-5" role="form" action="{{route('club.gal.search')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>
                                </div>
                            </div>
                        </form>

                        <a href="{{route('club.gal.new')}}" type="button" class="btn btn-primary heading-btn" id="btn_cad"><i class="icon-plus-circle2"></i> Adicionar</a>
                    </div>
                </div>

                <table class="table table-hover datatable-responsive">
                    <thead>
                    <tr>
                        <th class="hidden-xs" style="width: 120px">Preview</th>
                        <th>Titulo</th>
                        <th style="width: 120px">Data</th>
                        <th style="width: 150px">Informações</th>
                        <th class="text-center hidden-xs" style="width: 120px">Ações</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($lista as $cad)
                        <tr>
                            <td class="hidden-xs" style="width: 120px">
                                <a href="{{ $cad->foto() }}" data-popup="lightbox">
                                    <img src="{{ $cad->foto() }}" alt="" class="img-rounded img-preview">
                                </a>
                            </td>
                            <td>
                                <a href="{{route('club.gal.edit',['id'=>$cad->id])}}">{{$cad->title}}</a>
                            </td>
                            <td>
                                {{ date('d/m/Y',strtotime( $cad->created_at) ) }}
                            </td>
                            <td>
                                <ul class="list-condensed list-unstyled no-margin">
                                    <li><span class="text-semibold">Qtd Fotos:</span> {{ $cad->qtd_photos() }}</li>
                                    {{--<li><span class="text-semibold">Visualizações:</span> 0 </li>--}}
                                </ul>
                            </td>
                            <td class="text-center hidden-xs">
                                <ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{route('club.gal.edit',['id'=>$cad->id])}}"><i class="icon-pencil7"></i> Alterar</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="ask_url('Deseja excluir esta galeria?','{{ route('club.gal.del',['id'=>$cad->id]) }}')">
                                                    <i class="icon-bin"></i> Excluir</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Não foi encontada galeria cadastrada.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
            <!-- /media library -->


        </div>
    </div>

@endsection
