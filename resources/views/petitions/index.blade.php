@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Petitions') }}</h3>
                <div>
                    @if($petitions->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Nation')}}</th>
                                    <th scope="col">{{ __('tables.User')}}</th>
                                    <th scope="col">{{ __('tables.Supporters')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($petitions as $i=>$petition)
                                <tr>
                                    <th scope="row">{{$petition->id}}</th>
                                    <td>{{ $petition->user->nation->title }}</td>
                                    <td>{{ $petition->user->name }}</td>
                                    <td>{{ $petition->supporters->count() }}</td>
                                    <td>
                                        <form action="{{ route('petitions.delete',['id'=>$petition->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('petitions.No petitions')</p>
                    @endforelse
                    
                    @if($petitions->isNotEmpty())                    
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

