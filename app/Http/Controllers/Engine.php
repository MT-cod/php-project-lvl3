<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckUrlValidator;
use App\Rules\CheckConnectToUrl;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\UrlValidator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Engine extends Controller
{
    public function create(UrlValidator $request): RedirectResponse
    {
        //Если url был добавлен с параметрами после имени домена, то избавляемся от них
        $url = $this->filterUrl($request->input('url.name'));

        //Проверяем на наличие переданного имени сайта в базе
        if (DB::table('urls')->where('name', '=', $url)->exists()) {
            $id = DB::table('urls')->where('name', $url)->value('id');
            Session::flash('message', 'Страница уже существует');
            return redirect()->route('urls.show', ['id' => $id]);
        }
        //Переданный url новый для базы, добавляем в базу
        DB::table('urls')
            ->insert(['name' => $url, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        Session::flash('message', 'Страница успешно добавлена');
        $id = DB::table('urls')->where('name', $url)->value('id');
        return redirect()->route('urls.show', ['id' => $id]);
    }

    public function store(): Application|View|Factory
    {
        $querry = DB::select('
        select id, name, updated_at, sel2.status as status_code from urls LEFT JOIN
        (select url_id as sel_url_id, status_code as status from url_checks JOIN
        (select max(id) as sel_id, url_id as sel_url_id from url_checks group by url_id) as sel
        on url_checks.id = sel.sel_id
        where url_id = sel.sel_url_id) as sel2
        on urls.id = sel2.sel_url_id
        order by urls.id;
        ');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $urlsColl = new Collection($querry);
        $perPage = 25;
        $currentPageSearchResults = $urlsColl->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $urls = new LengthAwarePaginator($currentPageSearchResults, count($urlsColl), $perPage);
        $urls->withPath('urls');
        return view('urls', ['urls' => $urls]);
    }

    public function show(int $id): Application|View|Factory
    {
        $url = DB::table('urls')->where('id', $id)->first();
        $dataOfCheck = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('url', ['url' => $url, 'dataOfCheck' => $dataOfCheck, 'id' => $id]);
    }

    public function update(CheckUrlValidator $request): RedirectResponse
    {
        $url_id = $request->input('id');
        $url = (array) DB::table('urls')->where('id', $url_id)->first();

        //Повторно проверяем подключение и собираем инфу по тегам и пишем данные по проверке подключения
        try {
            $response = Http::get($url['name']);
        } catch (\Exception $e) {
            //Session::flash('errors', $e->getMessage() . ' for ' . $url['name']);
            return redirect()->route('urls.show', ['id' => $url_id]);
        }
        $tags = $this->getTags($response, $url['name']);

        DB::table('url_checks')->insert(
            [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'url_id' => $url_id,
                'status_code' => $response->status(),
                'h1' => $tags['h1'],
                'keywords' => $tags['keywords'],
                'description' => $tags['description']
            ]
        );
        DB::table('urls')
            ->where('id', $url_id)
            ->update(['updated_at' => Carbon::now()]);
        Session::flash('message', 'Страница успешно проверена');
        return redirect()->route('urls.show', ['id' => $url_id]);
    }

    //Вспомогательные методы////////////////////////////////////////////////////////
    public function filterUrl(mixed $url): string
    {
        $scheme = (string) parse_url($url, PHP_URL_SCHEME);
        $host = (string) parse_url($url, PHP_URL_HOST);
        return $scheme . '://' . $host;
    }
    public function getTags(Response $response, string $url): array
    {
        if ($response->successful()) {
            $h1Search = preg_match('/(?<=h1>).+(?=<\/h1>)/', $response->body(), $h1);
            $tags['h1'] = ($h1Search > 0) ? $h1[0] : '';
            $metaTagsSearch = get_meta_tags($url) ?: [];
            $tags['keywords'] = array_key_exists('keywords', $metaTagsSearch)
            ? $metaTagsSearch['keywords']
            : '';
            $tags['description'] = array_key_exists('description', $metaTagsSearch)
            ? $metaTagsSearch['description']
            : '';
            return $tags;
        }
        return ['h1' => '', 'keywords' => '', 'description' => ''];
    }
}
