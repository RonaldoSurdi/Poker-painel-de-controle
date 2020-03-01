<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseStatus;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use DateTime;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;
use laravel\pagseguro\Platform\Laravel5\PagSeguro;


class LicenseController extends Controller
{
    public function MudarStatus($id,$new_status,$obs='',$days='1 year'){
        $lic = License::find($id);
        $old = $lic->status;

        $daysx = $days;

        if ($lic->type == 11) {
            $daysx = '30 day';
            $lic->type = 1;
            $lic->save();
        } elseif ($lic->type == 12) {
            $daysx = '6 month';
            $lic->type = 1;
            $lic->save();
        }


        if ($lic->type == 1) {

            $licvencida = License::whereclub_id($lic->club_id)
                ->wheretype(1)
                ->wherestatus(2)
                ->first();

            if ($licvencida) {
                $oldvcd = 2;
            } else {
                $licvencida = License::whereclub_id($lic->club_id)
                    ->wheretype(1)
                    ->wherestatus(1)
                    ->first();
                $oldvcd = 1;
            }

        }

        //Salvar novo status
        $lic->status = $new_status;
        //if ( ($new_status==1) and (!$lic->due_date)){
        if ($new_status==1){
            //Se for ativação e não tem data
            if ($lic->type == 1) {
                if ($licvencida) {
                    if (date('Y-m-d H:i:s', strtotime($licvencida->due_date)) > date('Y-m-d H:i:s', strtotime(now()))) {
                        $lic->start_date = $licvencida->due_date;
                    } else {
                        $lic->start_date = now();
                    }
                } else {
                    $lic->start_date = now();
                }
            } else {
                $lic->start_date = now();
            }
            $lic->due_date = date('Y-m-d H:i:s', strtotime($lic->start_date. ' + '.$daysx));
        }
        $lic->save();

        //Guardar o status
        if ($lic->type == 1) {
            if ($licvencida) {
                $licvencida->status = 4;
                $licvencida->save();
                $cad = new LicenseStatus();
                $cad->license_id = $licvencida->id;
                $cad->old = $oldvcd;
                $cad->new = 4;
                $cad->text = 'Renovação de Licença';
                if (Auth::check())
                    $cad->user_id = Auth::user()->id;
                //
                $cad->save();
            }
        }
        $cad = new LicenseStatus();
        $cad->license_id = $lic->id;
        $cad->old = $old;
        $cad->new = $new_status;
        $cad->text = $obs;
        if (Auth::check())
            $cad->user_id = Auth::user()->id;
        //
        $cad->save();

        //se for ativação de uma licença anual, bloqueio a de 30 dias
        if ( ($lic->type==1) and ($new_status==1) ) {
            $aux = License::wherestatus(1)
                ->wheretype(2)
                ->whereclub_id($lic->club_id)
                ->first();
            if ($aux) {
                $this->MudarStatus($aux->id, 4, 'Bloqueio por ativação de Licença anual');
            } else {
                $aux = License::wherestatus(2)
                    ->wheretype(2)
                    ->whereclub_id($lic->club_id)
                    ->first();
                if ($aux) {
                    $this->MudarStatus($aux->id, 4, 'Bloqueio por ativação de Licença anual');
                }
            }
        }
    }

    public function index()
    {

        $lista = License::whereclub_id(Auth::user()->club->id)
            ->orderby('id')
            ->get();

        $lic = License::whereclub_id(Auth::user()->club->id)
            ->wherestatus(1)
            ->first();

        if ($lic) {
            if ($lic->vencida()) {
                $lic->status = 2;
                $lic->save();
            }
        } else {
            $lic = License::whereclub_id(Auth::user()->club->id)
                ->wherestatus(2)
                ->first();
        }

        $lic30 = License::whereclub_id(Auth::user()->club->id)
            ->wheretype(2)
            ->first();

        $cad = Auth::user()->club;

        return view('club.lic.home',compact('lista','lic','lic30','cad'));
    }

    public function free()
    {
        $lic30 = License::whereclub_id(Auth::user()->club->id)
            ->wheretype(2)
            ->first();

        //Se já utilizou
        if ($lic30){
            Session::flash('Saviso','Você já utilizou sua licença free');
            return redirect()->route('club.home');
        }

        $lic = new License();
        $lic->club_id = Auth::user()->club_id;
        $lic->type = 2;
        $lic->status = 0;
        $lic->save();

        //Ativar Licença
        $this->MudarStatus($lic->id,1,'Ativado pelo clube no site','30 days');

        Session::flash('Sok','Licença Free ativada com sucesso!');
        return redirect()->route('club.home');
    }

    public function premium1()
    {
        $lic = License::whereclub_id(Auth::user()->club->id)
            ->wheretype(11)
            ->where('status','<',1)
            ->first();

        if (!$lic) {
            //Adiciona a licença
            $lic = new License();
            $lic->club_id = Auth::user()->club_id;
            $lic->type = 11;
            $lic->status = 0;
            $lic->value = 129;
            $lic->save();
        }

        return $this->pay($lic,'Licença Mensal');
    }

    public function premium2()
    {
        $lic = License::whereclub_id(Auth::user()->club->id)
            ->wheretype(12)
            ->where('status','<',1)
            ->first();

        if (!$lic) {
            //Adiciona a licença
            $lic = new License();
            $lic->club_id = Auth::user()->club_id;
            $lic->type = 12;
            $lic->status = 0;
            $lic->value = 714;
            $lic->save();
        }

        return $this->pay($lic,'Licença Semestral');
    }

