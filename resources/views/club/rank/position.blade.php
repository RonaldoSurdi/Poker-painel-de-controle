@php($i=0)
@foreach($lista as $cad)
    @php($i++)
    <tr id="player{{$cad->id}}">
        <td class="col-xs-1 text-right">
            <h2 class="no-padding no-margin">{{ $i }}º</h2>
        </td>
        <td class="col-xs-10 p-5">

            <div class="media">
                <div class="media-left">
                    <a href="{{ $cad->photo() }}" data-popup="lightbox">
                        <img src="{{ $cad->photo() }}" class="img-circle img-lg img-preview" alt="">
                    </a>
                </div>

                <div class="media-body valign-middle">
                    <h6 class="media-heading">{{$cad->name}}</h6>
                </div>
            </div>

        </td>
        <td class="col-xs-1 text-right">
            <h2 class="no-padding no-margin">{{ $cad->total() }}</h2>
        </td>
    </tr>
@endforeach
