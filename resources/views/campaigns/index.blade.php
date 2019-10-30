@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('views.Campaigns') }}</h3>
                <div>
                    @if($campaigns->isNotEmpty()) 
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('tables.Users')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($campaigns as $i=>$campaign)
                                <tr>
                                    <th scope="row">{{$campaign->id}}</th>
                                    <td>{{ $campaign->users->count() }}</td>
                                    <td>
                                        <form action="{{ route('campaigns.delete',['id'=>$campaign->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-sm btn-danger'>{{__('some.Delete')}}</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('campaigns.No campaigns')</p>
                    @endforelse
                    
                    @if($campaigns->isNotEmpty())                    
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

