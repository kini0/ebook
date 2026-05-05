<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'string', 'email', 'max:180'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Votre nom est obligatoire.',
            'email.required'   => 'Votre adresse e-mail est obligatoire.',
            'subject.required' => 'Veuillez préciser un sujet.',
            'message.required' => 'Le message est obligatoire.',
            'message.min'      => 'Votre message est trop court.',
        ];
    }
}
