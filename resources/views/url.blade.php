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
        </style>
    </head>
    <body>
    <center>

        @if(Session::has('success'))
            <div style="background-color: #7fe2b4">{{ session()->pull('success') }}</div>
        @elseif(Session::has('errors'))
            <div style="background-color: #ff885a">{{ session()->pull('errors') }}</div>
        @else
            <br>
        @endif

        <div>
        <a class="nav-link" href="/">[Анализатор страниц]</a>
        <a class="nav-link" href="/urls">[Все добавленные страницы]</a>
        </div>

        <div class="container-lg">
            <h1 class="mt-5 mb-3">Страница: {{ Str::limit($url->name, 50, '...') }}</h1>
            <div class="table-responsive">
                <table  style="table-layout: auto; width: auto;">
                    <tbody><tr style="background-color: #9acfd4;">
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Дата создания</th>
                        <th>Дата обновления</th>
                    </tr>
                        <tr style="background-color: #bddfe2;">
                        <th>{{ $url->id }}</th>
                        <th>{{ Str::limit($url->name, 50, '...') }}</th>
                        <th>{{ $url->created_at }}</th>
                        <th>{{ $url->updated_at }}</th>
                        </tr>
                </tbody>
                </table>
            </div>
        </div>

        <br>

        <form action="/urls/{id}/checks" method="post">
            <input type="hidden" value="{{ $id }}" name="id"/>
            <button type="submit">Проверить</button>
        </form>

        <table  style="table-layout: auto; width: auto;">
            <tbody><tr style="background-color: #a4e7ac;">
                <th>ID</th>
                <th>Код ответа</th>
                <th>h1</th>
                <th>keywords</th>
                <th>description</th>
                <th>Дата создания</th>
            </tr>
            @foreach ($dataOfCheck as $row)
                <tr style="background-color: #c2f3d7;">
                    <th>{{ $row->id }}</th>
                    <th>{{ $row->status_code }}</th>
                    <th>{{ Str::limit($row->h1, 20, '...') }}</th>
                    <th>{{ Str::limit($row->keywords, 30, '...') }}</th>
                    <th>{{ Str::limit($row->description, 50, '...') }}</th>
                    <th>{{ $row->created_at }}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </center>
    </body>
</html>
