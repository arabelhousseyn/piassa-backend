<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserVehicleControlRequest extends FormRequest
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
            'technical_control' => 'date',
            'assurance' => 'date',
            'emptying' => 'date'
        ];
    }

    public function messages()
    {
        return [
            'technical_control.date' => 'Contrôle technique doit être date.',
            'assurance.date' => 'Assurance doit être date.',
            'emptying.date' => 'Vidange doit être date.'
        ];
    }
}
