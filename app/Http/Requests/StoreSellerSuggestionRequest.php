<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSellerSuggestionRequest extends FormRequest
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
            'seller_request_id' => 'required|integer',
            'marks' => 'required',
            'prices' => 'required',
            'available_at' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'seller_request_id.required' => 'Erreur veuillez réessayer.',
            'seller_request_id.integer' => 'Erreur veuillez réessayer.',
            'mark.required' => 'Marque requis.',
            'price.required' => 'Prix requis.',
            'available_at.required' => 'Disponibilité requis.',
        ];
    }
}
