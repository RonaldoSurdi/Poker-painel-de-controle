@extends('layouts.painelclub')
@php($pag='msgs')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/listies.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Mensagens</h3>

        <div class="row">

            <!-- Media library -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title text-semibold">Lista de Mensagens enviadas pelo seu clube
                    @if (isset($busca)) <span class="text-blue-800"> - pesquisando por {{$busca}}</span> @endif
                    </h6>
                    <div class="heading-elements">

                        <form class="heading-form mr-5" role="form" action="{{route('club.msg.search')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>
                                </div>
                            </div>
                        </form>

                        <a href="{{route('club.msg.new')}}" type="button" class="btn btn-primary heading-btn" id="btn_cad">
                            <i class="fa fa-envelope"></i> Nova Mensagem</a>
                    </div>
                </div>

                <table class="table table-hover datatable-responsive">
                    <thead>
                    <tr>
                        <th class="col-sm-1 hidden-xs" style="width: 120px">Preview</th>
                        <th class="col-sm-4">Titulo</th>
                        <th class="col-sm-2">Data</th>
                        <th class="col-sm-2">Destino</th>
                        <th class="col-sm-1">Status</th>
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
                                <a href="{{route('club.msg.show',['id'=>$cad->id])}}">{{$cad->title}}</a>
                            </td>
                            <td>
                                {{ date('d/m/Y H:i', strtotime( $cad->date_send)  ) }}
                            </td>
                            <td>
                                {{ $cad->UserType() }}
                            </td>
                            <td>
                                {{ $cad->_status() }}
                            </td>
                            <td class="text-center hidden-xs">
                                <ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @if($cad->status<>2)
                                                <li><a href="{{route('club.msg.edit',['id'=>$cad->id])}}"><i class="icon-pencil7"></i> Alterar</a></li>
                                            @endif
                                            <li><a href="{{route('club.msg.show',['id'=>$cad->id])}}"><i class="icon-eye"></i> Visualizar</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="ask_url('Deseja excluir esta Mensagem?','{{ route('club.msg.del',['id'=>$cad->id]) }}')">
                                                    <i class="icon-bin"></i> Excluir</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Não foi encontada mensagem cadastrado.
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
