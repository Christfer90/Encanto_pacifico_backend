<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */

    
    public function authorize()
    {
        return true;
    }

    /**
     * Se obtienen las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email"=>"required|email",
            "password"=>"required",
        ];
    }

    /**
     * Mensaje personalizado para validación
     *
     * @return array
     */
    
    public function messages()
    {
        return [
            'email.required' => 'el campo de correo electrónico es obligatorio',
            'email.email' => 'Por favor, ingrese un email válido',
            'password'=>'required'
        ];
    }
    

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "error"=>$validator->errors(),
                "message"=>"Uno o más campos son obligatorios"
        ], 422));
     }
    

     //end of this class
}