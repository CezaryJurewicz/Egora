                                @if (auth('web')->check() || auth('admin')->check())
                                @if ( (auth('admin')->user() ?: auth('web')->user())->can('followersbyid', [App\User::class, $user->active_search_name_hash]) )
                                <a class="btn btn-primary btn-sm btn-block" href="{{ route('users.followersbyid', $user->active_search_name_hash) }}">Followers</a>
                                @endif
                                @endif
