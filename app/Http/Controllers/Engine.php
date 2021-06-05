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
        $url = DB::table('urls')->where('id', $id)->first();
        $dataOfCheck = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('url', ['url' => $url, 'dataOfCheck' => $dataOfCheck, 'id' => $id]);
    }

    public function checkUrl(Request $request): \Illuminate\Http\RedirectResponse
    {
        $url_id = $request->input('id');
        DB::table('url_checks')->insert(
            [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'url_id' => $url_id,
                'status_code' => 200,
                'h1' => 'bla',
                'keywords' => 'bla-bla',
                'description' => 'bla-bla-bla'
            ]
        );
        DB::table('urls')
            ->where('id', $url_id)
            ->update(['updated_at' => Carbon::now()]);
        Session::flash('flash_mess_check_success', 'Проверка выполнена');
        return redirect()->route('showUrl', ['id' => $url_id]);
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

