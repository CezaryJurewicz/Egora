@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card mb-3">
                <div class="card-header">Denizens</div>
                <div class="card-body text-center">
                    @if (isset($user->municipality))
                        {{ $user->municipality->title}} - {{ $user->municipality->users->count()}} <br/>
                    @else
                    none
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
