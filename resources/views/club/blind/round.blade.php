@forelse($lista as $cad)
    <tr id="round{{$cad->id}}" class="{{ $cad->break ? 'tr_disabled' : '' }}">
        <td class="col-sm-3 p-5">
            <div class="media">
                <div class="media-body valign-middle">
                    <h6 class="media-heading nameround" id="round_name{{$cad->id}}">{{$cad->name}} {{ $cad->break ? '' : $cad->small_blind.'/'.$cad->big_blind }}</h6>
                </div>
                <div class="media-right media-middle">
                    <a href="#" id="roundup{{ $cad->id }}" onclick="RoundUp({{ $cad->id }})"><i class="icon-arrow-up15 pull-right"></i></a>
                </div>
                <div class="media-right media-middle">
                    <a href="#" id="rounddown{{ $cad->id }}" onclick="RoundDown({{ $cad->id }})"><i class="icon-arrow-down15 pull-right"></i></a>
                </div>
                <div class="media-right media-middle">
                    <a href="#" id="rounddel{{ $cad->id }}" onclick="RoundDel({{ $cad->id }})"><i class="icon-trash pull-right"></i></a>
                </div>
            </div>
        </td>
        <td class="col-sm-2 p-5">
            <input type="time" name="round_duration{{$cad->id}}" id="round_duration{{$cad->id}}"
                   class="form-control text-blue-800" placeholder="-"
                   min='0:00' max="23:59" value="{{ $cad->duration }}" >
        </td>
        <td class="col-sm-2 p-5">
            <input type="number" name="round_ante{{$cad->id}}" id="round_ante{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->break ? ' tr_disabled' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->break ? '' : $cad->ante }}" {{ $cad->break ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-2 p-5">
            <input type="number" name="round_small_blind{{$cad->id}}" id="round_small_blind{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->break ? ' tr_disabled' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->break ? '' : $cad->small_blind }}" {{ $cad->break ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-2 p-5">
            <input type="number" name="round_big_blind{{$cad->id}}" id="round_big_blind{{$cad->id}}"
                   class="form-control text-blue-800{{ $cad->break ? ' tr_disabled' : '' }}" placeholder="-"
                   min='0' step="1" value="{{ $cad->break ? '' : $cad->big_blind }}" {{ $cad->break ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-2 p-5 text-right">
            <input type="checkbox" name="round_rebuy{{$cad->id}}" id="round_rebuy{{$cad->id}}"
                   class="{{ $cad->break ? 'tr_disabled' : '' }}"
                   value="1" {{ $cad->rebuy ? 'checked' : '' }} {{ $cad->break ? 'disabled' : '' }}>
        </td>
        <td class="col-sm-2 p-5 text-right">
            <input type="checkbox" name="round_addon{{$cad->id}}" id="round_addon{{$cad->id}}"
                   class="{{ $cad->break ? 'tr_disabled' : '' }}"
                   value="1" {{ $cad->addon ? 'checked' : '' }} {{ $cad->break ? 'disabled' : '' }}>
        </td>
    </tr>
@empty
    <tr id="tr_aviso">
        <td colspan="3">
            <div class="alert bg-info alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span id="alerta_text">Você não possui Rounds neste Blind! Clique em "Adicionar Round".</span>
            </div>
        </td>
    </tr>
@endforelse
