<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Egora') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @if (auth('admin')->check())
            @include('blocks.admin_header')
        @else
            @include('blocks.header')        
        @endif
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (auth('web')->check() || auth('admin')->check())
                            @if (auth('web')->check() && auth('web')->user()->can('home', App\User::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'home') ? ' active' : '' }}" href="{{ route('home')}}">{{ __('Egora') }}</a>
                            </li>
                            @endif                                                    
                            
                            @if (auth('web')->check() && auth('web')->user()->can('municipal', App\User::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'municipal') ? ' active' : '' }}" href="{{ route('municipal')}}">{{ __('Egora') }}</a>
                            </li>
                            @endif                                                    
                            
                            @if (auth('web')->check() && auth('web')->user()->can('community', App\User::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'community') ? ' active' : '' }}" href="{{ route('community')}}">{{ __('Egora') }}</a>
                            </li>
                            @endif                                                    
                            
                            @if (auth('web')->check() && auth('web')->user()->can('ideological_profile', [App\User::class, auth('web')->user()->active_search_names->first()->hash]) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'users.ideological_profile' && Request::segment(2) == auth('web')->user()->active_search_names->first()->hash) ? ' active' : '' }}" href="{{ route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash)}}">{{ __('Home') }}</a>
                            </li>
                            @endif

                            @if (auth('web')->check() && auth('web')->user()->can('viewAny', App\LogLine::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'log.index') ? ' active' : '' }}" href="{{ route('log.index')}}">{{ __('Inbox') }} @if(($inbox_notifications_cnt || $inbox_comment_notifications_cnt) && (($inbox_notifications_cnt+$inbox_comment_notifications_cnt)>0) ) ({{ $inbox_notifications_cnt + $inbox_comment_notifications_cnt }})@endif</a>
                            </li>
                            @endif                        
                            
                            @if (auth('web')->check() && auth('web')->user()->can('viewAny', App\Notification::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'notifications.index') ? ' active' : '' }}" href="{{ route('notifications.index')}}">{{ __('Inbox') }} @if($inbox_notifications_cnt && $inbox_notifications_cnt >0) ({{ $inbox_notifications_cnt }})@endif</a>
                            </li>
                            @endif                        
                            
                            @if (auth('web')->check() && auth('web')->user()->can('viewAny', App\CommentNotification::class) )
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'comment_notifications.index') ? ' active' : '' }}" href="{{ route('comment_notifications.index')}}">{{ __('Comments') }} @if($inbox_comment_notifications_cnt && $inbox_comment_notifications_cnt >0) ({{ $inbox_comment_notifications_cnt }})@endif</a>
                            </li>
                            @endif                        

                            @if (auth('web')->user() && auth('web')->user()->can('search', App\User::class))
                            <li class="nav-item">
                                <a class="nav-link{{ ( (Route::current()->getName() == 'users.search') || (Route::current()->getName() == 'users.ideological_profile' && auth('web')->check() && Request::segment(2) != auth('web')->user()->active_search_names->first()->hash) ) ? ' active' : '' }}" href="{{ route('users.search')}}">{{ __('Users') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\User::class))
                            <li class="nav-item">
                                <a class="nav-link{{ (Route::current()->getName() == 'users.index') ? ' active' : '' }}" href="{{ route('users.index')}}">{{ __('All users') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\User::class))
                            <li class="nav-item">
                                <a class="nav-link{{ (Route::current()->getName() == 'default_leads') ? ' active' : '' }}" href="{{ route('default_leads')}}">{{ __('Default leads') }}</a>
                            </li>
                            @endif

                            @if (auth('admin')->user() && auth('admin')->user()->can('viewAny', App\Admin::class))
                            <li class="nav-item">
                                <a class="nav-link{{ (in_array(Route::current()->getName(), ['admins.index', 'admins.create'])) ? ' active' : '' }}" href="{{ route('admins.index')}}">{{ __('Guardians') }}</a>
                            </li>
                            @endif

                            @if ((auth('web')->user()?:auth('admin')->user())->can('administrate', App\Idea::class))
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'ideas.index') ? ' active' : '' }}" href="{{ route('ideas.index')}}">{{ __('All Ideas') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewIdi', App\Idea::class))
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'ideas.indexes' || Route::current()->getName() == 'ideas.popularity_indexes' ) ? ' active' : '' }}" href="{{ route('ideas.indexes', ['sort'=>'date'])}}">{{ __('Ideas') }}</a>
                            </li>
                            @endif
                            
                            @if (auth('web')->check() && auth('web')->user()->can('viewIPI', App\Idea::class))
                            <li>
                                <a class="nav-link{{ (Route::current()->getName() == 'ideas.popularity_indexes' || Route::current()->getName() == 'ideas.popularity_indexes' ) ? ' active' : '' }}" href="{{ route('ideas.popularity_indexes', ['sort'=>'date'])}}">{{ __('Ideas') }}</a>
                            </li>
                            @endif
                            
                            @if (auth('web')->user() && auth('web')->user()->can('viewAny', App\Meeting::class))
                            <li class="nav-item">
                                <a class="nav-link{{ (Route::current()->getName() == 'meetings.index') ? ' active' : '' }}" href="{{ route('meetings.index')}}">{{ __('Meetings') }}</a>
                            </li>
                            @else
                                @if (auth('admin')->user() &&auth('admin')->user()->can('viewAny', App\Meeting::class))
                                <li class="nav-item">
                                    <a class="nav-link{{ (Route::current()->getName() == 'meetings.all') ? ' active' : '' }}" href="{{ route('meetings.all')}}">{{ __('Meetings') }}</a>
                                </li>
                                @endif
                            @endif
                            
                            @if (auth('web')->check() && auth('web')->user()->can('viewAny', App\Campaign::class))
                            <li class="nav-item">
                                <a class="nav-link{{ (Route::current()->getName() == 'campaigns.index' || Route::current()->getName() == 'campaigns.search' ) ? ' active' : '' }}"" href="{{ route('campaigns.index')}}">{{ __('Campaigns') }}</a>
                            </li>
                            @endif                            
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\Content::class))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contents.index')}}">{{ __('Contents') }}</a>
                            </li>
                            @endif
                            
                            @if ((auth('web')->user()?:auth('admin')->user())->can('viewAny', App\UserType::class))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user_types.index')}}">{{ __('User Types') }}</a>
                            </li>
                            @endif
                            
                            @if (auth('web')->check() && !empty($admin_message_text))
                            <li class="nav-item">
                                <a class="nav-link{{ (Route::current()->getName() == 'settings.message') ? ' active' : '' }}" href="{{ route('settings.message')}}">{{ __('Information') }}</a>
                            </li>
                            @endif
                            
                            @if (auth('admin')->check() && auth('admin')->user()->can('viewAny', App\Setting::class))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('settings.index')}}">{{ __('Control') }}</a>
                            </li>
                            @endif
                            
                    </ul>
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">                        
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (auth('admin')->check()) {{  auth('admin')->user()->name  }} @endif
                                    @if (auth('web')->check()) {{  auth('web')->user()->name  }} @endif
                                    <span class="caret"></span>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @foreach( config('egoras') as $key => $item)
                                        @if (auth('web')->check() && auth('web')->user()->can('switch', [App\User::class, $key]) )
                                        <li>
                                            <a class="dropdown-item" href="{{ route('switch', [$key, 'home'] ) }}">{{ __(config(implode('.',['egoras',$key,'title']))) }}</a>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                        @endif
                                    @endforeach
                                    
                                    @if (auth('web')->check() && auth('web')->user()->can('settings',auth('web')->user()) )
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.settings',  auth('web')->user()->id)}}">{{ __('Settings') }}</a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    @endif

                                    @if (auth('admin')->check())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.settings') }}">{{ __('Settings') }}</a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    @endif
                                    
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
                    </ul>
                    <ul class="navbar-nav ml-auto">
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Login / Register') }}</a>
                                </li>
                            @endif

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="py-5">
            <main role="main" class="container py-5                 ">
                @include('blocks.alerts')

                @yield('content')
            </main>
        </div>
        
        <div class="text-center">
            Egora - Copyleft 2018
        </div>
    </div>
</body>
</html>
