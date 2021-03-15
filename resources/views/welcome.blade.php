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
                                <p>“Egora” is a contraction of “electronic” and “agora”. “Agora” is an ancient Greek
                                term meaning “gathering place”. For the ancient Greeks the agora of a town
                                served as the center of public life, where people could socialize, do business,
                                and hold discussions. Egora follows in that ancient Greek spirit, but its main
                                function is to enable a new form of democratic organization, one that is rational,
                                efficient, and incorruptible – i.e. Intelligent Democracy.</p>
                                <p>To explain it most briefly, Egora enables everyone to</p>
                                <ul>
                                    <li>develop their own political philosophy out of various ideas,</li>
                                    <li>determine which ideas are most strongly supported by the people,</li>
                                    <li>organize meetings to examine and deliberate any ideas, and</li>
                                    <li>use a simple algorithm to find the true representatives of the public will.</li>
                                </ul>
                                <p>However, Intelligent Democracy would not be possible without a community that
                                is dedicated to using reason to make sense out of a chaos. Thus, Egora is
                                actually the home of the International Logic Party (ILP), and we have built Egora
                                to organize ourselves. We want philosophers to have the advantage over
                                demagogues in politics, and Egora makes our mission possible. But you do not
                                have to be a member of the ILP to use Egora. We want democracy to be
                                available to everyone, and we invite you to use Egora to develop and express
                                your philosophy regardless of your affiliations. Furthermore, because Egora
                                technology is so versatile, it is possible for any other community to use Egora to
                                democratically organize themselves too.</p>
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
