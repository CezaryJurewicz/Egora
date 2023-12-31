                                @if (auth('web')->check() || auth('admin')->check())
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('leadsbyid', [App\User::class, $user->active_search_names->first()->hash]) )
                                <a class="btn btn-primary btn-sm btn-block" href="{{ route('users.leadsbyid', $user->active_search_names->first()->hash) }}">Leads</a>
                                @endif
                                @endif
