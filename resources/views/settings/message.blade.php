@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="text-center">
                    <h3>Mesage</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="col-10 offset-1">
                        {!! make_clickable_links(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($admin_message_text)))) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

