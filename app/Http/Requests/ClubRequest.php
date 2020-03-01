<?php

namespace App\Http\Requests;


use App\Models\Club;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ClubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('validaCnpj', function($attribute, $value, $parameters){
            $campos = parent::all();
            return VerificaCpfCgc($value);
        });

        Validator::extend('uniquedoc', function($attribute, $value, $parameters){
            $campos = parent::all();
            //procura se ja existe
            $cad = Club::wheredoc1( LIMPANUMERO($value) )->where('id','<>',$campos['cad_id'])->first();
            if ($cad)
                return false; //pq ja existe alguem com esse CPF/CNPJ

            //se passou marca ok
            return true;
        });

        return [
            'name'      => 'required|min:3',
            'doc1'      => 'nullable|validaCnpj|uniquedoc',
            'site'      => 'nullable|min:5',
            'phone'     => 'nullable|min:10',
            'whats'     => 'nullable|min:10',
            'zipcode'   => 'nullable|min:8',
            'city'      => 'required|exists:cities,id',
            'address'   => 'required|min:3',
            'district'  => 'required',
            'lic_user'     => 'nullable|email|unique:users,email',
            'lic_pass' => 'required|string|min:3|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Informe nome clube',
            'name.min' => 'Informe nome completo',
            'doc1.valida_cnpj' => 'Informe CNPJ válido',
            'doc1.uniquedoc' => 'CNPJ já foi cadastrado',
            'site.email' => 'Informe um site válido',
            'phone.min' => 'Informe o telefone completo',
            'whats.min' => 'Informe o nº de WhatsZapp completo',
            'zipcode.min' => 'Informe um CEP válido',
            'city.min' => 'Informe uma cidade',
            'city.exists' => 'Cidade não encontrada no IBGE',
            'address.min' => 'Informe o Endereço completo',
            'address.required' => 'Informe o Endereço completo',
            'district.required' => 'Informe o Bairro',

            'lic_user.required' => 'Informe seu e-mail de acesso',
            'lic_user.email' => 'Informe um e-mail válido',
            'lic_user.unique' => 'E-mail já esta sendo usado por outro usuário',
            'lic_pass.required' => 'Informe uma senha',
            'lic_pass.min' => 'Informe uma senha maior q 3 letras',
            'lic_pass.confirmed' => 'Confirmação da senha não confere',
        ];
    }
}
