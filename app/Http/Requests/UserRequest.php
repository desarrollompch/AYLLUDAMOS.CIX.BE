<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'last_name' => 'required',
            'admin' => '',
            'state' => 'required',
            'persona_id' => '',
            'rol_id' => ''
        ];


        if ($method == 'POST') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required';

        } else if ($method == 'PUT') {
            $rules['email'] = 'required|email|unique:users,email,' . $this->user->id;
            $rules['password'] = '';
        }

        return $rules;
    }
}
