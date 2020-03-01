@for($i=1; $i<=$qtd; $i++)
    <a href="#" class="list-group-item" onclick="StepLoad({{$i}})" title="Carregar etapa {{$i}}" >
        <i class="fa fa-arrow-right"></i> <span class="text-blue">{{ $i }}º Etapa</span>
        <i class="icon-spinner2 spinner pull-right" id="load_step{{$i}}" style="display: none"></i>
    </a>
@endfor
