@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Content') }}</h3>
                <div>
                    @if($contents->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Title')}}</th>
                                    <th scope="col">{{ __('tables.Alias')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($contents as $i=>$content)
                                <tr>
                                    <th scope="row">{{$content->id}}</th>
                                    <td>{{ $content->title }}</td>
                                    <td>{{ $content->alias }}</td>
                                    <td>
                                        <form action="{{ route('contents.delete', $content->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('contents.No content')</p>
                    @endforelse
                    
                    @if($contents->isNotEmpty())                    
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

