<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="text-center" style="background-image: url({{ asset('img/line.png') }}); background-repeat: repeat-x; background-size: 200px 700px;">
        <a href="{{ route('register') }}">
            <img  height="700px" src='{{ asset('img/Egora_E-image.png') }}'>
            <div id="example" style="font-size: 20px; position: absolute; top:450px; left:50%; margin: 0 0 0 -30px;"><b>ENTER</b></div>
        </a>
    </div>
    <div id="app">
        <main>
            @include('blocks.alerts')
            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">

                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p>“Egora” is a contraction of “electronic” and “agora”. Agora is an ancient Greek
                                term meaning “public space”. For ancient Greeks, the agora served as an open
                                place for people to do business, to socialize, and to hold discussions. The
                                Egora follows in that ancient Greek spirit, except it is an internet platform that
                                also enables a rational, efficient, and incorruptible form of political organization –
                                Intelligent Democracy.</p>
                                <p>As such, Egora is the home of the International Logic Party (ILP), which uses
                                this platform to organize itself. You do not have to be a member of the ILP to
                                use Egora, but be aware that your input has a direct impact on the proceedings
                                of the ILP. Here, your voice has real power, and we want to hear it!</p>
                                <p>Lastly, Egora is free to use and protected by Copyleft. If you are dissatisfied
                                with this Egora, you can create your own version of it. Egora welcomes all
                                competition. May the best ideas win!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <div class="mt-3 text-center">
        Egora - Copyleft 2018
    </div>
</body>
</html>
