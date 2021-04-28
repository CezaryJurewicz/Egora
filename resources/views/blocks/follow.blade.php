                @if (auth('web')->check() && auth('web')->user()->can('unfollow', $user))
                    <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE"/>
                        <div class="input-group">
                            <button type='submit' class='btn btn-sm btn-primary btn-block'>{{__('some.Unfollow')}}</button>
                        </div>
                    </form>
                @endif
                @if (auth('web')->check() && auth('web')->user()->can('follow', $user))
                    <form action="{{ route('users.follow', $user->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <button type='submit' class='btn btn-sm btn-primary btn-block'>{{__('some.Follow')}}</button>
                        </div>
                    </form>
                @endif
