<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'billing_name'    => ['required', 'string', 'max:180'],
            'billing_email'   => ['required', 'string', 'email', 'max:180'],
            'billing_phone'   => ['nullable', 'string', 'max:32'],
            'billing_country' => ['nullable', 'string', 'max:80'],
            'billing_city'    => ['nullable', 'string', 'max:120'],
            'billing_address' => ['nullable', 'string', 'max:255'],

            'payment_method'  => ['required', 'in:card,mobile_money,bank_transfer'],
            'mobile_operator' => ['required_if:payment_method,mobile_money', 'in:orange,mtn,wave'],
            'mobile_phone'    => ['required_if:payment_method,mobile_money', 'string', 'max:32'],

            'terms'           => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'billing_name.required'   => 'Le nom est obligatoire.',
            'billing_email.required'  => 'L\'adresse e-mail est obligatoire.',
            'payment_method.required' => 'Choisissez une méthode de paiement.',
            'mobile_operator.required_if' => 'Sélectionnez un opérateur Mobile Money.',
            'mobile_phone.required_if'    => 'Indiquez votre numéro Mobile Money.',
            'terms.accepted'          => 'Vous devez accepter les conditions de vente.',
        ];
    }

    /**
     * @return array{operator?:string, phone?:string}
     */
    public function paymentPayload(): array
    {
        if ($this->input('payment_method') === 'mobile_money') {
            return [
                'operator' => $this->input('mobile_operator'),
                'phone'    => $this->input('mobile_phone'),
            ];
        }
        return [];
    }
}
