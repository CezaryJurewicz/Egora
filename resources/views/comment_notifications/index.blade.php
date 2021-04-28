@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">

        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-1 text-center">
                        <h3>{{ __('Comment Notifications') }}</h3>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                <div class="card p-5">
                    @forelse($rows as $row)
                    <div id="nid{{$row->id}}" class="mb-3">
                        <div class="pb-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                        <b>
                                            <a style="color:#000;" href="{{ route('users.ideological_profile', $row->sender->active_search_name_hash) }}">
                                            {{ $row->sender->active_search_name ??  $row->sender->id }} 
                                            </a>
                                        </b>
                                        {{ $row->message }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            {{ $row->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right">
                                    @if ($row->comment->is_response())
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->comment->commentable->commentable, 'open'=>$row->comment->commentable->id]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>
                                    @elseif (!is_null($row->comment->commentable))
                                    <a class="btn btn-primary btn-sm" href="{{ route('ideas.view', [$row->comment->commentable]).'#comment-'.$row->comment->id }}">{{ __('Open') }}</a>                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {!! make_clickable_links(shorten_text($row->comment->message)) !!}
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-3 text-left">
                                    @if( Auth::guard('web')->check() && Auth::guard('web')->user()->can('delete', $row) )
                                    <form action="{{ route('comment_notifications.delete', $row->id) }}" method="POST" enctype="multipart/form-data">
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
                    
                    {{  $rows->render() }}
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

