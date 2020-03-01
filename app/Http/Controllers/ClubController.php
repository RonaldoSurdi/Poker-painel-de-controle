<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests\ClubRequest;
use App\Models\ClubInfo;
use App\Models\City;
use App\Models\Club;
use App\Models\ClubView;
use App\Models\ClubWeekHour;
use App\Models\MessageUser;
use App\Models\UserApp;
use App\Models\UserFollow;
use App\Models\UserLocation;
use App\User;
use Illuminate\Http\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ClubController extends Controller
{

    public function MontarListaClubs($lista,$request){
        $data = collect();
        $qtd = 0;

        //se tem nao tem o id da cidade
        if (!$request->has('city_id')){
            $city_id = 0;
        }else{
            $city_id = $request['city_id'];
        }

        /**** se nao tem latitude ****/
        if (!$request->has('lat')){
            $lat = 0;
            $lng = 0;
            $raio = 0;
        }else{
            $lat = $request['lat'];
            $lng = $request['lng'];
            $raio = $request['raio'];
        }



        /**** montar a lista de retorno ***/
        foreach ($lista as $item) {
            $qtd++;

            $distance = '';
            if ($item->distance) {
                $cad = Club::find($item->id);
                $distance = $item->distance;
                if ($distance>1)
                    $distance = number_format($distance, 3, ',', '.').' Km';
                else{
                    $distance = $distance * 1000;
                    $distance = number_format($distance, 2, ',', '.').' m';
                }

            }else
                $cad = $item;

            //se for a saintec e nao for usuario do tadeu sandro ou daniel não mostra
            if ((isset($cad)) and ($cad))
                if ($cad->id==350) //cadastro da saintec como clube
                    /*if (($request['user_id']>4) //1-saintec 2-sandro 3-tiepo 4- tadeu
                        and ($request['user_id']<>120) //daniel
                    )*/
                    if ($request['user_id']>3) $cad = null;

            //se tem cadastro de clube mostra
            if ((isset($cad)) and ($cad)){
                $site ='';
                $phone ='';
                if ($cad->site) $site = $cad->site;
                if ($cad->phone) $phone = $cad->phone;

                /** Verifica se tem licença */
                $lic = 'N';
                if ( $cad->premium() )
                    $lic = 'S';


                /*** guarda o city_id **/
                if ($city_id == 0)
                    $city_id = $cad->city_id;

                /*** guarda a latitude **/
                if ($lat==0) {
                    $lat = $cad->lat;
                    $lng = $cad->lng;
                }
                $cityName = $cad->city();
                    /** Monta o retorno */
                /*    $data->push([
                        'id' => $cad->id
                        , 'name' => $cad->name
                        , 'address' => $cad->address . ', ' . $cad->number . ', ' . FormatarCEP($cad->zipcode) . ', ' . $cad->district . ' - ' .
                            $cad->city()
                        , 'phone' => $phone
                        , 'site' => $site
                        , 'premium' => $lic
                        , 'lat' => $cad->lat
                        , 'lng' => $cad->lng
                        , 'distance' => $distance
                    ]);*/
                    $data->push([
                        'id' => $cad->id
                        , 'name' => $cad->name
                        , 'address' => $cad->address . ', ' . $cad->number . ', ' . FormatarCEP($cad->zipcode) . ', ' . $cad->district . ' - ' . $cityName
                        , 'phone' => $phone
                        , 'site' => $site
                        , 'premium' => $lic
                        , 'lat' => $cad->lat
                        , 'lng' => $cad->lng
                        , 'distance' => $distance
                        , 'city' => $cityName
                    ]);
            }else{
                $log = json_encode($item);
                Log::info('getClubs: não achou club '.$log);
            }
        }

        /*** se ainda não tem latitude , pega pela cidade ***/
        if ( ($city_id>0) and ($lat==0) ) {
            $cidade = City::find($city_id);
            $lat = $cidade->lat;
            $lng = $cidade->lng;
        }

        /*** Tem a latitude mas não tem a cidade ***/
        if ( ($city_id==0) and ($lat<>0) ) {
            $cities = DB::select(DB::raw('select id, (6371 *
                acos(
                    cos(radians('.$lat.')) *
                    cos(radians(lat)) *
                    cos(radians('.$lng.') - radians(lng)) +
                    sin(radians('.$lat.')) *
                    sin(radians(lat))
                )) AS distance
                from cities                                               
                group by id,distance
                HAVING distance <= 10'
            ));
            foreach ($lista as $item)
                if ( $city_id==0)
                    $city_id = $item['id'];
        }

        if ($city_id==0) $city_id = null;

        /*** gravar o log do usuario ***/
        $log = new UserLocation();
        $log->user_app_id = $request['user_id'];
        $log->city_id = $city_id;
        $log->lat = $lat;
        $log->lng = $lng;
        $log->radius = $raio;
        $log->qtd = $qtd;
        $log->save();

        return ["result"=>"S","items"=>$data,'lat'=>$lat,'lng'=>$lng];
    }


    public function getClubs(Request $request)
    {
//Log::info('getClubs ini');

        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
            'raio' => 'required',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'lat.required'=>"Informe uma latitude",
                'lng.required'=>"Informe uma longitude",
                'raio.required'=>"Informe o raio, não é o estado americano!",
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
            ]
        );

        /***se tem algum erro de campo***/
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /** pega os dados vindos do app */
        $lat = $request['lat'];
        $lng = $request['lng'];
        $raio = $request['raio'];

        /*** consultar os clubs usando a "Fórmula de Haversine" ***/
        /**  exemplo https://pt.stackoverflow.com/questions/55669/identificar-se-conjunto-de-coordenadas-est%C3%A1-dentro-de-um-raio-em-android **/
        $lista = DB::select(DB::raw('select id, (6371 *
        acos(
            cos(radians('.$lat.')) *
            cos(radians(lat)) *
            cos(radians('.$lng.') - radians(lng)) +
            sin(radians('.$lat.')) *
            sin(radians(lat))
        )) AS distance
        from clubs 
        where (status=1) and (deleted_at is null)
        group by id,distance
        HAVING distance <= '.$raio.'
        order by distance '
        ));

        /*** gravar a ultima latitude do usuario ***/
        $user = UserApp::find($request['user_id']);
        $user->lat = $request['lat'];
        $user->lng = $request['lng'];
        $user->save();

        /** Montar a lista **/
        $data = $this->MontarListaClubs($lista,$request);

