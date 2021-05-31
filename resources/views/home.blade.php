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
            }
        </style>
    </head>
    <body style="text-align: center;">
        @if(Session::has('flash_mess_add_success'))
            <div style="background-color: #7fe2b4">{!! session('flash_mess_add_success') !!}</div>
        @elseif(Session::has('flash_mess_add_error'))
            <div style="background-color: #ff885a">{!! session('flash_mess_add_error') !!}</div>
        @else
            <br>
        @endif

        <a class="nav-link" href="/">[Анализатор страниц]</a>
        <a class="nav-link" href="/urls">[Все добавленные страницы]</a>
        <form action="/" method="post">
            <input type="text" name="url[name]" value="" class="form-control form-control-lg" placeholder="https://www.example.com">
            <button type="submit">Проверить</button>
        </form>
    </body>
</html>
