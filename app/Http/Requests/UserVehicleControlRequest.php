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
            'user_vehicle_id' => 'required|unique:user_vehicle_controls|exists:user_vehicles,id',
            'technical_control' => 'required|date|date_format:Y-m-d',
            'assurance' => 'required|date|date_format:Y-m-d',
            'emptying' => 'required'
        ];
    }
}
