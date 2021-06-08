<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mt-cod / Page analizer</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
                text-align: center;
                background-color: #dbe8f3;
            }
            .pagination li{
                list-style-type: none;
                float: left;
            }
        </style>
    </head>
    <body>
    <center>
        <div>
        <a class="nav-link" href="/">[Анализатор страниц]</a>
        <a class="nav-link" href="/urls">[Все добавленные страницы]</a>
        </div>
        <h1 class="mt-5 mb-3">Страницы</h1>
        <table style="table-layout: auto; width: auto;">
            <tbody><tr style="background-color: #9acfd4;">
                <th>ID</th>
                <th>Имя</th>
                <th>Последняя проверка</th>
                <th>Код ответа</th>
            </tr>
            @foreach ($urls as $url)
                <tr style="background-color: #bddfe2;">
                    <th>{{ $url->id }}</th>
                    <th><a href="/urls/{{ $url->id }}">{{ Str::limit($url->name, 50, '...') }}</a></th>
                    <th>{{ $url->updated_at }}</th>
                    <th>{{ $url->status_code }}</th>
                </tr>
            @endforeach
            </tbody>
            <tr><th>{!! $urls->links('pagination::bootstrap-4') !!}</th></tr>
        </table>
    </center>
    </body>
</html>
