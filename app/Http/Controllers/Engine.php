<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class Engine extends Controller
{
    public function addUrl(Request $request): \Illuminate\Http\RedirectResponse
    {
        $url = $this->validateAndFilterUrl($request->input('url.name'));
        if ($url === false) {
            Session::flash('flash_mess_add_error', 'Некорректный URL: ' . $request->input('url.name'));
            return redirect()->route('home');
        }
        if (DB::table('urls')->where('name',  '=', $url)->exists()) {
            $id = DB::table('urls')->where('name', $url)->value('id');
            Session::flash('flash_mess_duplicate_url', 'Страница уже существует');
            return redirect()->route('showUrl', ['id' => $id]);
        }

            DB::table('urls')->upsert(
                [['name' => $url, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]],
                ['name'],
                ['updated_at']
            );
            Session::flash('flash_mess_add_success', 'Страница успешно добавлена');



    }

    public function showUrls(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
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

    public function showUrl(int $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $url = DB::table('urls')->where('id', $id)->first();
        $dataOfCheck = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return view('url', ['url' => $url, 'dataOfCheck' => $dataOfCheck, 'id' => $id]);
    }

    //Метод проверки страницы
    /**
     * @throws RequestException
     */
    public function checkUrl(Request $request): \Illuminate\Http\RedirectResponse
    {
        $url_id = $request->input('id');
        $url = (array) DB::table('urls')->where('id', $url_id)->first();

        //Проверяем на ошибку подключения
        try {
            $response = Http::get($url['name']);
        } catch (\Exception $e) {
            Session::flash('flash_mess_check_error', 'Ошибка: ' . $e->getMessage());
            return redirect()->route('showUrl', ['id' => $url_id]);
        }

        //Подключение успешно - собираем инфу по тегам и пишем данные по проверке подключения
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
        Session::flash('flash_mess_check_success', 'Страница успешно проверена');
        return redirect()->route('showUrl', ['id' => $url_id]);
    }

    //Вспомогательные методы////////////////////////////////////////////////////////
    public function validateAndFilterUrl(string $url): string | bool
    {
        $scheme = (string) parse_url($url, PHP_URL_SCHEME);
        $host = (string) parse_url($url, PHP_URL_HOST);
        if (($scheme == 'http' || $scheme == 'https') && !empty($host)) {
            return $scheme . '://' . $host;
        }
        return false;
    }
    public function getTags($response, string $url): array
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
