                <div>
                    @if($ideas->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Content')}}</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                    @if($index == 'dominance')
                                    <th scope="col">{{ __('tables.Points')}}</th>
                                    @else
                                    <th scope="col">{{ __('tables.Supporters')}}</th>
                                    @endif
                                    <th scope="col">{{ __('tables.User Type')}}</th>
<!--                                    <th scope="col">{{ __('tables.Created')}}</th>
                                    <th scope="col">{{ __('tables.Updated')}}</th>                                    -->
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($ideas as $i=>$idea)
                                <tr>
                                    <th scope="row">{{$idea->id}}</th>
                                    <td>
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                            <a href="{{ route('ideas.view', $idea->id) }}">
                                        @endif
                                            {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $idea->nation->title }}</td>
                                    @if($index == 'dominance')
                                    <td>{{ $idea->liked_users->pluck('pivot.position')->sum() }}</td>
                                    @else
                                    <td>{{ $idea->liked_users->count() }}</td>
                                    @endif
                                    <td>{{ $idea->user->user_type->title }}</td>
<!--                                    <td>{{ $idea->createdDate() }}</td>
                                    <td>{{ $idea->updatedDate() }}</td>-->
                                </tr>
                    @empty
                        <p>@lang('ideas.No ideas found')</p>
                    @endforelse
                    
                    @if($ideas->isNotEmpty())                  
                            </tbody>
                        </table>
                    
                        {{ $ideas->appends(request()->except('_token'))->links() }}
                    @endif
                </div>
