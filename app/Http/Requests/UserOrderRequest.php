<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserOrderRequest extends FormRequest
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
            'user_cart_id' => 'required|integer',
            'type_delivery' => 'required',
            'amount' => 'required',
            'location' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'user_cart_id.required' => 'Erreur veuillez réessayer.',
            'user_cart_id.integer' => 'Erreur veuillez réessayer.',
            'type_delivery.required' => 'Type de livraison requis.',
            'location.required' => 'Emplacement requis.',
            'amount.required' => 'montant requis.',
            'location.string' => 'Emplacement doit être validé.',
        ];
    }
}
