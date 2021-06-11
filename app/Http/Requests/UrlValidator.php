<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlValidator extends FormRequest
{
    public function messages(): array
    {
        return [
            'required' => 'Некорректный URL',
            'max' => ['string' => 'Некорректный URL'],
            'active_url' => 'Некорректный URL'
        ];
    }

    /*public function authorize(): bool
    {
        return true;
    }*/

    public function rules(): array
    {
        return ['url.name' => 'bail|required|max:255|active_url'];
    }
}
