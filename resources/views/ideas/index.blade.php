@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Ideas') }}</h3>
                <form action="{{ route('ideas.search') }}" method="POST">
                <div class="form-group row">
                    <label for="search" class="col-md-2 col-form-label">{{ __('Search') }}</label>

                        @csrf
                        <div class="col-md-6">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') }}" autofocus required>

                            @error('search')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <div class="input-group">
                                <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Search')}}</button>
                            </div>
                        </div>
                </div>
                </form>
                
                <div>
                    @if($ideas->isNotEmpty())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Content')}}</th>
                                    <th scope="col">{{ __('Relevance')}}</th>
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
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                            <a href="{{ route('ideas.view', $idea->id) }}">
                                        @endif
                                            {{ implode(' ', array_slice(explode(' ', $idea->content), 0, 5)) }} ...
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $idea->nation ? $idea->nation->title : $idea->community->title}}</td>
                                    <td>{{ $idea->createdDate() }}</td>
                                    <td>{{ $idea->updatedDate() }}</td>
                                    
                                    
                                    <td>
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('view', $idea))
                                        <a class="btn btn-sm btn-primary" href="{{ route('ideas.view', $idea->id) }}">
                                            @lang('some.View')
                                        </a>
                                        @endif
                                        
                                        @if ((auth('web')->user()?:auth('admin')->user())->can('delete', $idea))
                                            @if ($idea->trashed())
                                            <form action="{{ route('ideas.restore',$idea->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="PUT"/>
                                                <div class="input-group">
                                                    <button type='submit' class='btn btn-sm btn-warning'>{{__('some.Restore')}}</button>
                                                </div>
                                            </form>
                                            @else
                                            <form action="{{ route('ideas.delete',$idea->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                <div class="input-group">
                                                    <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                                </div>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('ideas.No ideas')</p>
                    @endforelse
                    
                    @if($ideas->isNotEmpty())                  
                            </tbody>
                        </table>
                    
                        {{ $ideas->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

