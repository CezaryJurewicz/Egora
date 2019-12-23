@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row mt-2 mb-2">
                    <div class="col-md-12">
                        <a class="col-md-1 btn btn-sm btn-primary" href="{{  url()->previous() }}">Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <img src="{{ Storage::url($user->verification_id->image->filename) }}" class="img-fluid img-thumbnail1" alt=""> 
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

