<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>

        <!-- Styles / Scripts -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    </head>
    <body>
        <header>
            <div class="container">
                <a href="{{ route('home') }}"><img src="{{ asset('img/logoNetflist.png') }}" alt="" id="logo"></a>
                @include('nav')
            </div>
        </header>
        @yield('content')
    </body>
</html>