        @if (auth('web')->check() && auth('web')->user()->following->isNotEmpty())
        <div class="card">
            <div class="card-header">
                @lang('Following')
            </div>
            <div class="card-body">
                @foreach(auth('web')->user()->following as $u)
                <div class="row col-md-12">
                <a href="{{ route('users.ideological_profile', $u->active_search_names->first()->hash) }}">
                {{ $u->active_search_names->first()->name ?? $u->id }}
                </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif