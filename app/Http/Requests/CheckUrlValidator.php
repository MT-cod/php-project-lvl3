<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckConnectToUrl;

class CheckUrlValidator extends FormRequest
{
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Некорректный URL'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ['id' => ['bail', 'required', new CheckConnectToUrl()]];
    }
}
