<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginSellerRequest extends FormRequest
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
            'phone' => 'required|digits:10',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Téléphone requis.',
            'phone.digits' => 'Le numéro de téléphone doit être composé de 10 chiffres.',
            'password.required' => 'Mote de passe requis.',
            'password.min' => 'L mot de passe doit avoir une longueur minimale de 8',
        ];
    }
}
