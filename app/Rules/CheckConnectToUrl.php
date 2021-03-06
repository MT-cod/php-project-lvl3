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
        $urlName = DB::table('urls')->where('id', $value)->value('name');
        try {
            Http::get($urlName);
        } catch (\Exception $e) {
            $this->curlError = $e->getMessage();
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
