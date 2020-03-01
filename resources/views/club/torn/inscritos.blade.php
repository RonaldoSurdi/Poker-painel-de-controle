@extends('layouts.blank')

@section('content')
    <div class="row">

        <!-- Media library -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title text-semibold">Lista de inscritos no torneio: <b>{{$cad->name}}</b>
                </h6>
                <div class="heading-elements hidden-print">

                    <a href="#" type="button" class="btn btn-primary heading-btn" id="btn_cad" onclick="window.print()">
                        <i class="icon-printer"></i> Imprimir</a>
                </div>
            </div>

            <table class="table table-hover datatable-responsive">
                <thead>
                <tr>
                    <th class="col-sm-7">Nome do Jogador</th>
                    <th class="col-sm-1">Carta</th>
                    <th class="col-sm-4">Prêmio</th>
                </tr>
                </thead>
                <tbody>

                @forelse($lista as $item)
                    <tr>
                        <td>
                            {{$item->name}}
                        </td>
                        <td>
                            @php
                                if ($item->card==1) echo 'Dez';
                                elseif ($item->card==2) echo 'Valete';
                                elseif ($item->card==3) echo 'Dama';
                                elseif ($item->card==4) echo 'Rei';
                                elseif ($item->card==5) echo 'Ás';
                                elseif ($item->card==6) echo 'Curinga';
                                else echo '--';
                            @endphp
                        </td>
                        <td>
                            {{ $item->premium}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            Não foi encontado inscritos neste torneio.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
        <!-- /media library -->


    </div>
@endsection