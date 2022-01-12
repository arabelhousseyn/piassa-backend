<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
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
            'sign_id' => 'required',
            'model' => 'required',
            'year' => 'required',
            'motorization' => 'required',
            'chassis_number' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'sign_id.required' => 'Étiquettes de véhicule requis.',
            'model.required' => 'Modèle requis.',
            'year.required' => 'Année requis.',
            'motorization.required' => 'Motorisation requis.',
            'chassis_number.required' => 'N° châssis requis.',
        ];
    }
}
