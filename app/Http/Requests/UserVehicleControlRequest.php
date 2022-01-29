<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserVehicleControlRequest extends FormRequest
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
            'user_vehicle_id' => 'required|integer|unique:user_vehicle_controls',
            'technical_control' => 'required|date',
            'assurance' => 'required|date',
            'emptying' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'user_vehicle_id.required' => 'Erreur veuillez réessayer.',
            'user_vehicle_id.integer' => 'Erreur veuillez réessayer.',
            'user_vehicle_id.unique' => 'Contrôle technique existe déjà pour ce véhicule.',
            'technical_control.required' => 'Contrôle technique requis.',
            'technical_control.date' => 'Contrôle technique doit être date.',
            'assurance.required' => 'Assurance requis.',
            'assurance.date' => 'Assurance doit être date.',
            'emptying.required' => 'Vidange requis.',
            'emptying.date' => 'Vidange doit être date.'
        ];
    }
}
