<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrbanizacionRequest extends FormRequest
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
            'latitude'=>'required',
            'longitude'=>'required',
            'coordenadas'=>'required',
            'min_latitude'=>'',
            'max_latitude'=>'',
            'max_longitude'=>'',
            'min_longitude'=>'',
            'descripcion'=>'required',
            'territorio_vecinal_id'=>'required'
        ];
    }
}
