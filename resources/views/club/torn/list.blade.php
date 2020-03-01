@extends('layouts.painelclub')
@php($pag='torn')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/listies.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Torneios</h3>

        <div class="row">

            <!-- Media library -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title text-semibold">Lista de torneios do seu clube
                    @if (isset($busca)) <span class="text-blue-800"> - pesquisando por {{$busca}}</span> @endif
                    </h6>
                    <div class="heading-elements">

                        <form class="heading-form mr-5" role="form" action="{{route('club.torn.search')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>
                                </div>
                            </div>
                        </form>

                        <a href="{{route('club.torn.new')}}" type="button" class="btn btn-primary heading-btn" id="btn_cad">
                            <i class="icon-plus-circle2"></i> Adicionar Torneio</a>
                    </div>
                </div>

                <table class="table table-hover datatable-responsive">
                    <thead>
                    <tr>
                        <th class="col-sm-1 hidden-xs" style="width: 120px">Preview</th>
                        <th class="col-sm-6">Titulo</th>
                        <th class="col-sm-2">Data</th>
                        <th class="col-sm-2">Informações</th>
                        <th class="col-sm-1 text-center hidden-xs" style="width: 120px">Ações</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($lista as $cad)
                        <tr>
                            <td class="hidden-xs" style="width: 120px">
                                <a href="{{ $cad->img() }}" data-popup="lightbox">
                                    <img src="{{ $cad->img() }}" alt="" class="img-rounded img-preview">
                                </a>
                            </td>
                            <td>
                                <a href="{{route('club.torn.show',['id'=>$cad->id])}}">{{$cad->name}}</a>
                            </td>
                            <td>
                                {{--{{ $cad->data()}}--}}
                                @if ($cad->type==1)
                                    {!! $cad->week() !!}
                                @else
                                    @foreach($cad->dates as $day)
                                        {{ date('d/m/Y H:i', strtotime($day->data) ) }}<BR>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <ul class="list-condensed list-unstyled no-margin">
                                    <li><span class="text-semibold">Inscrição pelo APP:</span> {{$cad->inscricao()}}</li>
                                    @if ($cad->insc_app==1)
                                        <li>
                                            <a href="{{route('club.torn.insc',['id'=>$cad->id])}}" target="_blank" title="Ver lista de inscritos">
                                                <span class="text-semibold">Inscritos:</span> {{$cad->subscription->count()}}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </td>
                            <td class="text-center hidden-xs">
                                <ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{route('club.torn.edit',['id'=>$cad->id])}}"><i class="icon-pencil7"></i> Alterar</a></li>
                                            <li><a href="{{route('club.torn.show',['id'=>$cad->id])}}"><i class="icon-eye"></i> Visualizar</a></li>
                                            @if ($cad->insc_app==1)
                                            <li><a href="{{route('club.torn.insc',['id'=>$cad->id])}}"  target="_blank"><i class="icon-printer"></i> Lista de Inscritos</a></li>
                                            @endif
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="ask_url('Deseja excluir este torneio?','{{ route('club.torn.del',['id'=>$cad->id]) }}')">
                                                    <i class="icon-bin"></i> Excluir</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Não foi encontado torneio cadastrado.
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