    public function premium()
    {
        $lic = License::whereclub_id(Auth::user()->club->id)
            ->wheretype(1)
            ->where('status','<',1)
            ->first();

        if (!$lic) {
            //Adiciona a licença
            $lic = new License();
            $lic->club_id = Auth::user()->club_id;
            $lic->type = 1;
            $lic->status = 0;
            $lic->value = 1188;
            $lic->save();
        }

        return $this->pay($lic,'Licença Anual');
    }

    public function pay(License $lic, $lictitle)
    {
        header('Access-Control-Allow-Origin: *');

        $club =  $lic->club;

        $clubname = $club->name;

        if (!strCharFind(' ',$clubname)) {
            return ['result'=>'N','message'=>'Atualize sua Razão Social em seu cadastro','code'=>1];
        }

        $phone = preg_replace("/[^0-9]/", "", $club->phone);
        $phoneddd = substr($phone, 0, 2);
        $phonenumber = substr($phone, 2, strlen($phone));

        $doc = preg_replace("/[^0-9]/", "", $club->doc1);

        $docsize = strlen($doc);

        if ($docsize<11) {
            return ['result'=>'N','message'=>'Atualize seu CPF/CNPJ em seu cadastro','code'=>2];
        }

        if ($docsize > 11) {
            $doctype = 'CNPJ';
        } else {
            $doctype = 'CPF';
        }

        $zipcode = preg_replace("/[^0-9]/", "", $club->zipcode);

        $data = [
            'items' => [
                [
                    'id' => $lic->id,
                    'description' => sanitizeString($lictitle),
                    'quantity' => 1,
                    'shippingCost' => 0,
                    'amount' => $lic->value,
                ]
            ],
            'shipping' => [
                'type' => 3,
                'address' => [
                    'postalCode' => $zipcode,
                    'street' => sanitizeString($club->address),
                    'number' => $club->number,
                    'district' => sanitizeString($club->district),
                    'city' => sanitizeString($club->cidade()->name),
                    'state' => $club->cidade()->uf,
                    'country' => 'BRA',
                ],
            ],
            'sender' => [
                'email' => $club->email, //'comprador-de-testes-'.$lic->id.'@sandbox.pagseguro.com.br',
                'name' => sanitizeString($clubname),
                'documents' => [
                    [
                        'number' => $doc,
                        'type' => $doctype,
                    ]
                ],
                'phone' => [
                    'number' => $phonenumber,
                    'areaCode' => $phoneddd,
                ],
                //'bornDate' => '1988-03-25',
            ]

        ];

        $checkout = PagSeguro::checkout()->createFromArray($data);
        $credentials = PagSeguro::credentials()->get();
        $information = $checkout->send($credentials);
        //Retorna um objeto de laravel\pagseguro\Checkout\Information\Information
        if ($information) {
            //Log::warning('getCode: '.$information->getCode());
            //Log::warning('getLink: '.$information->getLink());
            //print_r($information->getDate());
            $codepag = $information->getCode();
            return ['result'=>'S','message'=>'Abrindo Pagamento','code'=>$codepag];
        } else {
            //Session::flash('Saviso','Não foi possivel iniciar o pagamento!');//<br>.$message
            return ['result'=>'N','message'=>'Não foi possivel iniciar o pagamento!','code'=>3];
            //return redirect()->route('club.lic');
        }
    }


    public function callback(Request $request){
        header('Access-Control-Allow-Origin: *');
        $pagseguroCode = $request['notificationCode'];
        //$credentials = PagSeguro::credentials()->get();
        //$credentials_email = $credentials->getEmail();
        //$credentials_token = $credentials->getToken();
        //https://ws.sandbox.pagseguro.uol.com.br
        //https://ws.pagseguro.uol.com.br
        $urldir = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/'.$pagseguroCode.'?email=pagamento@saintec.com.br&token=f0adb2be-2d96-4b0b-848e-b925c5150889fdf39bee4a5cb14cbc903aabcf3c30502297-3da0-4cb9-bbe9-66f04db8e208';//260FC992CC75457E8EF772EF68D81448';

        //$trasactiondata = file_get_contents($urldir);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urldir);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
        curl_close($ch);

        $array_data = json_decode(json_encode(simplexml_load_string($data)), true);
        $tralic = $array_data['code'];
        $stalic = $array_data['status'];
        $codlic = $array_data['items']['item']['id'];
        $paytyp = $array_data['paymentMethod']['type'];
        $paycod = $array_data['paymentMethod']['code'];
        if (array_key_exists('paymentLink',$array_data))
             $paylnk = $array_data['paymentLink'];
        else $paylnk = '';
        $paysta = payMethodRes($paycod);

        $lic = License::find($codlic);
        $lic->payment = $tralic;
        $lic->paymentStatus = $stalic;
        $lic->paymentMethod = $paysta;
        $lic->paymentLink = $paylnk;
        $lic->save();

        if ($stalic == 3) {
            //Log::info($stalic);
            $this->MudarStatus(
                $codlic,
                1,
                'Ativado por pagamento on-line'
            );
        }

        return $pagseguroCode;
    }

    public function setCadData(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
        ],
            [
                'club_id.required'=>"Informe o clube logado",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*****Salvar informações adicionais *****/
        $cad = Club::find($request['club_id']);
        $cad->name = $request['name'];
        $cad->doc1 = $request['doc1'];
        $cad->responsible = $request['responsible'];
        $cad->phone = $request['phone'];
        $cad->whats = $request['whats'];
        $cad->site = $request['site'];
        $cad->zipcode = $request['zipcode'];
        $cad->city_id = $request['city'];
        $cad->address = $request['address'];
        $cad->number = $request['number'];
        $cad->district = $request['district'];
        $cad->complement = $request['complement'];

        $cad->save();

        return ["result"=>"S","message"=>'Dados Cadastrais Salvos!'];
    }
}
