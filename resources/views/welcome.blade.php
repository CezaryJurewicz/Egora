<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Egora') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="text-center" style="background-image: url({{ asset('img/line-800.jpg') }}); background-repeat: repeat-x; background-size: 100px 800px;">
        <a href="{{ route('register') }}">
            <img class="img-fluid" src='{{ asset('img/Egora E-Image_Welcome.png') }}'>
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
                                <p><b>Quick introduction for new visitors</b></p>
                                <p>“Egora” is a contraction of “electronic” and “agora”. Agora is an ancient Greek
                                word meaning “public space”. For ancient Greeks the agora served as an open
                                place for people to do business, to socialize, and to hold discussions. The
                                Egora follows in that ancient Greek spirit, except that it is an internet platform
                                that also enables a rational, efficient, and incorruptible form of political
                                organization – Intelligent Democracy.</p>
                                <p>As such, Egora is the home of the International Logic Party (ILP), which utilizes
                                the Egora to organize itself. You do not have to be a member of the ILP to use
                                Egora, but be aware that your input has a direct impact on the proceedings of
                                the ILP. Here, your voice has real power, and we want to hear it!</p>
                                <p>Lastly, Egora is free to use and protected by Copyleft. If you are dissatisfied
                                with this Egora, you can create your own version of it, and you can use <a href="https://github.com/CezaryJurewicz/Egora" target="_blank" rel="noopener">the code
                                of this Egora</a> to start. Egora welcomes all competition. May the best ideas win!</p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-12 text-center mt-5">
                        <div class="card">
                            <div class="card-body mb-3">
                                <h3><b>Egora: Version &Sigma;</b><br/>
                                (archived)</h3>
                            </div>
                            <div class="card-body mb-3">
                                <h5>Idea Dominance Index</h5>
                                <a href="{{ asset('docs/NewIDI.pdf') }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('img/pdf-64x64.png') }}" alt="" width="32" height="32">
                                </a>
                            </div>
                            <div class="card-body">
                                <h5>User Ideological Profiles</h5>
                                <a href="{{ asset('docs/NewIP.pdf') }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('img/pdf-64x64.png') }}" alt="" width="32" height="32">
                                </a>
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
