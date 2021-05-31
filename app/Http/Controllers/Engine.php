<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class Engine extends Controller
{
    public function addUrl(Request $request): \Illuminate\Http\RedirectResponse
    {
        $url = $this->validateAndFilterUrl($request->input('url.name'));
        if ($url !== false) {
            DB::table('urls')->upsert(
                [['name' => $url, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]],
                ['name'],
                ['updated_at']
            );
            Session::flash('flash_mess_add_success', 'Страница добавлена');
        } else {
            Session::flash('flash_mess_add_error', 'Некорректный адрес: ' . $request->input('url.name'));
        }
        return redirect()->route('home');
    }

    public function showUrls(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $urls = DB::table('urls')->orderBy('id')->simplePaginate(20);
        return view('urls', ['urls' => $urls]);
    }

    public function showUrl(int $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $url = DB::table('urls')->where('id', '=', $id)->get();
        return view('url', ['url' => $url]);
    }

    public function validateAndFilterUrl(string $url): string | bool
    {
        $scheme = (string) parse_url($url, PHP_URL_SCHEME);
        $host = (string) parse_url($url, PHP_URL_HOST);
        if (($scheme == 'http' || $scheme == 'https') && !empty($host)) {
            return $scheme . '://' . $host;
        }
        return false;
    }
}
