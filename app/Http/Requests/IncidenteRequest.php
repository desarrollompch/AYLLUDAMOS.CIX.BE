<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncidenteRequest extends FormRequest
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
            'fecha'=>'required',
            'descripcion' => '',
            'persona_id_validador' => '',
            'latitud' => 'required',
            'longitud' => 'required',
            'urbanizacion_id' => 'required',
            'persona_id' => 'required',
            'estado_incidente_id' => 'required',
            'tipo_incidente_id' => 'required',
            'imagen' => '',
            'direccion' => 'required'
        ];
    }
}
