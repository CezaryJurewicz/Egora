@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Nations') }}</h3>
                <div>
                    @if($nations->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Title')}}</th>
                                    <th scope="col">{{ __('tables.Users')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($nations as $i=>$nation)
                                <tr>
                                    <th scope="row">{{$nation->id}}</th>
                                    <td>{{ $nation->title }}</td>
                                    <td>{{ $nation->users->count() }}</td>
                                    <td>
                                        @if ($nation->trashed())
                                        <form action="{{ route('nations.restore',['id'=>$nation->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-warning'>{{__('some.Restore')}}</button>
                                            </div>
                                        </form>
                                        @else
                                        <form action="{{ route('nations.delete',['id'=>$nation->id]) }}" method="POST">
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
                        <p>@lang('nations.No nations')</p>
                    @endforelse
                    
                    @if($nations->isNotEmpty())                    
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

