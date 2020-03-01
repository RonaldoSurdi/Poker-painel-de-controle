@extends('layouts.painelclub')
@php($pag='lic')
@section('content')

    <div class="row">
        <h4>Licença Anual</h4>

        <div class="row">

            <div class="col-sm-4">
                <div class="panel panel-info text-left" style="max-width: 300px">
                    <div class="panel-heading">
                        <h5 class="panel-title">Licença</h5>
                    </div>
                    <div class="panel-body p-5">
                        <h5>
                            <table>
                                <tr>
                                    <td class="p-5">Tipo:</td>
                                    <td class="p-5"><span class="text-primary">{{$cad->_type()}}</span></td>
                                </tr>
                                <tr>
                                    <td class="p-5">Valor:</td>
                                    <td class="p-5"><span class="text-primary">{{'R$ '.number_format($cad->value, 2, ',', '.')}}</span></td>
                                </tr>
                            </table>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 ">



            </div>


        </div>


    </div>

@endsection
