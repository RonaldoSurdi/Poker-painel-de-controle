<?php

function LIMPANUMERO ($nro)
    {
        $aux ='';
        for ($i=0;$i <= strlen($nro);$i++)
        {
            if ((substr($nro,$i,1)=='0')or(substr($nro,$i,1)=='1')or
                (substr($nro,$i,1)=='2')or(substr($nro,$i,1)=='3')or(substr($nro,$i,1)=='4')or
                (substr($nro,$i,1)=='5')or(substr($nro,$i,1)=='6')or(substr($nro,$i,1)=='7')or
                (substr($nro,$i,1)=='8')or(substr($nro,$i,1)=='9'))
            {
                $aux.=substr($nro,$i,1);
            }
        }
        return $aux;
    }

///// no lugar da função mod que retorna o resto da divisao usamos o %, ex: 6 mod 5 fica 6 % 5;
Function VerificaCpfCgc($pP1)
{
    $pP1 = TRIM( ( LIMPANUMERO( $pP1 ) ));
    if (strlen($pP1)>0)
    {
        $DIGITO = 0;
        $MULT = '543298765432';
        ///Se for CNPJ
        if (strlen($pP1)==14)
        {
            $DIGITOS = substr($pP1,12,2); /// digitos informados
            $MULT = '543298765432';
            $CONTROLE = '';
            ///Loop de verificação
            $J=1;
            For ($J;$J<=2;$J++)
            {
                $SOMA = 0;
                $I=1;
                For ($I;$I<=12;$I++)
                {
                    $SOMA = $SOMA + (substr($pP1,$I-1,1)*substr($MULT,$I-1,1));
                }
                if ($J==2)
                {
                    $SOMA = $SOMA + (2*$DIGITO);
                }
                $DIGITO = ($SOMA*10) % 11;
                If ($DIGITO==10)
                {
                    $DIGITO = 0;
                }
                $CONTROLE = $CONTROLE.$DIGITO;
                $MULT= '654329876543';
            } /// fim for J
            ////compara os dígitos calculados(CONTROLE) com os dígitos informados (DIGITOS)
            if ($CONTROLE<>$DIGITOS)
            {
                return FALSE;
            }else
            {
                return TRUE;
            }
        }else //// FIM Se FOR CNPJ
            if (strlen($pP1)==11) /// se FOR CPF
            {
                $DIGITOS = substr($pP1,9,2); /// digitos informados
                $MULT = '100908070605040302';
                $CONTROLE = '';
                ///Loop de verificação
                $J=1;
                For ($J;$J<=2;$J++)
                {
                    $SOMA = 0;
                    //
                    $K=0;
                    //
                    $I=1;
                    //
                    For ($I;$I<=9;$I++)
                    {
                        if ($I == 1)
                        {
                            $K=1;
                        }else
                        {
                            $K=$K+2;
                        }
                        $SOMA = $SOMA + (substr($pP1,$I-1,1)*substr($MULT,$K-1,2));
                    }
                    //
                    if ($J==2)
                    {
                        $SOMA = $SOMA + (2*$DIGITO);
                    }
                    //
                    $DIGITO = ($SOMA*10) % 11;
                    //
                    If ($DIGITO==10)
                    {
                        $DIGITO = 0;
                    }
                    //
                    $CONTROLE = $CONTROLE.$DIGITO;
                    $MULT= '111009080706050403';
                } /// fim for J
                ////compara os dígitos calculados(CONTROLE) com os dígitos informados (DIGITOS)
                if ($CONTROLE<>$DIGITOS)
                {
                    return False;
                }else
                {
                    return True;
                }

            }else //// FIM Se FOR CPF
            {
                return False;
            }
    }else // se a variavel informada for vazia
    {
        return False;;
    }

}

function FormataCpfCnpj($pP1)
{
    $result = $pP1;
    $pP1 = LIMPANUMERO($pP1);
    if (strlen($pP1)==14)
    {
        $result=substr($pP1,0,2).'.'.
            substr($pP1,2,3).'.'.
            substr($pP1,5,3).'/'.
            substr($pP1,8,4).'-'.
            substr($pP1,12,2);
    }elseif (strlen($pP1)==11)
    {
        $result=substr($pP1,0,3).'.'.
            substr($pP1,3,3).'.'.
            substr($pP1,6,3).'-'.
            substr($pP1,9,2);
    }
    return $result;
}



