@extends('layouts.painelclub')
@php($pag='blind')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/listies.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Blind</h3>

        <div class="row">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <!-- Media library -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title text-semibold">Lista de Blind do seu clube
                        @if (isset($busca)) <span class="text-blue-800"> - pesquisando por {{$busca}}</span> @endif
                    </h6>
                    <div class="heading-elements">

                        <form class="heading-form mr-5" role="form" action="{{route('club.blind.search')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>
                                </div>
                            </div>
                        </form>

                        <a href="{{route('club.blind.new')}}" type="button" class="btn btn-primary heading-btn" id="btn_cad"><i class="icon-plus-circle2"></i> Adicionar</a>
                    </div>
                </div>

                <table class="table table-hover datatable-responsive">
                    <thead>
                    <tr>
                        {{--<th class="hidden-xs" style="width: 120px">Preview</th>--}}
                        <th class="col-sm-1" >Etapa</th>
                        <th class="col-sm-4" >Titulo</th>
                        <th class="col-sm-4" >Torneio</th>
                        <th class="col-sm-2" >Informações</th>
                        <th class="col-sm-2">Criar Etapa</th>
                        <th class="col-sm-2">Clonar Modelo</th>
                        <th class="col-sm-1">Ações</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($lista as $cad)
                        <tr>
                            {{--<td class="hidden-xs" style="width: 120px">--}}
                                {{--<a href="{{ $cad->img() }}" data-popup="lightbox">--}}
                                    {{--<img src="{{ $cad->img() }}" alt="" class="img-rounded img-preview">--}}
                                {{--</a>--}}
                            {{--</td>--}}
                            <td>
                                {!! $cad->steps !!}
                            </td>
                            <td>
                                <a href="{{route('club.blind.edit',['id'=>$cad->id])}}">{{$cad->title}}</a>
                            </td>
                            <td>
                                {!! $cad->torneio() !!}
                            </td>
                            <td>
                                <ul class="list-condensed list-unstyled no-margin">
                                    <li><span class="text-semibold">Jogadores:</span> {{$cad->players->count()}}</li>
                                    <li><span class="text-semibold">Etapas:</span> {{$cad->steps }}</li>
                                </ul>
                            </td>
                            <td>
                                <a href="#" onclick="cloneblind('{{ $cad->id }}','0')" type="button" class="btn btn-primary heading-btn" id="btn_newslep"><i class="icon-database-export"></i> Criar Etapa</a>
                            </td>
                            <td>
                                <a href="#" onclick="cloneblind('{{ $cad->id }}','1')" type="button" class="btn btn-primary heading-btn" id="btn_clonemodel"><i class="icon-database-export"></i> Clonar Modelo</a>
                            </td>
                            <td>
                                <a href="{{route('club.blind.edit',['id'=>$cad->id])}}"><i class="icon-pencil7"></i></a> <a href="#" onclick="ask_url('Deseja excluir este blind?','{{ route('club.blind.del',['id'=>$cad->id]) }}')"><i class="icon-bin"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Não foi encontado evento cadastrado.
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

@section('script_footer')
    <script>
        function cloneblind(id,clone) {
            console.log(id);
            console.log($('[name="_token"]').val());
            $.ajax({
                url: '/club/blind/newsleep',
                type: 'post',
                dataType: 'json',
                data: {
                    _token : $('[name="_token"]').val(),
                    id : id,
                    clone: clone,
                },
                success: function(data) {
                    if (data.result=='N'){
                        aviso('warning',data.message);
                        return false;
                    }
                    //blindStarted = true;
                    aviso('success',data.message);
                    window.location.href = "https://www.pokerclubsapp.com.br/club/blind/edit/"+data.id;
                }
                ,error: function(XMLHttpRequest, textStatus, errorThrown){
                    /**** deu erro na requisição web *****/
                    /*listrounds = [];*/
                }
            });
        }
    </script>
@endsection