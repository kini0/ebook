<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['nullable', 'exists:categories,id'],
            'title'              => ['required', 'string', 'max:200'],
            'subtitle'           => ['nullable', 'string', 'max:200'],
            'author'             => ['required', 'string', 'max:180'],
            'short_description'  => ['nullable', 'string', 'max:500'],
            'description'        => ['required', 'string'],
            'isbn'               => ['nullable', 'string', 'max:30'],
            'language'           => ['nullable', 'string', 'max:10'],
            'pages'              => ['nullable', 'integer', 'min:1', 'max:5000'],
            'price_cents'        => ['required', 'integer', 'min:0'],
            'compare_at_cents'   => ['nullable', 'integer', 'min:0'],
            'is_featured'        => ['nullable', 'boolean'],
            'is_published'       => ['nullable', 'boolean'],
            'cover'              => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file'               => ['required', 'file', 'mimes:pdf,epub,mobi', 'max:51200'],
        ];
    }
}
