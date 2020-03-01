@extends('layouts.painelclub')
@php($pag='agenda')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/listies.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Agenda Semanal - Próximos 7 dias</h3>

        <div class="row">
            @php($semana = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'])
            @php($i=0)
            @foreach($lista as $item)
                @php($i++)

                <div class="col-sm-4">
                    <div class="panel panel-default border-grey">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                                {{$item['week']}} - {{$item['date']}}
                                @if ($i==1) <label class="label label-success">HOJE</label> @endif
                            </h6>
                            <div class="heading-elements">
                                <a href="{{route('club.torn.newW',['day'=>$i])}}" type="button" class="btn btn-primary heading-btn" id="btn_cad"><i class="icon-plus-circle2"></i> Adicionar</a>
                            </div>
                        </div>
                        <table class="table table-responsive table-hover">
                            @forelse($item['items'] as  $torn)
                                <tr>
                                    <td class="col-sm-2 text-blue-800">
                                        <a href="{{route('club.torn.show',['id'=>$torn['id']])}}" target="_blank" title="Ver dados do evento">
                                        {{ $torn['hour'] }}
                                        </a>
                                    </td>
                                    <td class="col-sm-10 text-blue-800">
                                        <a href="{{route('club.torn.show',['id'=>$torn['id']])}}" target="_blank" title="Ver dados do evento">
                                            {{$torn['title']}} </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">
                                        <span class="text-muted">Não possui torneios</span>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

                @if ( ($i==3) or ($i==6) )
                    <div class="col-sm-12">
                    </div>
                @endif


            @endforeach

        </div>
    </div>

@endsection
