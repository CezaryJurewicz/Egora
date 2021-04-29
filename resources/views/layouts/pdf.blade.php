<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'Egora') }}</title>
</head>
<body>
    <div id="app">
        <div class="py-5">
            <main role="main" class="container py-5                 ">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
