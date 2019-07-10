<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaRequest extends FormRequest
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
        $method = $this->method();

        $rules = [
            'ape_paterno'=>'required',
            'ape_materno'=>'required',
            'nombres'=>'required',
            'telefono'=>'',
            'direccion'=>'',
            'state'=>'required',
            'tipo_persona_id'=>'required',
            'nivel_ciudadano_id'=>'required',
            'urbanizacion_id'=>'required'
        ];

        if ($method == 'POST') {
            $rules['mail'] = 'email|unique:persona,mail';
            $rules['dni'] = 'required|min:8|max:10|unique:persona,dni';
            $rules['telefono'] = 'unique:persona,telefono';

        } else if ($method == 'PUT') {
            $rules['mail'] = 'email|unique:persona,mail,'. $this->persona->id;
            $rules['dni'] = 'required|min:8|max:10|unique:persona,dni,'. $this->persona->id;
            $rules['telefono'] = 'unique:persona,telefono,'. $this->persona->id;
        }

        return $rules;
    }
}
