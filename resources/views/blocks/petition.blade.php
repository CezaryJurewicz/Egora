                                    @if (auth('web')->check() && auth('web')->user() && $user->petition)
                                    <div class="panel mt-3">
                                        <div class="panel-body">
                                            <h5 class="mb-2">Officer Petition</h5>
                                            <p><b>Polis:</b><br>{{ $user->petition->polis }}</p>
                                            <p><b>Supporters:</b><br>
                                                @forelse($user->petition->supporters as $i=>$supporter)
                                                    {{$i+1}}. @if(null !== $supporter->active_search_names->first()) {{ $supporter->active_search_names->first()->name }} @else - @endif <br>
                                                @empty
                                                    <p>-</p>
                                                @endforelse
                                            </p>
                                        </div>
                                    </div>
                                    @if ( auth('web')->user()->can('support_officer_application', $user) )
                                    <a class="btn btn-ilp btn-sm btn-block" href="{{ route('ilp.support_officer_application', $user->id ) }}">Add My Name</a>
                                    @endif
                                    @if ( auth('web')->user()->can('unsupport_officer_application', $user) )
                                    <a class="btn btn-ilp btn-sm btn-block" href="{{ route('ilp.unsupport_officer_application', $user->id ) }}">Remove My Name</a>
                                    @endif
                                    @endif