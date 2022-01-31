<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required',
            'phone' => 'required|digits:10|unique:users',
            'province_id' => 'required',
            'gender' => 'required|in:M,F',
            'password' => 'required|confirmed|min:8',
            'location' => 'required',
            'has_role' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Nom complet requis.',
            'phone.required' => 'Téléphone requis.',
            'phone.unique' => 'Téléphone déjà utilisé.',
            'phone.digits' => 'Le numéro de téléphone doit être composé de 10 chiffres.',
            'province_id.required' => 'Willaya requis.',
            'gender.required' => 'Le sexe requis.',
            'gender.in' => 'Erreur Veuillez réessayer.',
            'password.required' => 'Mote de passe requis.',
            'password.confirmed' => 'Le mot de passe ne correspond pas.',
            'password.min' => 'L mot de passe doit avoir une longueur minimale de 8',
            'location.required' => 'Emplacement GPS requis.',
            'has_role.required' => 'Rôle requis.'
        ];
    }
}
