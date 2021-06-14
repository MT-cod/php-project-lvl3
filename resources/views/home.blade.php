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

    {{--@if(Session::has('errors'))
        <div style="background-color: #ff885a">{{ Session::get('errors') }}</div>--}}
    @if ($errors->any())
        <div style="background-color: #ff885a">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @else
        <br>
    @endif

        <a class="nav-link" href="/">[Анализатор страниц]</a>
        <a class="nav-link" href="/urls">[Все добавленные страницы]</a>
        <form action="/urls" method="post">
            {{--<input id="url[name]" type="text" name="url[name]" value="" placeholder="https://www.example.com">--}}
            <input id="url[name]" type="text" name="url[name]" value="{{ old('url.name') }}" class="@error('url.name') is-invalid @enderror" placeholder="https://www.example.com">
            <button type="submit">Проверить</button>
        </form>
    </body>
</html>
