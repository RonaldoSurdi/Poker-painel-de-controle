<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CheckClub
{
    /** Verifica se o clube esta logado ***/
    public function handle($request, Closure $next)
    {
        if (!Auth::check()){
            Session::put('url', URL::full());
            Session::flash('Saviso', 'Para acessar esta página você precisa ter ser clube cadastrado conosco!');
            return redirect()->to('/premium');
        }



        //Se tem redirecionamento
        if (Session::has('url') ) {
            $url = Session::pull('url', URL::full() );
            return redirect()->to($url);
        }else
            return $next($request);
    }
}
