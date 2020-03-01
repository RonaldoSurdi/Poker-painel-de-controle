<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserPassRequest;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

    public function reset(Request $request)
    {
        /*** Aceitar todas as URL de outros sites */
        header('Access-Control-Allow-Origin: *'); 

        $this->sendResetLinkEmail($request);

        return [
            'result' => 'S'
            , 'message'=>"Link enviado para ".$request['email']
        ];
    }
}
