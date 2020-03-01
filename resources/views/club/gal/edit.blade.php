@extends('layouts.painelclub')
@php($pag='fotos')
@section('script')
    <script type="text/javascript" src="{{ asset('front/assets/js/plugins/media/fancybox.min.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        @if ($cad->id>0)
            <h3>Alterar Galeria</h3>
        @else
            <h3>Nova Galeria</h3>
        @endif
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title">
                            Dados da galeria
                        </h6>
                    </div>

                    <form class="panel-body" id="frm_cad" name="frm_cad">
                        {{ csrf_field() }}
                        <input type="hidden" id="idd" name="idd" value="{{$cad->id}}">
                        <div class="row">
                            <div class="form-group col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"> Titulo</span>
                                    <input type="text" id="title" name="title" value="{{ $cad->title }}"
                                           class="form-control text-blue-800" placeholder="titulo da galeria">
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"> Data</span>
                                    <input type="date" id="date_event" name="date_event" value="{{ $cad->date_event }}"
                                           class="form-control text-blue-800" placeholder="data da galeria">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><b>Descrição:</b></label>
                            <textarea rows="4" cols="5" id="desc" name="desc"  class="form-control text-blue-800"
                                      placeholder="Escreve aqui informações sobre esta galeria">{{ $cad->desc }}</textarea>
                        </div>

                        <a href="{{route('club.gal')}}" class="btn btn-sm btn-info heading-btn pull-left">Voltar para lista</a>

                        <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                                onclick="SalvarDados()" id="btn_save"><i class="icon-floppy-disk"></i> Salvar</button>
                    </form>
                </div>
            </div>


            <div class="col-xs-12 col-md-8 col-md-offset-2" id="div_fotos">
                <form id="frm_foto" name="frm_foto" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" id="gal_id" name="gal_id" value="{{$cad->id}}">
                    <input id="foto1" name="foto1" type="file" style="display: none"  accept="image/*" onchange="upload_img('foto1')" >
                </form>

                <div class="panel panel-flat border-top-lg border-top-warning">
                    <div class="panel-heading p-10">
                        <h6 class="panel-title">
                            <i class="icon-image2"></i> Fotos da galeria
                        </h6>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-primary heading-btn" id="btn_foto">
                                <i class="icon-image2"></i> Adicionar Foto</button>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="alert bg-info alert-styled-left" id="alerta_foto">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span id="alerta_foto_text">Você não possui fotos nesta galeria! Clique em "Adicionar Foto".</span>
                        </div>

                        <ul class="row"  id="galeria_fotos">

                        </ul>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <script type="text/javascript" src="{{ asset('my/js/gal_edit.js')}}"></script>
    <script type="text/javascript" src="{{ asset('my/js/gal_photos.js')}}"></script>
    <script>
        @if ($cad->id==0)
            $('#div_fotos').hide();
        @else
            CarregarFotos(0);
        @endif
    </script>
@endsection