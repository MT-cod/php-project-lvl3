<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckConnectToUrl implements Rule
{
    public string $curlError;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $url = (array) DB::table('urls')->where('id', $value)->first();
        try {
            Http::get($url['name']);
        } catch (\Exception $e) {
            $this->curlError = $e->getMessage() . ' for ' . $url['name'];
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->curlError;
    }
}
