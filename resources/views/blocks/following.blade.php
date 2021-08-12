        @if (auth('web')->check() && auth('web')->user()->following->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5>@lang('Leads') ({{ auth('web')->user()->following->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach(auth('web')->user()->following->sortBy('active_search_name') as $u)
                @if($u->active_search_names->first())
                <div class="row col-md-12">
                <a @if ($u->last_online_at->diffInDays(now()) > 23) style="color: #838383;" @endif href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                {{ $u->active_search_name ?? $u->id }}
                </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif