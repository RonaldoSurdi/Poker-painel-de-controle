<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\GalleryView;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class GalleryPhotoController extends Controller
{
    public function AddPhotoGal(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'gal_id' => 'required|exists:galleries,id',
            'foto1' => 'required|file',
        ],
            [
                'gal_id.required'=>"Informe a galeria logado",
                'gal_id.exists'=>"Galeria não cadastrada",
                'foto1.required'=>"Informe uma foto do clube",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //dados do arquivo ftp
        $arquivo = $request->file('foto1');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();

        $club_id = Auth::user()->club_id;
        $gal_id = $request['gal_id'];
        $gal = Gallery::whereid($gal_id)->whereclub_id($club_id)->first();
        if (!$gal)
            return ["result"=>"N","message"=>'Galeria não encontrada no seu Clube'];

        /*** qtd maxima de photos do clube ***/
        if ($gal->title=='HOME'.$club_id)
            if ($gal->qtd_photos()>=10){
                return ["result"=>"N","message"=>'Ops!<br>Você atingiu o limite de 10 fotos!
                <br>Aqui nos dados do clube o limite é de 10 fotos, para enviar mais fotos utilize o menu "Fotos" e crie suas galerias!'];
            }


        /*** salvar na base a foto ***/
        $cad = new GalleryPhoto();
        $cad->gallery_id = $gal->id;
        $cad->ext = $ext;
        $cad->save();

        $arqName = $cad->id.'.'.$ext;

        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou a foto, tente novamente!'];
            Log::debug($e);
        }
        Storage::disk('photos')->put($gal->id.'/'.$arqName , $file );

        /*** ok ***/
        return ["result"=>"S","message"=>'Foto salva!','photo_id'=>$cad->id];
    }


    public function AddPhotoClub(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'idd' => 'required|exists:clubs,id',
            'foto1' => 'required|file',
        ],
            [
                'idd.required'=>"Informe o clube logado",
                'idd.exists'=>"Clube não cadastrado",
                'foto1.required'=>"Informe uma foto do clube",
            ]
        );

        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }


        /**** Verificar se tem a galeria de HOME ***/
        $club_id = $request['idd'];
        $gal = Gallery::whereclub_id($club_id)
            ->wheretitle('HOME'.$club_id)
            ->first();
        if (!$gal){
            $gal = new Gallery();
            $gal->club_id = $club_id;
            $gal->title = 'HOME'.$club_id;
            $gal->save();
        }

        /*** qtd maxima de photos ***/
        if ($gal->qtd_photos()>=10){
            return ["result"=>"N","message"=>'Ops!<br>Você atingiu o limite de 10 fotos!
            <br>Aqui nos dados do clube o limite é de 10 fotos, para enviar mais fotos utilize o menu "Fotos" e crie suas galerias!'];
        }

        /***Adiciona a galeria ao request **/
        $request['gal_id'] = $gal->id;

        return $this->AddPhotoGal($request);
    }


    public function photos(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'gal_id' => 'required|exists:galleries,id',
        ],
            [
                'gal_id.required'=>"Informe a galeria",
                'gal_id.exists'=>"Galeria não encontrada",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** variaveis **/
        $club_id = Auth::user()->club_id;
        $gal_id = $request['gal_id'];
        if ($request->has('photo_id'))
            $photo_id = $request['photo_id'];
        else
            $photo_id =0;

        /** se tem foto procura ela */
        if ($photo_id>0){
            $lista = GalleryPhoto::whereid($request['photo_id'])->get();
        }else{
            /** se tem galeria procura ela */
            $gal = Gallery::whereid($gal_id)
                ->whereclub_id($club_id)
                ->first();
            if (!$gal)
                $lista = new Collection();
            elseif (!$gal->photos)
                $lista = new Collection();
            else
                $lista = $gal->photos;
        }

        $html = View::make('club.gal.photos', compact('lista'))->render();

        return ["result"=>"S",'qtd'=>$lista->count(),'html'=>$html];
    }

    public function SetDescPhoto(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'photo_id' => 'required|exists:gallery_photos,id',
        ],
            [
                'photo_id.required'=>"Informe a foto",
                'photo_id.exists'=>"Foto não cadastrada",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /// localizar a foto
        $cad = GalleryPhoto::find($request['photo_id']);

        /**** verificar se essa foto é dele ***/
        if ($cad->gallery->club_id <> Auth::user()->club_id)
            return ["result"=>"N","message"=>'Foto não encontrada no seu clube!'];

        ///salvar os dados
        $cad->info = $request['photo_desc'];
        $cad->save();
        return ["result"=>"S","message"=>'Descrição salva!'];
    }

    public function DelPhoto(GalleryPhoto $cad){
        /**** verificar se essa foto é dele ***/
        if ($cad->gallery->club_id <> Auth::user()->club_id)
            return ["result"=>"N","message"=>'Foto não encontrada no seu clube!'];

        /**** nome do arquivo ***/
        $arqName = $cad->gallery_id.'/'.$cad->id.'.'.$cad->ext;

        //exclui o arquivo
        Storage::disk('photos')->delete( $arqName );
        //apaga no banco
        $cad->delete();

        return ["result"=>"S","message"=>'Foto Excluida!'];
    }

    public function getPhotosGallery(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'gallery_id' => 'required|exists:galleries,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'gallery_id.required'=>"Informe a galeria",
                'gallery_id.exists'=>"Galeria não cadastrada",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** registrar a visita ***/
        $view = new GalleryView();
        $view->user_app_id = $request['user_id'];
        $view->gallery_id = $request['gallery_id'];
        $view->save();

        /** carregar club */
        $gal = Gallery::find($request['gallery_id']);
        $data = collect();
        foreach ($gal->photos as $item){
            $data->push([
                'id' => $item->id,
                'description' => TratarNull( $item->info ),
                'photo' => $item->img(),
            ]);
        }

        /** retorno */
        return ['result'=>'S','items'=>$data];
    }
}
