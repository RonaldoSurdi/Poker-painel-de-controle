<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('userapp_id')){
            Session::put('url', URL::full());
            Session::flash('Saviso', 'Para acessar esta página você precisa ser cadastrado no app, informe seus dados de acesso!');
            return redirect()->to('/');
        }

        //Se tem redirecionamento
        if ( (!$request->secure()) && (in_array(env('APP_ENV'), ['stage', 'production'])) ) {
            return redirect()->secure($request->getRequestUri());
        }else
            return $next($request);
    }
}
