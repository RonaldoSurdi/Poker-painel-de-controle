<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Gallery::whereclub_id(Auth::user()->club_id)
            ->where('title','<>','HOME'.Auth::user()->club_id)
            ->orderby('id','desc')
            ->get();

        return view('club.gal.list',compact('lista'));
    }

    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Gallery::whereclub_id(Auth::user()->club_id)
            ->where('title','<>','HOME'.Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('title', 'like', '%'.$busca.'%')
                    ->orWhere('desc', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.gal.list',compact('lista','busca'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cad = new Gallery();
        $cad->id =0;
        $cad->date_event = date('Y-m-d');
        return view('club.gal.edit',compact('cad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'idd' => 'required',
            'title' => 'required',
            'date_event' => 'required|date',
        ],
            [
                'idd.required'=>"Informe a galeria logado",
                'idd.exists'=>"Galeria não cadastrada",
                'title.required'=>"Informe um titulo",
                'date_event.required'=>"Informe a data",
                'date_event.date'=>"Informe a data",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /**** Verifica se a galeria existe ****/
        if ($request['idd']>0) {
            $cad = Gallery::whereid($request['idd'])->whereclub_id(Auth::user()->club_id)->first();
            if (!$cad)
                return ["result"=>"N","message"=>'Galeria não encontrada no seu Clube'];
        }else{
            /*** Nova galeria ****/
            $cad = new Gallery();
            $cad->club_id = Auth::user()->club_id;
        }
        /*** Salva os dados novos ****/
        $cad->title = $request['title'];
        $cad->date_event = $request['date_event'];
        $cad->desc = $request['desc'];
        $cad->save();

        /*** ok ***/
        return ["result"=>"S","message"=>'Galeria Salva!','gal_id'=>$cad->id];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gallery  $Gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.gal');

        return view('club.gal.edit',compact('cad'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gallery  $Gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.gal');
        //
        Storage::disk('photos')->deleteDirectory( $cad->id );
        Auditoria('DELETE','GALLERY',$cad->id);
        $cad->delete();



        Session::flash('Sok', 'Galeria Excluida!');
        return redirect()->route('club.gal');
    }

    public function getGalleries(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
            'club_id' => 'required|exists:clubs,id',
        ],
            [
                'club_id.required'=>"Informe o clube",
                'club_id.exists'=>"Clube não cadastrado",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /** carregar club */
        $lista = Gallery::whereclub_id($request['club_id'])
            ->where('title','<>','HOME'.$request['club_id'])
            ->orderby('date_event','desc')
            ->orderby('id','desc')
            ->get();
        $data = collect();
        foreach ($lista as $item){
            $data->push([
                'id' => $item->id,
                'title' => $item->title,
                'date' => date('d/m/Y', strtotime($item->date_event)),
                'description' => TratarNull( $item->desc ),
                'photo' => $item->foto(),
            ]);
        }

        /** retorno */
        return ['result'=>'S','items'=>$data];
    }
}
