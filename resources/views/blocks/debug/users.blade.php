                                                @if (request()->has('debug') && auth('admin')->check())
                                                <small>
                                                    <table>
                                                    @foreach( $users as $user)
                                                    <tr>
                                                        <td>#{{ $user->id }}</td>
                                                        <td><a href="{{ route('users.ideological_profile', [$user->active_search_names->first()->hash, 'debug' => 1]) }}">{{ $user->active_search_names->first()->name }}</a></td>
                                                        <td>{{ $user->lastOnlineAtDate() }}</td>
                                                        <td><b>{{ $user->pivot->position }}</b></td>
                                                    </tr>
                                                    @endforeach
                                                    </table>
                                                </small>
                                                @endif
