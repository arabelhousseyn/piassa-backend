<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUserRequest extends FormRequest
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
            'user_vehicle_id' => 'required|integer',
            'qt' => 'required|integer',
            'type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_vehicle_id.required' => 'Erreur veuillez réessayer.',
            'user_vehicle_id.integer' => 'Erreur veuillez réessayer.',
            'qt.required' => 'Quantité requis.',
            'qt.integer' => 'Quantité doit être un nombre.',
            'type.required' => 'Erreur veuillez réessayer.',
        ];
    }
}