if (!function_exists('helpBlock')) {
    /**
     * Retorna mensagem de ajuda para erros de validação
     *
     * @param Illuminate\Support\ViewErrorBag $errors
     * @param string $key
     * @return string
     */
    function helpBlock($errors, $key)
    {
        if ($errors->has($key)) {
            return "<span class='help-block'><strong>{$errors->first($key)}</strong></span>";
        }

        return null;
    }
}

/**
 * Retorna classe de erro de validação para Views
 *
 * @param Illuminate\Support\ViewErrorBag $errors
 * @param string $key
 * @return string
 */
function hasErrorClass($errors, $key, $class = 'has-error', $dd = false)
{
    if ($dd) {
        if (!$errors->has($key)) dd($errors);
    }
    return $errors->has($key) ? ' ' . $class : '';
}


/**
 * Retorna os estados UF em Options
 *
 */
function CarregarEstados($default)
{
    $lista = DB::table('cidades')
        ->select(DB::raw('uf'))
        ->where('uf','<>','EX')
        ->groupBy('uf')
        ->orderBy('uf')
        ->get();

    $retorno='';
    foreach ($lista as $cad ) {
        $retorno.= "<option value='".$cad->uf."'";
        if ($default==$cad->uf) {$retorno.= 'selected';};

        $retorno.=">".$cad->uf."</option>";
    }

    return $retorno;
}


/**
 * Retorna os estados UF em Options
 *
 */
function CarregarCidades($uf,$default)
{
    $lista = DB::table('cidades')
        ->whereuf($uf)
        ->orderBy('cidade')
        ->get();

    $retorno='<option value=""></option>';
    foreach ($lista as $cad ) {
        $retorno.= "<option value='".$cad->cidade."'";
        if ($default==$cad->cidade) {$retorno.= 'selected';};
        $retorno.= ">".$cad->cidade."</option>";
    }

    return $retorno;
}


/**
 * Retorna os estados UF em Options
 *
 */
function So1Nome($nome)
{
    $pos = strpos($nome,' ');
    if ($pos>0) $nome=substr($nome,0,$pos);
    return $nome;
}



/**
 * Retorna os estados UF em Options
 *
 */
function Auditoria($acao,$model,$id,$info='')
{
    if (Auth::check()) {
        $user_id = Auth::user()->id;
    }else {
        $user_id = null;
    }

    DB::table('audits')->insert(
        ['user_id' => $user_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'action' => $acao,
            'model' => $model,
            'reg_id' => $id,
            'info' => $info
        ]
    );
}



function FormatarCEP($CEP){
    $CEP = LIMPANUMERO($CEP);
    $CEP = substr($CEP,0,5).'-'.substr($CEP,5,3);
    return $CEP;
}


function TratarEndereco($rua){
    if (!str_contains($rua,' - de '))
        return $rua;

    $ini = 0;
    $fim = strpos($rua,' - de ');
    $msg = substr($rua,$ini,$fim);

    return $msg;
}

function mycheckbox($name,$label,$checked=''){

    if ($checked=='checked'){
        $checked = 'checked="checked"';
    }
    return '<div class="checkbox">
                <label>
                    <input type="checkbox" class="control-primary" '.$checked.' id="'.$name.'" name="'.$name.'">
                    '.$label.'
                </label>
            </div>';
}

function myCheckboxLine($name,$label,$checked=''){

    if ($checked=='checked'){
        $checked = 'checked="checked"';
    }
    return '<label class="checkbox-inline">
                    <input type="checkbox" class="control-primary" '.$checked.' id="'.$name.'" name="'.$name.'">
                    '.$label.'
            </label>';
}

function diaSemana($i){
    $cad = array( 'dom','seg','ter','qua','qui','sex','sab','');
    return $cad[$i];
}

function diaSemanaEx($i){
    $cad = array( 'domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado','');
    return $cad[$i];
}

function diaSemanaPlural($i){
    $cad = array( 'domingos','segundas-feiras','terças-feiras','quartas-feiras','quintas-feiras','sextas-feiras','sábados','');
    return $cad[$i];
}

