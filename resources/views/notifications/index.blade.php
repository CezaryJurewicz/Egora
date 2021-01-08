@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-1 text-center">
                        <h3>{{ __('Notifications') }}</h3>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                <div class="card p-2">
                    @forelse($rows as $row)
                    <div class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                <div class="col-md-9">
                                    <!-- {{ $row->id }} -->
                                    <b>
                                    {{ $row->sender->active_search_names->first()->name ??  $row->sender->id }}                                    
                                    </b>
                                    @if ($row->invite)
                                        {{ __('invited you to examine their idea.') }}
                                    @elseif ($row->sender->liked_ideas->contains($row->idea))
                                        {{ __('supported your idea!') }}
                                    @elseif ($row->response)
                                        : {{ $row->notification_preset->title }}
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    @if ($row->invite)
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->idea->id,'notification_id'=>$row->id]) }}">{{ __('Open') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$row->invite)
                        <div class="card">
                            <div class="card-body">
                                {!! make_clickable_links(shorten_text($row->idea->content)) !!}
                            </div>
                        </div>
                        @endif
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-md-3">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('notifications.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                    {{  $rows->appends(compact('search', 'relevance', 'unverified', 'nation'))->render() }}
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

