<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Urlname;

class UrlValidator extends FormRequest
{
    public function messages(): array
    {
        return [
            'required' => 'Некорректный URL',
            'max' => ['string' => 'Некорректный URL']
        ];
    }

    public function rules(): array
    {
        return ['url.name' => ['bail', 'required', 'max:255', new Urlname()]];
    }
}
