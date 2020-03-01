@foreach($lista as $day)
<tr id="day_tr{{$day['qtd']}}">
    <td class="col-sm-2 p-5">
        <span class="text-blue-800">{{$day['qtd']}}º Dia</span>
    </td>
    <td class="col-sm-6 p-5">
        <input type="date" id="day_date{{$day['qtd']}}" name="day_date{{$day['qtd']}}" value="{{$day['date']}}"
               class="form-control text-blue-800" placeholder="data do evento" required>
    </td>
    <td class="col-sm-4 p-5">
        <input type="time" id="day_hour{{$day['qtd']}}" name="day_hour{{$day['qtd']}}" value="{{$day['hour']}}"
               class="form-control text-blue-800" placeholder="Hora do evento" required>
    </td>
    @if ($day['qtd']>1)
        <td class="col-sm-4 p-5">
            <a href="#" onclick="DelDate({{$day['qtd']}})" title="Remover este dia"><i class="icon-trash text-danger"></i> </a>
        </td>
    @endif
</tr>
@endforeach