<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CorrectUrlName;

class UrlNameValidator extends FormRequest
{
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Некорректный URL',
            'max' => ['string' => 'Некорректный URL']
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ['url.name' => ['bail', new CorrectUrlName()]];
    }
}
