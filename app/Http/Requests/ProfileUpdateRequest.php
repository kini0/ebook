<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => ['required', 'string', 'email', 'max:180',
                              Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone'      => ['nullable', 'string', 'max:32'],
            'country'    => ['nullable', 'string', 'max:80'],
            'city'       => ['nullable', 'string', 'max:120'],
            'address'    => ['nullable', 'string', 'max:255'],
        ];
    }
}
