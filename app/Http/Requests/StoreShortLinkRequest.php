<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'URL обязателен для заполнения.',
            'original_url.url' => 'Введите корректный URL.',
            'original_url.max' => 'URL не должен превышать 2048 символов.',
        ];
    }
}
