@forelse($lista as $cad)
    <tr id="award{{$cad->id}}">
        <td class="col-sm-1 p-5">
            <input type="number" name="award_ranking{{$cad->id}}" id="award_ranking{{$cad->id}}"
                   class="form-control text-blue-800" placeholder="-"
                   min='0' step="1" value="{{ $cad->ranking }}" >
        </td>
        <td class="col-sm-2 p-5">
            <input type="text" name="award_valor{{$cad->id}}" id="award_valor{{$cad->id}}"
                   class="form-control text-blue-800" placeholder="-"
                   min='0' max="255" value="{{ number_format($cad->valor,2,",",".") }}" >
        </td>
        <td class="col-sm-2 p-5">
            <input type="number" name="award_points{{$cad->id}}" id="award_points{{$cad->id}}"
                   class="form-control text-blue-800" placeholder="-"
                   min='0' step="1" value="{{ $cad->points }}" >
        </td>
        <td class="col-sm-4 p-5">
            <input type="text" name="award_another{{$cad->id}}" id="award_another{{$cad->id}}"
                   class="form-control text-blue-800" placeholder="-"
                   min='0' max="255" value="{{ $cad->another }}" >
        </td>
        <td class="col-sm-1 col-xs-1 text-right">
            <button type="button" class="btn btn-secundary" id="btn_award_del{{$cad->id}}" onclick="AwardDel('{{$cad->id}}')"><i class="icon-cancel-circle2"></i></button>
        </td>
    </tr>
    <script>
        $("#award_valor{{$cad->id}}").mask("#.##0,00", {reverse: true});
    </script>
@empty
    <tr id="tr_aviso">
        <td colspan="3">
            <div class="alert bg-info alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span id="alerta_text">Você não possui Prêmios neste Blind! Clique em "Adicionar Prêmio".</span>
            </div>
        </td>
    </tr>
@endforelse
