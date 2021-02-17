@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    @if (is_egora())
                    <h3>{{ __('Ideological Profile') }}</h3>
                    @elseif (is_egora('community'))
                    <h3>{{ __('Community Matters') }}</h3>
                    @endif
                    <h3>Idea: Preview</h3>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                            Relevance: &nbsp;&nbsp;&nbsp;
                            @if (is_egora())
                                @if (isset($idea->nation))
                                {{ $idea->nation->title }} 
                                @endif
                            @elseif (is_egora('community'))
                                {{ $idea->community->title }}                             
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    {!! make_clickable_links(strip_tags(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), $idea->content)), '<br><p><b><i><li><ul><ol>')) !!}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mt-2">Supporters</h5>
                    </div>                
                    <div class="card-body">
                        @foreach($idea->liked_users_visible->sortByDesc('created_at')->take(92) as $user)
                        <a class="mr-2" href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">
                            {{ $user->active_search_names->first() ? $user->active_search_names->first()->name : '-'}} 
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                    </div>                
                
                    <div class="card-body text-center">
                        <div class="col-10 offset-1">
                        Egora, “The Worldwide Stock-Market of Ideas”, enables everyone to<br/>
                        – develop their own political philosophy out of various ideas,<br/>
                        – determine which ideas are most strongly supported by the people, and<br/>
                        – find the true representatives of the public will, to elect them into public office.
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a class='btn btn-primary btn-xl ml-2' href="{{ route('index') }}">{{__('Register Now')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

