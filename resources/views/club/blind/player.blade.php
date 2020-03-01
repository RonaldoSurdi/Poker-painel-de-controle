@forelse($lista as $cad)
    <tr id="player{{$cad->id}}" class="{{ $cad->saiu ? 'tr_saiu' : '' }}">
        <td class="col-sm-3 p-5{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <div class="media">
                <h6 class="blindnameplayer" id="player_name{{$cad->id}}">{{$cad->name}}</h6>
                <div class="media-left">
                    {{--<a href="{{ $cad->photo() }}" data-popup="lightbox">--}}
                    <a href="#" onclick="AlterImg({{$cad->id}})" title="Alterar Imagem" style="pointer-events:none">
                        <img src="{{ $cad->photo() }}" class="img-circle img-lg img-preview{{ $cad->saiu ? ' tr_desabilitado' : '' }}" alt="" id="player{{$cad->id}}_img">
                        <i class="spinner icon-spinner2 loadimg" id="load_img{{$cad->id}}"></i>
                    </a>
                </div>
                <div class="media-right media-middle">
                    <ul class="icons-list">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-menu7"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right" style="{{ (($cad->saiu)||($cad->ranking==1)) ? 'display:none' : '' }}">
                                <li style="{{ $cad->user_app_id > 0 ? 'display:none' : '' }}"><a href="#" onclick="PlayerEdit({{$cad->id}})"><i class="icon-pencil pull-right"></i> Alterar o Nome</a></li>
                                <li style="{{ $cad->user_app_id > 0 ? 'display:none' : '' }}" class="divider"></li>
                                <li><a href="#" onclick="AlterImg({{$cad->id}})" ><i class="icon-image2 pull-right"></i> Alterar Foto</a></li>
                                <li><a href="#" onclick="ImagemDel({{ $cad->id }})" ><i class="icon-cancel-square pull-right"></i> Remover Foto</a></li>
                                <li style="{{ ($statusblind>0)||$cad->active ? 'display:none' : '' }}" class="divider"></li>
                                <li style="{{ ($statusblind>0)||$cad->active ? 'display:none' : '' }}"><a href="#" onclick="PlayerDel('{{ $cad->id }}','{{ $cad->active }}')"><i class="icon-trash pull-right"></i> Remover Jogador</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </td>
        <td class="col-sm-1">
            {{$cad->fichas_buyin}}
        </td>
        <td class="col-sm-1 text-center">
            <div class="thumb{{ $cad->card_app > 0 ? '' : ' div-disabled' }}">
                <img  alt="" style="height: 60px;width: auto;" class="{{ $cad->saiu ? 'tr_desabilitado' : '' }}"
                      src="{{asset('/my/images/card_'.$cad->card_app.'.png')}}">
            </div> {{$cad->premium_app}}
        </td>
        <td class="col-sm-1 p-5">
            <input type="number" name="player_bonus{{$cad->id}}" id="player_bonus{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->saiu||($cad->ranking==1) ? ' tr_saiu' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->fichas_bonus }}" {{ $cad->saiu||($cad->ranking==1) ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-1 col-xs-1 text-center">
            <input type="checkbox" name="player_passaport{{$cad->id}}" id="player_passaport{{$cad->id}}" onchange="btn_PassaportPlayer('{{$cad->id}}')"
                   class="{{ $cad->saiu||($cad->ranking==1) ? 'tr_desabilitado' : '' }}"
                   value="1" {{ $cad->passaport ? 'checked' : '' }} {{ $cad->saiu||($cad->ranking==1) ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-1 col-xs-1 text-center" class="{{ $cad->saiu ? 'tr_desabilitado' : '' }}">
            <button type="button" class="btn {{ $cad->active ? 'btn-success' : 'btn btn-warning' }} heading-btn" id="btn_player_habilitar{{$cad->id}}" onclick="btn_HabilitarPlayer('{{$cad->id}}','{{$cad->active}}')" {{ (($cad->saiu)||($cad->ranking==1)) ? 'disabled' : '' }}><i class="icon-vcard"></i> {{ $cad->active ? 'ON' : 'OFF' }}</button>
        </td>
        <td class="col-sm-1 p-5 text-center{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <input type="hidden" id="player_rebuy{{$cad->id}}" name="player_rebuy{{$cad->id}}" value="{{$cad->rebuy}}">
            <strong><span id="player_rebuy_text{{$cad->id}}" style="font-size:16px">{{ $cad->rebuy  }}</span></strong><br>
            <button type="button" class="btn btn-secundary btn_del heading-btn{{ $cad->saiu ? ' tr_desabilitado' : '' }}" id="btn_rebuy_del{{$cad->id}}" onclick="btn_Rebuy('{{$cad->id}}','2')" {{ $cad->saiu||!$cad->active||($cad->ranking==1) ? 'disabled' : '' }}><i class="icon-subtract"></i></button>
            <button type="button" class="btn btn-secundary btn_add heading-btn{{ $cad->saiu ? ' tr_desabilitado' : '' }}" id="btn_rebuy_add{{$cad->id}}" onclick="btn_Rebuy('{{$cad->id}}','1')" {{ $cad->saiu||!$cad->active||($cad->ranking==1) ? 'disabled' : '' }}><i class="icon-add"></i></button>
        </td>
        <td class="col-sm-1 p-5 text-center{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <input type="hidden" id="player_addon{{$cad->id}}" name="player_addon{{$cad->id}}" value="{{$cad->addon}}">
            <strong><span id="player_addon_text{{$cad->id}}" style="font-size:16px">{{ $cad->addon  }}</span></strong><br>
            <button type="button" class="btn btn-secundary btn_del heading-btn{{ $cad->saiu ? ' tr_desabilitado' : '' }}" id="btn_addon_del{{$cad->id}}" onclick="btn_Addon('{{$cad->id}}','2')" {{ $cad->saiu||!$cad->active||($cad->ranking==1) ? 'disabled' : '' }}><i class="icon-subtract"></i></button>
            <button type="button" class="btn btn-secundary btn_add heading-btn{{ $cad->saiu ? ' tr_desabilitado' : '' }}" id="btn_addon_add{{$cad->id}}" onclick="btn_Addon('{{$cad->id}}','1')" {{ $cad->saiu||!$cad->active||($cad->ranking==1) ? 'disabled' : '' }}><i class="icon-add"></i></button>
        </td>
        <td class="col-sm-1 p-5{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <input type="number" name="player_mesa{{$cad->id}}" id="player_mesa{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->saiu||($cad->ranking==1) ? ' tr_saiu' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->mesa }}" {{ $cad->saiu||!$cad->active ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-1 p-5{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <input type="number" name="player_cadeira{{$cad->id}}" id="player_cadeira{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->saiu||($cad->ranking==1) ? ' tr_saiu' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->cadeira }}" {{ $cad->saiu||!$cad->active ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-1 col-xs-1 text-right{{ $cad->active ? '' : ' tr_desabilitado' }}">
            <button type="button" class="btn {{ $cad->saiu ? 'btn-secundary' : 'btn-warning' }}" id="btn_player_eliminado{{$cad->id}}" onclick="btn_EliminarPlayer('{{$cad->id}}','{{$cad->saiu}}')" {{ $cad->active&&$cad->ranking<>1 ? '' : 'disabled' }}><i class="icon-user-block"></i></button>
        </td>
        <td class="col-sm-1 col-xs-1 text-right">
            <button type="button" class="btn btn-secundary" id="btn_player_info{{$cad->id}}" onclick="btn_InfoPlayer('{{$cad->id}}')" ><i class="icon-info3"></i></button>
        </td>
    </tr>
@empty
    <tr id="tr_aviso">
        <td colspan="3">
            <div class="alert bg-info alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span id="alerta_text">Você não possui Jogadores neste Blind! Clique em "Adicionar Jogador".</span>
            </div>
        </td>
    </tr>
@endforelse

