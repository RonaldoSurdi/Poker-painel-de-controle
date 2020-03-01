<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class MessageRequest extends FormRequest
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
        Validator::extend('validaText', function($attribute, $value, $parameters){
            $campos = parent::all();
            if ($campos['msg_type']=='1'){
                return $value<>'';
            }else
                return true;
        });
        Validator::extend('validaImg', function($attribute, $value, $parameters){
            $campos = parent::all();
            if ($campos['msg_type']=='2'){
                return $value<>'';
            }else
                return true;
        });

        return [
            'title' => 'required|string|max:50',
            'user_type' => 'required',
            'msg_type' => 'required',
            'desc' => 'valida_text',
            'img1' => 'valida_img',
            'date_day' => 'required',
            'date_hour' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Informe um titulo',
            'user_type.required' => 'Selecione o tipo usuário',
            'msg_type.required' => 'Selecione o tipo de Mensagem',
            'desc.valida_text' => 'Informe o texto da mensagem',
            'img1.valida_img' => 'Selecione uma imagem para a mensagem',
            'date_send.required' => 'Informe a data de envio',
        ];
    }
}
