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
    <body  style="text-align: center;">
    <center>
        <div>
        <a class="nav-link" href="/">[Анализатор страниц]</a>
        <a class="nav-link" href="/urls">[Все добавленные страницы]</a>
        </div>

        <div class="container-lg">
            <h1 class="mt-5 mb-3">Страницы</h1>
            <div class="table-responsive">
                <table  style="table-layout: auto; width: auto;">
                    <tbody><tr style="background-color: #9acfd4;">
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Дата создания</th>
                        <th>Дата обновления</th>
                    </tr>
                        @foreach ($url as $row)
                            <tr style="background-color: #bddfe2;">
                            <th>{{ $row->id }}</th>
                            <th>{{ $row->name }}</th>
                            <th>{{ $row->created_at }}</th>
                            <th>{{ $row->updated_at }}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </center>
    </body>
</html>
