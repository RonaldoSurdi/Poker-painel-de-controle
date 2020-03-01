<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CheckLic
{
    /** Verifica se o clube esta logado e tem licença premium ativa***/
    public function handle($request, Closure $next)
    {
        if (!Auth::check()){
            Session::put('url', URL::full());
            Session::flash('Saviso', 'Seu clube não está logado!');
            return redirect()->to('/premium');
        }

        if (!Auth::user()->club){
            Session::put('url', URL::full());
            Session::flash('Saviso', 'Seu clube não foi encontrado!');
            return redirect()->to('/premium');
        }

        /***** verifica a licença do club ***/
        if (!Auth::user()->club->premium()){
            // Session::flash('Saviso', 'Seu clube não possui uma licença premium ativa, entre em contato com nossa equipe e adquira sua licença Premium!');
            return redirect()->route('club.lic');
        }


        //Se tem redirecionamento
        if (Session::has('url') ) {
            $url = Session::pull('url', URL::full() );
            return redirect()->to($url);
        }else
            return $next($request);
    }
}
