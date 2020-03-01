@extends('layouts.painelclub')
@php($pag='msgs')

@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>Visualizar Mensagem</h3>

        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title"> <i class="icon-profile"></i> Dados de Cadastro</h6>
                        <div class="heading-elements">
                            <a href="{{route('club.msg')}}" class="btn btn-sm btn-info heading-btn">Voltar para lista</a>
                            @if ( ($cad->status<>2) )
                                <a href="{{route('club.msg.edit',['id'=>$cad->id])}}" class="btn btn-primary heading-btn" id="btn_cad">Alterar Cadastro</a>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body">
                        <h3><b>{{$cad->title}}</b></h3>
                        <hr>
                        <h5><b>Data de envio:</b> {{ date('d/m/Y H:i', strtotime($cad->date_send) )  }}</h5>
                        <hr>
                        <h5><b>Enviar para:</b> {{$cad->UserType()}}</h5>
                        <h5><b>Valor:</b> {{$cad->Valor()}}</h5>

                        <hr>
                            <h5><b>Mensagem:</b></h5>
                            <br>
                            @if ($cad->msg_type==2)
                                <img src="{{$cad->img()}}" class="img-responsive">
                            @else
                                {!! nl2br($cad->text) !!}
                            @endif
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                @php(
                    $btnSend = '<a href="'.route('club.msg.enviar',['id'=>$cad->id]).'" class="btn btn-lg btn-success heading-btn" id="btn_cad">Enviar esta mensagem</a>'
                )
                @if($cad->status==1)
                    <div class="alert bg-info alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        Esta mensagem esta agendada para {{ date('d/m/Y H:i', strtotime($cad->date_send) )  }}!
                    </div>
                @elseif($cad->status==2)
                    <div class="alert bg-success alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        Mensagem enviada para {{$cad->OneQtd()}} usuários!
                    </div>
                @elseif($cad->status==3)
                    <div class="alert bg-danger-400 alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        Ocorreu um erro ao enviar:
                        <br>
                        {!! $cad->OneErro() !!}
                    </div>
                    <br>
                    {!! $btnSend !!}
                @elseif($cad->status==9)
                    <div class="alert alert-warning alert-styled-left" style="max-width: 500px">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        O envio desta mensagem foi cancelado!
                    </div>
                @else
                    @if ($cad->approved==0)
                        <div class="alert alert-warning alert-styled-left" style="max-width: 500px">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            Aguardando ser aprovada pelo PokerClubs
                        </div>
                    @else
                        {!! $btnSend !!}
                    @endif
                @endif
            </div>
        </div>

    </div>

@endsection

@section('script_footer')
    <script type="text/javascript" src="{{ asset('my/js/torn_img.js')}}"></script>

@endsection