//Log::info('getClubs fim');
        /** retorno */
        return $data;
    }

    public function getClubsCity(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'user_id' => 'required|exists:user_app,id',
        ],
            [
                'user_id.required'=>"Informe o usuário logado no app",
                'user_id.exists'=>"Usuário não cadastrado",
                'city_id.required'=>"Cidade não informada",
                'city_id.exists'=>"Cidade não encontrada",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /** Montar a lista **/
        $lista = Club::wherestatus(1)
            ->wherecity_id($request['city_id'])
            ->orderby('name')
            ->get();
        $data = $this->MontarListaClubs($lista,$request);

        /** retorno */
        return $data;
    }

    public function getClubsAllCity(Request $request)
    {
        /** Montar a lista **/
        $lista = Club::wherestatus(1)
            ->orderby('city_id')
            ->get();
        $data = $this->MontarListaClubs($lista,$request);

        /** retorno */
        return $data;
    }

    public function index(){
        return view('club.home');
    }

    public function dados(){
        $cad = Auth::user()->club;
        return view('club.dados',compact('cad'));
    }

    public function setLatLng(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            //'club_id' => 'required|exists:clubs,id',
            'lat' => 'required',
            'lng' => 'required',
        ],
            [
                'club_id.required'=>"Informe o clube logado",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $cad = Club::find( Auth::user()->club_id );
        $cad->lat = $request['lat'];
        $cad->lng = $request['lng'];
        $cad->save();

        return ["result"=>"S","message"=>'Nova localização salva!'];
    }

    public function setContract(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            //'club_id' => 'required|exists:clubs,id',
            'contract' => 'required'
        ],
            [
                'club_id.required'=>"Informe o clube logado",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }
        $cad = Club::find( Auth::user()->club_id );
        $cad->contract = $request['iagree'];
        $cad->save();

        return ["result"=>"S","message"=>'Salvo com sucesso!'];
    }

    public function setTerms(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            //'club_id' => 'required|exists:clubs,id',
            'terms' => 'required'
        ],
            [
                'club_id.required'=>"Informe o clube logado",
                'club_id.exists'=>"Clube não cadastrado",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }
        $cad = Club::find( Auth::user()->club_id );
        $cad->terms = $request['iagree'];
        $cad->save();

        return ["result"=>"S","message"=>'Salvo com sucesso!'];
    }

    public function setLinkLive(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
            'link' => 'required',
        ],
            [
                'club_id.required'=>"Informe o clube logado",
                'club_id.exists'=>"Clube não cadastrado",
                'link.required'=>"Informe o link da transmissão ao vivo",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        $cad = ClubInfo::whereclub_id($request['club_id'])->first();
        if (!$cad){
            $cad = new ClubInfo();
            $cad->club_id = $request['club_id'];
        }
        $cad->link_live = $request['link'];
        $cad->save();

        return ["result"=>"S","message"=>'Link Ao Vivo Salvo!'];
    }


    public function setInfo(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'info_id' => 'required|exists:clubs,id',
            'qtd_table' => 'required|numeric|min:1',
        ],
            [
                'info_id.required'=>"Informe o clube logado",
                'info_id.exists'=>"Clube não cadastrado",
                'qtd_table.required'=>"Informe a qtd de mesas",
                'qtd_table.number'=>"Informe a qtd de mesas",
                'qtd_table.min'=>"Informe a qtd de mesas, pelo menos 1",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        /*****Salvar informações adicionais *****/
        $cad = ClubInfo::whereclub_id($request['info_id'])->first();
        if (!$cad){
            $cad = new ClubInfo();
            $cad->club_id = $request['info_id'];
        }
        $cad->qtd_table = $request['qtd_table'];
        $cad->social_facebook = $request['social_face'];
        $cad->social_instagran = $request['social_insta'];
        $cad->desc = $request['desc'];
        $cad->comfortable = '';
        for($i=1;$i<=14;$i++){
            if ($request->has('ckAmb_'.$i)){
                $cad->comfortable.= 'A'.$i.',';
            }
        }
        $cad->save();

        /***** savar horarios da semana *****/

        for ($i=0;$i<=6;$i++) {
            $week = ClubWeekHour::whereclub_id($request['info_id'])
                ->whereweek_day($i)
                ->first();

            /*** ajustar conforme configurado****/
            if ($request->has('ck_week'.$i)){

                if (!$week) {
                    $week = new ClubWeekHour();
                    $week->club_id = $request['info_id'];
                    $week->week_day = $i;
                }
                $week->hour_open = $request['hour_open'.$i];
                $week->hour_close = $request['hour_close'.$i];
                $week->save();

            }elseif ($week) {
                /*** se esta cadastrado e for removido então deleta ****/
                $week->delete();
            }
        }

        return ["result"=>"S","message"=>'Informações adicionais Salvas!'];
    }

    public function setCad(Request $request){
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
        //$cad->city_id = $request[''];
        $cad->address = $request['address'];
        $cad->number = $request['number'];
        $cad->district = $request['district'];
        $cad->complement = $request['complement'];

        $cad->save();

        return ["result"=>"S","message"=>'Dados Cadastrais Salvos!'];
    }

    function SetLogo(Request $request){
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'logo1' => 'required|file',
        ],
            [
                'logo1.required'=>"Informe um logo do clube",
            ]
        );
        if ($validator->fails()) { /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }

        //dados do arquivo ftp
        $arquivo = $request->file('logo1');
        $nome = $arquivo->getClientOriginalName();
        $ext = $arquivo->getClientOriginalExtension();
        $arqName = Auth::user()->club_id.'/logo.'.$ext;

        /**** salvar novo logo no banco ****/
        $cad = ClubInfo::whereclub_id(Auth::user()->club_id)->first();
        if (!$cad){
            $cad = new ClubInfo();
            $cad->club_id = Auth::user()->club_id;
        }
        $arqOld = $cad->logo_ext;
        $cad->logo_ext = $ext;
        $cad->save();

        /*** apagar o logo antigo ***/
        if ($arqOld<>''){
            $arqOld = Auth::user()->club_id.'/logo.'.$arqOld;
            if ( Storage::disk('logos')->exists($arqOld) )
                Storage::disk('logos')->delete($arqOld);
        }


        /***** Salvar a foto no ftp *****/
        try {
            $file = file_get_contents($arquivo);
        } catch (\Exception $e) {
            return ["result"=>"N","message"=> 'Seu navegador não enviou o logo, tente novamente!'];
            Log::debug($e);
        }
        Storage::disk('logos')->put($arqName , $file );

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Logomarca salva!'
            ,'logo'=>Auth::user()->club->logo()
        ];
    }

    public function DelLogo(){

        $cad = ClubInfo::whereclub_id(Auth::user()->club_id)->first();
        if (!$cad)
            return ["result"=>"N","message"=>'Você ainda não tem logomarca!'];

        //
        if (!$cad->logo_ext)
            return ["result"=>"N","message"=>'Você ainda não tem logomarca!'];

        //
        $arqName = Auth::user()->club_id.'/logo.'.$cad->logo_ext;

        //exclui o arquivo
        Storage::disk('photos')->delete( $arqName );

        //apaga no banco
        $cad->logo_ext ='';
        $cad->save();

        Auditoria('DELETE','LOGO',Auth::user()->club_id);

        /*** ok ***/
        return ["result"=>"S"
            ,"message"=>'Logomarca Excluida!'
            ,'logo'=>Auth::user()->club->logo()
        ];
    }

    public function getClub(Request $request)
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

        /*** registrar a visita ***/
        $view = new ClubView();
        $view->user_app_id = $request['user_id'];
        $view->club_id = $request['club_id'];
        $view->save();


        /** carregar club */
        $cad = Club::find($request['club_id']);

        /** Verifica se tem licença */
        if ($cad->premium())
            $lic = 'S';
        else
            $lic = 'N';

        /** Verifica se curtiu */
        $flw = UserFollow::whereuser_app_id($request['user_id'])->whereclub_id($request['club_id'])->first();
        if ($flw)
            $follow = 'S';
        else
            $follow = 'N';

        /***** informações adicionais ****/
        $mesas  ='1';
        $desc   ='';
        $live   ='';
        $face   ='';
        $insta   ='';
        if ($cad->info){
            $mesas  = $cad->info->qtd_table;
            $desc   = $cad->info->desc;
            $live   = $cad->info->link_live;
            $face   = $cad->info->social_facebook;
            $insta  = $cad->info->social_instagran;
        }

        $_diaSemana = array( 'dom','seg','ter','qua','qui','sex','sab');
        $_diaSemanaEx = array( 'domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado');
        $_ListaAmbiente = array('Ar-Condicionado'
        ,'Área para Fumantes'
        ,'Lounge para Jogadores'
        ,'Ranking de Clube'
        ,'Cartão Débito/Crédito'
        ,'Valet Parking'
        ,'Acesso para Deficientes'
        ,'Wi-fi'
        ,'Bar'
        ,'Lanches'
        ,'Refeição'
        ,'Segurança'
        ,'Televisão'
        ,'Estacionamento'
        );

        /*** dias da semana ***/
        $week = array();
        for($i=1;$i<=7;$i++){
            $week[$_diaSemana[$i-1]] = 'Fechado';
            if ($cad->week($i))
                $week[$_diaSemana[$i-1]] = date('H:i', strtotime($cad->week($i)->hour_open))
                    .' as '
                    .date('H:i', strtotime($cad->week($i)->hour_close));
        }

        /***** convenience *****/
        $convenience = collect();
        for($i=1;$i<=14;$i++) {
            if ($cad->AmbChecked($i)<>'') {
                //$convenience->push(['description' => '']);
                if (($i < 0) || ($i > 13)) $convenience->push(['description' => '']);
                else $convenience->push(['description' => $_ListaAmbiente[$i-1]]);
            }
        }

        /***** fotos *****/
        $photos = collect();
        if ($cad->gallery()->photos){
            $pos=0;
            foreach ($cad->gallery()->photos as $photo) {
                $pos++;
                $photoinf = '';
                if ($photo->info) $photoinf = $photo->info;
                $photos->push([
                    'pos' => $pos,
                    'link' => $photo->img(),
                    'description' => $photoinf,
                ]);
            }
        }

        $qtd_msg = MessageUser::select('message_users.*')
            ->join('messages','messages.id','message_id')
            ->whereuser_app_id($request['user_id'])
            ->whereclub_id($request['club_id'])
            ->where('message_users.status','<',3)
            ->orderby('message_users.id','desc')
            ->count();

        /***** Monta os dados ***/
        $_CEP = LIMPANUMERO($cad->zipcode);
        $_CEP = substr($_CEP,0,5).'-'.substr($_CEP,5,3);

        $data = array();
        $data['result'] = 'S';
        $data['id'] = $cad->id;
        $data['name'] = $cad->name;
        $data['address'] = $cad->address.', '.$cad->number.', '.$_CEP.', '.$cad->district.' - '.$cad->city();
        $data['phone'] = !empty($cad->phone) ? $cad->phone : '';
        $data['site'] = !empty($cad->site) ? $cad->site : '';
        $data['premium'] = $lic;
        $data['follow'] = $follow;
        $data['lat'] = $cad->lat;
        $data['lng'] = $cad->lng;
        $data['tables'] = !empty($mesas) ? $mesas : 1;
        $data['description'] = !empty($desc) ? $desc : '';
        $data['live'] = !empty($live) ? $live : '';
        $data['facebook'] = !empty($face) ? $face : '';
        $data['instagran'] = !empty($insta) ? $insta : '';
        $data['logo'] = $cad->logo();
        $data['week'] = $week;
        $data['convenience'] = $convenience;
        $data['photos'] = $photos;
        $data['qtd_msg'] = $qtd_msg;


        /** retorno */
        return $data;
    }


    public function getClubsAutoComplete(Request $request)
    {
        /***valição dos campos ***/
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_app,id',
            'search' => 'required',
        ],
            [
                'user_id.required' => "Informe o usuário logado no app",
                'user_id.exists' => "Usuário não cadastrado",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message) {
                return ["result" => "N", "message" => $message];
            }
        }

        $busca = $request['search'];

        $lista = Club::wherestatus(1)
            ->Where(function ($query) use ($busca) {
                    $query->where('name', 'like', '%'.$busca.'%')
                        ->orWhere('site', 'like', '%'.$busca.'%')
                    ;
                })
            ->get();

        /** Montar a lista **/
        $data = $this->MontarListaClubs($lista,$request);

        /** retorno */
        return $data;
    }

    public function getNearestClubs(Request $request){
        $request['raio'] ='500';
        return $this->getClubs($request);
    }


    public function ranking(){
        return view('club.rank');
    }

    public function torneios(){
        return view('club.torn');
    }

    public function newcad(ClubRequest $request){
        //se existe
        if ($request['cad_id']>0) {
            $cad = Club::find($request['cad_id']);
            if ($cad->id<>$request['cad_id']){
                Session::put('Serro', 'Cadastro não encontrado');
                return redirect('/club');
            }
            $action = 'EDIT';
        }else{
            $cad = new Club();
            $cad->status = 1;
            $action ='NEW';
        }

        //dados
        $cad->name = $request['name'];
        $cad->doc1 = LIMPANUMERO($request['doc1']);
        $cad->responsible = $request['responsible'];
        $cad->phone = LIMPANUMERO($request['phone']);
        $cad->whats = LIMPANUMERO($request['whats']);

        $cad->site = $request['site'];
        $cad->zipcode = LIMPANUMERO($request['zipcode']);
        $cad->city_id = $request['city'];
        $cad->address = $request['address'];
        $cad->number = $request['number'];
        $cad->district = $request['district'];
        $cad->complement = $request['complement'];

        $cad->email = $request['cad_user'];
        $cad->cad = 1;
        $cad->save();

        Auditoria($action,'CLUB',$cad->id,'Se cadstrou pelo site');


        $user = User::whereclub_id($cad->id)->first();
        if (!$user){ //Ainda não tem cadastro
            $user = new User();
        }
        /*** salva os novos dados ***/
        $user->club_id = $cad->id;
        $user->email = $request['lic_user'];
        $user->password = Hash::make( $request['lic_pass'] );
        $user->save();


        if ($request['cad_id']==0) {
            //Loga o usuario
            Auth::loginUsingId($user->id);

            //Já libera a licença por 30 dias para ele
            return redirect()->route('club.lic.free');
        }

        //
        Session::put('Sok', 'Cadastro Salvo');
        return redirect()->route('club.dados');
    }
}
