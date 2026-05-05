<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'            => ['required', 'string', 'max:80'],
            'last_name'             => ['required', 'string', 'max:80'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:32'],
            'country'               => ['nullable', 'string', 'max:80'],
            'city'                  => ['nullable', 'string', 'max:120'],
            'password'              => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms'                 => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'    => 'Le prénom est obligatoire.',
            'last_name.required'     => 'Le nom est obligatoire.',
            'email.unique'           => 'Cette adresse e-mail est déjà utilisée.',
            'password.confirmed'     => 'La confirmation du mot de passe ne correspond pas.',
            'terms.accepted'         => 'Vous devez accepter les conditions d\'utilisation.',
        ];
    }
}
