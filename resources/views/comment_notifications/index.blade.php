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
                        @include('blocks.log.comment', ['row' => $row])   
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

