@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Ideas') }}</h3>
                <div>
                    @if($ideas->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Content')}}</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                    <th scope="col">{{ __('tables.User')}}</th>
                                    <th scope="col">{{ __('tables.Position')}}</th>
                                    <th scope="col">{{ __('tables.Created')}}</th>
                                    <th scope="col">{{ __('tables.Updated')}}</th>                                    
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($ideas as $i=>$idea)
                                <tr>
                                    <th scope="row">{{$idea->id}}</th>
                                    <td>
                                        <a href="{{ route('ideas.view', $idea->id) }}">
                                            {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...
                                        </a>
                                    </td>
                                    <td>{{ $idea->nation->title }}</td>
                                    <td>#{{ $idea->user->id }}: {{ $idea->user->name }}</td>
                                    <td>{{ $idea->position }}</td>
                                    <td>{{ $idea->createdDate() }}</td>
                                    <td>{{ $idea->updatedDate() }}</td>
                                    
                                    
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">
                                            @lang('some.View')
                                        </a>

                                        @if ($idea->trashed())
                                        <form action="{{ route('ideas.restore',['id'=>$idea->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-warning'>{{__('some.Restore')}}</button>
                                            </div>
                                        </form>
                                        @else
                                        <form action="{{ route('ideas.delete',['id'=>$idea->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('ideas.No ideas')</p>
                    @endforelse
                    
                    @if($ideas->isNotEmpty())                  
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

