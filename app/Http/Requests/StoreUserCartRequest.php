<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserCartRequest extends FormRequest
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
            'seller_suggestion_id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'seller_suggestion_id.required' => 'Erreur veuillez réessayer.',
            'seller_suggestion_id.integer' => 'Erreur veuillez réessayer.'
        ];
    }
}
