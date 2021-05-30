<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Engine extends Controller
{
    public function addUrl(Request $request): \Illuminate\Http\RedirectResponse
    {
        $url = $this->validateUrl($request->input('url.name'));
        if ($url !== false) {
            DB::table('urls')->upsert(
                [['name' => $url, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]],
                ['name'],
                ['updated_at']
            );
        }
        return redirect()->route('home');
    }

    public function validateUrl(string $url): string | bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
