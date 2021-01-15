@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card mb-3">
                <div class="card-header">Communities</div>
                <div class="card-body text-center">
                @foreach($user->communities as $community)
                {{ $community->title }} - {{ $community->participants->count() }}<br/>
                @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
