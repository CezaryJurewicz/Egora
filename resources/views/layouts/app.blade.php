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
    <div id="app">
        @if (auth('admin')->check())
            @include('blocks.admin_header')
        @else
            @include('blocks.header')        
        @endif
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Egora') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">                        
                        @if (auth('web')->check() || auth('admin')->check())
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\Nation::class))
                            <li>
                                <a class="nav-link" href="{{ route('nations.index')}}">{{ __('Nations') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\User::class))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index')}}">{{ __('Users') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\UserType::class))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user_types.index')}}">{{ __('User Types') }}</a>
                            </li>
                            @endif

                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\Idea::class))
                            <li>
                                <a class="nav-link" href="{{ route('ideas.index')}}">{{ __('Ideas') }}</a>
                            </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (auth('admin')->check()) {{  auth('admin')->user()->name  }} @endif
                                    @if (auth('web')->check()) {{  auth('web')->user()->name  }} @endif
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item"  style="line-height: initial;" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a></li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </ul>
                            </li>
                        @else                                        
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
