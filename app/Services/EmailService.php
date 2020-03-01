<?php
/**
 * Created by PhpStorm.
 * User: Homologacao
 * Date: 11/03/2016
 * Time: 15:12
 */

namespace App\Services;

use App\Models\UserApp;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public static function ConfirmarUsuario($id)
    {
        $cad = UserApp::find($id);
        if (!$cad) return 'Nao achei';

        //
        $id = base64_encode($id);
        $url = route('user.confirm',['id'=>$id]);
        $app = 'PokerClubs';

        ///Dados para o e-amil
        $data = array();
        $data['nome']   = So1Nome($cad->name);
        $data['email']  = $cad->email;

        ///Corpo da msg
        $linhas = array();
        $linhas[0] = 'Recebemos sua solicitação de cadastro no App '.$app.', clique no botão abaixo para confirmar seu cadastro.';

        $data['Lines']  = $linhas;
        $data['actionText']  = 'Confirmar Cadastro';
        $data['actionUrl']  = $url;

        //Envia o e-mail
        Mail::send('emails.padrao', $data, function($message) use ($data) {
            $message->to( $data['email'] , $data['nome'] )
                ->subject('Confirme seu cadastro no PokerClubs');
        });

        return "enviado";
    }
}