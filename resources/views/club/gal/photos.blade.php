@foreach($lista as $photo)
    <div class="col-md-12" id="row_photo{{$photo->id}}">
        <div class="thumbnail row">
            <div class="thumb col-xs-4" style="max-height: 150px;">
                <img src="{{$photo->img()}}" class="img-rounded" alt="" style="max-height: 140px; width: auto;">
                <div class="caption-overflow">
                    <span>
                        <a href="{{$photo->img()}}" title="Vizualizar Foto" data-popup="lightbox" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>
                    </span>
                </div>
            </div>

            <div class="caption  col-xs-8 pt-5">
                <h6 class="no-margin-top text-semibold"><a href="#" class="text-default">Descrição da foto</a>
                    <a href="#" title="Excluir foto" class="text-danger" onclick="PhotoDel({{$photo->id}})"><i class="icon-trash pull-right"></i></a>
                </h6>
                <form id="frm_photodesc{{$photo->id}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="photo_id" value="{{$photo->id}}">
                    <textarea id="photo_desc{{$photo->id}}" name="photo_desc" class="form-control" rows="3" maxlength="190">{{$photo->info}}</textarea>
                </form>
                <button type="button" class="btn btn-sm btn-primary heading-btn pull-right"
                        id="btn_descFoto{{$photo->id}}" onclick="SetDescPhoto({{$photo->id}})">Salvar</button>
            </div>
        </div>
    </div>
@endforeach