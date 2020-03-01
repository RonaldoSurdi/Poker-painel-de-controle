<?php

namespace App\Http\Controllers;

use App\Models\ClubIndicado;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('check.user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raio = 30;
        //Raio selecionado
        if (Session::has('raio')){
            $raio = Session::pull('raio');
        }else
        //Ultima config de raio
        if (Session::has('userapp_id')){
            $cad = UserLocation::whereuser_app_id(Session::has('userapp_id'))
                ->orderby('id','desc')
                ->limit(1)
                ->where('radius','>',0)
                ->first();
            if ($cad)
                $raio = $cad->radius;
        }

        return view('map.home', compact('raio'));
    }

    public function raio(Request $request)
    {
        $raio = $request['radius'];
        Session::put('raio',$raio);
        return redirect('/map');
    }

    public function contato(Request $request){
        ///Corpo da msg
        $linhas = array();
        $linhas[0] = 'Solicitação de contato preenchido no site';
        $linhas[1] = '<b>Nome:</b> '.$request['ct_name'];
        $linhas[2] = '<b>E-mail:</b> '.$request['ct_email'];
        $linhas[3] = '<b>Telefone:</b> '.$request['ct_fone'];
        $linhas[4] = '<b>Mensagem:</b> <br>'.nl2br($request['ct_msg']);

        $data['Lines']  = $linhas;

        //Envia o e-mail
        Mail::send('emails.padrao', $data, function($message) use ($data,$request) {
            $message->from($request['ct_email'],$request['ct_name'] );
            $message->returnPath('contato@pokerclubsapp.com.br');
            $message->to( 'contato@pokerclubsapp.com.br' , 'POKERCLUBS' )
                ->subject('Contato PokerClubs');
        });

        return ["result"=>"S"];
    }
}

