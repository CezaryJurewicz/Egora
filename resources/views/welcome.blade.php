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
    <style>
        @media screen and (min-width: 600px) {
            .modal {
                display: block; /* Hidden by default */
                position: fixed;
                z-index: 1; 
                left: 0;
                top: 0;
                width: 30%; 
                height: 30%; 
                overflow: none; 
                /*background-color: rgb(0,0,0);  Fallback color */
                /*background-color: rgba(0,0,0,0.4);  Black w/ opacity */
            }
        }

        @media screen and (max-width: 599px) {
            .modal {
              display: block;
              position: fixed;
              z-index: 1; 
              left: 0;
              top: 0;
              width: 50%; 
              height: 40%; 
              overflow: none; 
            }
        }
        .modal-content {
            /*background-color: #fefefe;*/
            background-color: rgba(0,0,0,0);
            margin: 1% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 0px solid #888;
            width: 90%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="myModal" class="modal">
        <div class="modal-content">
        <!--<span class="close">&times;</span>-->
        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/3hEzvAL-xgI?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
    <div class="text-center" style="background-image: url({{ asset('img/line-800.jpg') }}); background-repeat: repeat-x; background-size: 100px 100%;">
        <a href="{{ route('register') }}">
            <img style="width:60%;" class="img-fluid" src='{{ asset('img/Egora E-Image New.jpg') }}'>
        </a>
    </div>
    <div id="app">
        <main>
            @include('blocks.alerts')
            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="accordion mb-3" id="accordion">
                        <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                  Quick introduction for new visitors
                              </button>
                            </h2>
                          </div>

                          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body col-10 offset-1">
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
                        <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                          <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                  Egora: Version &Sigma; (archived)
                              </button>
                            </h2>
                          </div>

                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body col-10 offset-1 text-center">
                                    <div class="card">
                                        <div class="card-body mb-3">
                                            <h3><b>Egora: Version &Sigma;</b><br/>
                                            (archived: January 7, 2019)</h3>
                                        </div>
                                        <div class="card-body mb-3">
                                            <h5>Idea Dominance Index</h5>
                                            <a href="{{ asset('docs/NewIDI.pdf') }}" target="_blank" rel="noopener">
                                                <img src="{{ asset('img/pdf-64x64.png') }}" alt="" width="32" height="32">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                          </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                        
                        <div class="panel ">
                            <div class="panel-body">
                                <div class="row pt-2" id="ideas">
                                    <div class="col-md-4 text-center">
                                        @if (empty($sort)) 
                                        <a class="btn btn-primary btn-block" href="{{ route('index', array_merge(\Arr::only(\Request::query(),['sort', 'search', 'relevance', 'unverified', 'nation','page']), ['sort' => 'date'])) }}#ideas">{{ __('Newest Ideas') }}</a>
                                        @else
                                        <h5 class="pt-2">{{ __('Newest Ideas') }}</h5>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-center">
                                        @if (empty($sort) && empty($index)) 
                                        <h5 class="pt-2">{{ __('Idea Dominance Index') }}</h5>
                                        @else
                                        <a class="btn btn-primary btn-block" href="{{ route('index') }}#ideas">{{ __('Idea Dominance Index') }}</a>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-center">
                                        @if (empty($index)) 
                                        <a class="btn btn-primary btn-block" href="{{ route('index', ['index'=>'popularity']) }}#ideas">{{ __('Idea Popularity Index') }}</a>
                                        @else
                                        <h5 class="pt-2">{{ __('Idea Popularity Index') }}</h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                @auth
                                <div class="accordion mb-3 bt-0" id="accordion">
                                    <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                                      <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Guide
                                          </button>
                                        </h2>
                                      </div>

                                      <div id="collapseOne" class="collapse show1" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body col-md-10 offset-1">
                                            @if (empty($sort)) 
                                            <p>The Idea Dominance Index (IDI) shows what are the most <u>strongly</u>
                                            supported ideas universally and within any nation. As the people
                                            give more points to a particular idea within their Ideological Profiles,
                                            the idea rises higher on the IDI.</p>
                                            <p>If you think there is an idea that should be higher on the IDI, explain
                                            to other people why they should support this idea. If you think there
                                            is an idea that should not be so high on the IDI, give other people
                                            better ideas for them to support instead.</p>
                                            @else
                                            <p>This is simply a listing of ideas according to how recently they were introduced.</p>
                                            @endif
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                @endauth

                                @include('blocks.search')

                                @if ($index == 'popularity')
                                    @include('blocks.ideas', ['index'=>'popularity'])
                                @else
                                    @include('blocks.ideas', ['index'=>'dominance'])
                                @endif

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