function mesSemanaEx($i){
    $cad = array('Janeiro1///////
    pç','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    re55850;turn $cad[$i];
}

function ListaAmbiente($pos){
    if ( ($pos<=0) or ($pos>14) ) return '';

    $cad = array(
        'Ar-Condicionado',
        'Área para Fumantes',
        'Lounge para Jogadores',
        'Ranking de Clube',
        'Cartão Débito/Crédito',
        'Valet Parking',
        'Acesso para Deficientes',
        'Wi-fi',
        'Bar',
        'Lanches',
        'Refeição',
        'Segurança',
        'Televisão',
        'Estacionamento',
        ''
    );
    return $cad[$pos-1];
}

function TratarNull($var,$value=''){
    if (!$var)
        return $value;
    return $var;
}

/**** verificar se esse cadastro é dele ***/
function CadastroDoLogado($cad){
    if ($cad->club_id <> Auth::user()->club_id){
        Session::flash('Saviso', 'Não encontramos esse cadastro em seu clube!');
        return false;
    }else
        return true;
}

function UrlInterno(){
    if (env('APP_ENV','local')=='local')
        return "http://localhost:8000";
    else
        return "https://painel.pokerclubsapp.com.br";
}

function sanitizeString($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    //$str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '', $str);
    $str = preg_replace('/[^a-z0-9]/i', ' ', $str);
    $str = preg_replace('/_+/', ' ', $str);
    return $str;
}
function strCharFind($needle,$haystack)
{
    $return = FALSE;
    $arr = str_split($haystack, 1);
    foreach ($arr as $value) {
        if ($value == strtolower($needle) || $value == strtoupper($needle)) {
            $return = TRUE;
        }
    }
    return $return;
}
function payMethodRes($code) {
    if ($code == 101) { $return = 'Cartão de crédito Visa.'; }
    elseif ($code == 102) { $return = 'Cartão de crédito MasterCard.'; }
    elseif ($code == 103) { $return = 'Cartão de crédito American Express.'; }
    elseif ($code == 104) { $return = 'Cartão de crédito Diners.'; }
    elseif ($code == 105) { $return = 'Cartão de crédito Hipercard.'; }
    elseif ($code == 106) { $return = 'Cartão de crédito Aura.'; }
    elseif ($code == 107) { $return = 'Cartão de crédito Elo.'; }
    elseif ($code == 108) { $return = 'Cartão de crédito PLENOCard.'; }
    elseif ($code == 109) { $return = 'Cartão de crédito PersonalCard.'; }
    elseif ($code == 110) { $return = 'Cartão de crédito JCB.'; }
    elseif ($code == 111) { $return = 'Cartão de crédito Discover.'; }
    elseif ($code == 112) { $return = 'Cartão de crédito BrasilCard.'; }
    elseif ($code == 113) { $return = 'Cartão de crédito FORTBRASIL.'; }
    elseif ($code == 114) { $return = 'Cartão de crédito CARDBAN.'; }
    elseif ($code == 115) { $return = 'Cartão de crédito VALECARD.'; }
    elseif ($code == 116) { $return = 'Cartão de crédito Cabal.'; }
    elseif ($code == 117) { $return = 'Cartão de crédito Mais!.'; }
    elseif ($code == 118) { $return = 'Cartão de crédito Avista.'; }
    elseif ($code == 119) { $return = 'Cartão de crédito GRANDCARD.'; }
    elseif ($code == 120) { $return = 'Cartão de crédito Sorocred.'; }
    elseif ($code == 122) { $return = 'Cartão de crédito Up Policard.'; }
    elseif ($code == 123) { $return = 'Cartão de crédito Banese Card.'; }
    elseif ($code == 201) { $return = 'Boleto Bradesco.'; }
    elseif ($code == 202) { $return = 'Boleto Santander.'; }
    elseif ($code == 301) { $return = 'Débito online Bradesco.'; }
    elseif ($code == 302) { $return = 'Débito online Itaú.'; }
    elseif ($code == 303) { $return = 'Débito online Unibanco.'; }
    elseif ($code == 304) { $return = 'Débito online Banco do Brasil.'; }
    elseif ($code == 305) { $return = 'Débito online Banco Real.'; }
    elseif ($code == 306) { $return = 'Débito online Banrisul.'; }
    elseif ($code == 307) { $return = 'Débito online HSBC.'; }
    elseif ($code == 401) { $return = 'Saldo PagSeguro.'; }
    elseif ($code == 501) { $return = 'Oi Paggo. *'; }
    elseif ($code == 701) { $return = 'Depósito em conta - Banco do Brasil'; }
    else { $return = 'Não identificado'; }
    return $return;
}

function numberformt($n) {
    $nx = str_replace(".","",$n);
    $nx = str_replace(",",".",$nx);
    return $nx;
}