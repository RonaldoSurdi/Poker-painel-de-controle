@forelse($lista as $cad)
    <tr id="player{{$cad->id}}">
        <td class="col-sm-7 p-5">
            <div class="media">
                <div class="media-left">
                    {{--<a href="{{ $cad->photo() }}" data-popup="lightbox">--}}
                    <a href="#" onclick="AlterImg({{$cad->id}})" title="Alterar Imagem">
                        <img src="{{ $cad->photo() }}" class="img-circle img-lg img-preview" alt="" id="player{{$cad->id}}_img">
                        <i class="spinner icon-spinner2 loadimg" id="load_img{{$cad->id}}"></i>
                    </a>
                </div>

                <div class="media-body valign-middle">
                    <h6 class="media-heading nameplayer" id="player_name{{$cad->id}}">{{$cad->name}}</h6>
                </div>

                <div class="media-right media-middle">
                    <ul class="icons-list">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-menu7"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#" onclick="PlayerEdit({{$cad->id}})"><i class="icon-pencil pull-right"></i> Alterar o Nome</a></li>
                                <li><a href="#" onclick="AlterImg({{$cad->id}})" ><i class="icon-image2 pull-right"></i> Alterar Foto</a></li>
                                <li><a href="#" onclick="ImagemDel({{ $cad->id }})" ><i class="icon-cancel-square pull-right"></i> Remover Foto</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onclick="PlayerDel({{ $cad->id }})"><i class="icon-trash pull-right"></i> Remover Jogador</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </td>
        <td class="col-sm-3 ">
            <div class="row etapa">
                    <div class="etapa{{$etapa}} col-xs-12 etapa">
                        <input type="number" name="player{{$cad->id}}_step{{$etapa}}" id="player{{$cad->id}}_step{{$etapa}}"
                               class="form-control text-blue-800" placeholder="Etapa {{$etapa}}"
                                min='0' step="1" value="{{ $cad->Point($etapa) ? $cad->Point($etapa) : '' }}" >
                    </div>
            </div>
        </td>
        <td class="col-sm-2 col-xs-1 text-right">
            {{ $cad->total() }}
        </td>
    </tr>
@empty
    <tr id="tr_aviso">
        <td colspan="3">
            <div class="alert bg-info alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span id="alerta_text">Você não possui Jogadores neste Ranking! Clique em "Adicionar Jogador".</span>
            </div>
        </td>
    </tr>
@endforelse
