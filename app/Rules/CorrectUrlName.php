<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectUrlName implements Rule
{
    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $scheme = (string) parse_url($value, PHP_URL_SCHEME);
        $host = (string) parse_url($value, PHP_URL_HOST);
        if (($scheme == 'http' || $scheme == 'https') && !empty($host) && mb_strlen($value) < 256) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Некорректный URL';
    }
}
