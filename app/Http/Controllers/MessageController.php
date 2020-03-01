<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\MessageError;
use App\Models\MessageOneSignal;
use App\Models\MessageUser;
use App\Models\UserApp;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = Message::whereclub_id(Auth::user()->club_id)
            ->orderby('id','desc')
            ->get();
        return view('club.msgs.list',compact('lista'));
    }

    public function search(Request $request){
        $busca = $request['busca'];

        $lista = Message::whereclub_id(Auth::user()->club_id)
            ->where(function ($query) use ($busca) {
                $query->where('title', 'like', '%'.$busca.'%')
                    ->orwhere('text', 'like', '%'.$busca.'%')
                ;
            })
            ->orderby('id','desc')
            ->get();
        return view('club.msgs.list',compact('lista','busca'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cad = new Message();
        $cad->id = 0;
        $cad->user_type =1;
        $cad->radius = 150;
        return view('club.msgs.edit',compact('cad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageRequest $request)
    {
        //
        if ($request['idd']=='0'){
            $cad= new Message();
            $cad->club_id = Auth::user()->club_id;
        }else{
            $cad = Message::find($request['idd']);
            if (!CadastroDoLogado($cad))
                return false;
        }

        $cad->title = $request['title'];
        $cad->user_type = $request['user_type'];
        $cad->radius = $request['radius'];
        $cad->msg_type = $request['msg_type'];
        $cad->text = $request['desc'];
        $cad->date_send = $request['date_day'].' '.$request['date_hour'];
        $cad->status = 0;
        if ($request['user_type']==1)
            $cad->approved = 1;
        else
            $cad->approved = 0;
        $cad->save();

        $this->SalvarImagem($cad,$request);

        Session::flash('Sok','Messagem salva! Aguarde a Aprovação');
        return redirect()->route('club.msg.show',['id'=>$cad]);
    }

    function SalvarImagem(Message $cad,Request $request){
        //imagem
        if ($request['img1']){
            //dados do arquivo ftp
            $arquivo = $request->file('img1');
            $nome = $arquivo->getClientOriginalName();
            $ext = $arquivo->getClientOriginalExtension();
            $arqOld = $cad->img_ext;

            $arqName = $cad->id.'/imagem.'.$ext;

            /*** Salvar no banco ***/
            $cad->img_ext = $ext;
            $cad->save();

            /*** apagar o logo antigo ***/
            if ($arqOld<>''){
                $arqOld = $cad->id.'/imagem.'.$arqOld;
                if ( Storage::disk('messages')->exists($arqOld) )
                    Storage::disk('messages')->delete($arqOld);
            }

            /***** Salvar a foto no ftp *****/
            try {
                $file = file_get_contents($arquivo);
            } catch (\Exception $e) {
                return ["result"=>"N","message"=> 'Seu navegador não enviou a imagem, tente novamente!'];
                Log::debug($e);
            }
            Storage::disk('messages')->put($arqName , $file );

            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.msg');
        return view('club.msgs.show',compact('cad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.msg');
        return view('club.msgs.edit',compact('cad'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $cad)
    {
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.msg');
        //

        Storage::disk('messages')->deleteDirectory( $cad->id );
        Auditoria('DELETE','MESSAGE',$cad->id);
        $cad->delete();

        Session::flash('Sok', 'Messagem excluida!');
        return redirect()->route('club.msg');
    }

    public function getClubMessages(Request $request){
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

        $lista = MessageUser::select('message_users.*')
            ->join('messages','messages.id','message_id')
            ->whereuser_app_id($request['user_id'])
            ->whereclub_id($request['club_id'])
            ->orderby('message_users.id','desc')
            ->get();

        $data = $this->MontarLista($lista);

        return ['result'=>'S','items'=>$data];
    }

    public function MontarLista($lista){
        $data = collect();
        foreach ($lista as $item){
            $msg = $item->msg;

            // se foi lido
            $read = 'N';
            if ($item->status==3)
                $read='S';

            if ($msg) {
                /** montar */
                $data->push([
                    'id' => $msg->id,
                    'title' => TratarNull($msg->title),
                    'date' => date('d/m/y H:i', strtotime($msg->date_send)),
                    'read' => $read,
                    'club' => [
                        'id' => $msg->club_id,
                        'name' => $msg->club->name
                    ],
                ]);
            }
        }
        return $data;
    }

    public function getUserMessages(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }


        $lista = MessageUser::whereuser_app_id($request['user_id'])
            ->orderby('id','desc')
            ->get();

        $data = $this->MontarLista($lista);

        return ['result'=>'S','items'=>$data];
    }

    public function getMessage(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'msg_id' => 'required|exists:messages,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'msg_id.required'=>"Informe a Mensagem",
                'msg_id.exists'=>"Mensagem não cadastrada",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** marca como lida ***/
        $msguser = MessageUser::wheremessage_id($request['msg_id'])
            ->whereuser_app_id($request['user_id'])
            ->first();
        if (!$msguser){
            // senao tem cria
            $msguser = new MessageUser();
            $msguser->message_id = $request['msg_id'];
            $msguser->user_app_id = $request['user_id'];
            $msguser->status = 3;
            $msguser->save();
        }elseif ($msguser->status<3){
            //marca como lida
            $msguser->status = 3;
            $msguser->save();
        }

        /**** carrega a msg ***/
        $data = collect();
        $cad = Message::find($request['msg_id']);
        $texto = '';
        $img = '';
        if ($cad->msg_type==2)
            $img = $cad->img();
        else
            $texto = TratarNull($cad->text);

        /** montar */
        $data->push([
            'id'=>$cad->id,
            'title'=> TratarNull($cad->title),
            'date'=> date('d/m/Y H:i',strtotime($cad->date_send) ),
            'read'=> 'S',
            'text'=> $texto,
            'image'=> $img,
            'club'=> [
                'id'=> $cad->club_id,
                'name'=> $cad->club->name
            ],
        ]);

        return ['result'=>'S','items'=>$data];
    }

    public function sendMessage(Request $request){
        if ($request['verify']!=='805463') {
            return view('erros.404');
        } else {
            // Listar as msg pendentes para envio
            $lista = Message::wherestatus(1)
                ->whereapproved(1)
                ->whereRaw('date_send <= current_timestamp')
                ->orderby('id', 'desc')
                ->get();

            foreach ($lista as $msg) {
                /***** Criar os usuarios *****/
                if ($msg->user_type == 3) {
                    //todos usuarios
                    $users = UserApp::all();

                } elseif ($msg->user_type == 2) {
                    //usuarios em um raio
                    $users = DB::select(DB::raw('select id, (6371 *
                                                acos(
                                                    cos(radians(' . $msg->club->lat . ')) *
                                                    cos(radians(lat)) *
                                                    cos(radians(' . $msg->club->lng . ') - radians(lng)) +
                                                    sin(radians(' . $msg->club->lat . ')) *
                                                    sin(radians(lat))
                                                )) AS distance
                                                from user_app 
                                                where (deleted_at is null)
                                                group by id,distance
                                                HAVING distance <= ' . $msg->radius . '
                                                order by distance '
                    ));

                } else {//somente os seguidores
                    $users = UserApp::select('user_app.id')
                        ->join('user_follows', 'user_follows.user_app_id', 'user_app.id')
                        ->where('user_follows.club_id', $msg->club_id)
                        ->get();
                }

                /*** apaga se tiver usuarios salvos ***/
                //MessageUser::wheremessage_id($msg->id)->delete();
                DB::table('message_users')->where('message_id', $msg->id)->delete();

                //Salvar os usuarios
                foreach ($users as $user) {
                    $cad = new MessageUser();
                    $cad->message_id = $msg->id;
                    $cad->user_app_id = $user->id;
                    $cad->status = 0;
                    $cad->save();
                }

                /** faz envio pelo OneSignal ***/
                $response = $this->sendOneSignal($msg);

                /*** Verifica o retorno ***/
                Log::info($response);
                $obj = json_decode($response, true);
                if (str_contains($response, 'recipients')) {
                    //deu certo //salva novo status
                    $msg->status = 2;
                    $msg->save();

                    //grava os dados da msg
                    $one = new MessageOneSignal();
                    $one->message_id = $msg->id;
                    $one->one_msg_id = $obj['id'];
                    $one->qtd = $obj['recipients'];
                    $one->save();

                    //Marca os usuarios como enviados
                    MessageUser::wheremessage_id($msg->id)->update(['status' => 1]);

                } elseif (str_contains($response, 'erro')) {
                    //salvar o erro
                    $msg->status = 3;
                    $msg->save();

                    foreach ($obj['errors'] as $msg_erro) {
                        $erro = new MessageError();
                        $erro->message_id = $msg->id;
                        $erro->error = $msg_erro;
                        $erro->save();
                    }
                }


            }
        }
    }

    function sendOneSignal($msg){
        $title = array(
            "en" => $msg->title,
            "pt" => $msg->title,
        );

        $content = array(
            "en" => $msg->club->name,
            "pt" => $msg->club->name,
        );

        $fields = array(
            'app_id' => "48ab8249-e50d-42a6-b46f-ecf31e023419",
            //f8fec6cb-d5bc-4ce1-ab77-9c49b62a6b58
            //b52187cd-1b28-456f-b316-87f4cc82d101
            'data' => array("msg_id" => $msg->id),
            'contents' => $content,
            'headings' => $title,
            'ios_sound' => 'onesignal_default_sound.wav',
            'android_sound' => 'onesignal_default_sound',
            'adm_sound' => 'onesignal_default_sound',
            'android_led_color' => 'FFD700', //dourado
        );


        /***** Criar os usuarios *****/
        if ($msg->user_type==3){
            //todos usuarios
            //$fields['included_segments'] = array('MyTestUsers');
            $fields['included_segments'] = array('All');

        }elseif ($msg->user_type==2){
            //usuarios em um raio
            $fields['filters'] =  array(
                                        array("field"   => "location"
                                            , "radius"  => $msg->radius*1000 //in meters
                                            , "lat"     => $msg->club->lat
                                            , "long "   => $msg->club->lng
                                            )
                                       );

        }else{//somente os seguidores

            $players = array();

            //Inclui a lista de usuarios seguidores que tem UID
            foreach ($msg->users as $userm){
                if ($userm->user->onesignal_uid<>'')
                    array_push($players, $userm->user->onesignal_uid);
            }

            //Config do one signal para o uid
            $fields['include_player_ids'] = $players;
        }

        //Transforma em Json Encode
        $fields = json_encode($fields);

        //Faz a chamada no OneSignal
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZjhhNjQ2YjItNDMwOS00ZDAwLTljMTAtZWI0M2JkMTk1MGY5'));
        //MWE4Y2Q1YzEtYmZjNC00OWIxLWE5NmMtYzA3N2M1MTFmMzll
        //MWFmYzNlNzEtYWQ1NS00YWFkLWI5ZmEtNjMyZDRmOWM3ZWQ4
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        //pega o retorno
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function enviar(Message $cad){
        /**** verificar se é do club logado ***/
        if (!CadastroDoLogado($cad))
            return redirect()->route('club.msg');

        $cad->status=1;
        $cad->save();
        return redirect()->route('club.msg.show',['id'=>$cad->id]);
    }

    public function deleteMsgUser(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'msg_id' => 'required|exists:messages,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'msg_id.required'=>"Informe a Mensagem",
                'msg_id.exists'=>"Mensagem não cadastrada",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*** exclui a msg ***/
        MessageUser::wheremessage_id($request['msg_id'])
            ->whereuser_app_id($request['user_id'])
            ->delete();

        return ["result"=>"S","message"=>'Mensagem excluída!'];
    }

    public function deleteAllMessages(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
//            'club_id' => 'nullable|exists:clubs,id',
        ],
            [
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );
        if ($validator->fails()) {/***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $clube = 'messages.club_id>0';
        if ($request->has('club_id'))
            $clube = 'messages.club_id='.$request['club_id'];

        /*** exclui a msg ***/
        $lista = MessageUser::select('message_users.*')
            ->join('messages','messages.id','message_users.message_id')
            ->whereuser_app_id($request['user_id'])
            ->whereRaw($clube)
            ->get();

        foreach ($lista as $cad){
            $cad->delete();
        }

        return ["result"=>"S","message"=>'Mensagens excluídas!'];
    }
}
