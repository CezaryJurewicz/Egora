        @if (auth('web')->check() && auth('web')->user()->following->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5>@lang('Leads')</h5>
            </div>
            <div class="card-body">
                @foreach(auth('web')->user()->following->sortBy('active_search_name') as $u)
                @if($u->active_search_names->first())
                <div class="row col-md-12">
                <a href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                {{ $u->active_search_name ?? $u->id }}
                </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif