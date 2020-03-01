<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TournamentRequest extends FormRequest
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

        return [
            'name' => 'required|string|max:255',
            'date_type' => 'required',
            'desc' => 'required|string|min:3|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Informe um nome',
            'date_type.required' => 'Informe o tipo de data',
            'desc.required' => 'Informe uma descricao',

        ];
    }
}
