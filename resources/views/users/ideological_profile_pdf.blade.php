@extends('layouts.pdf')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                        <div class="row">
                            <div class="col col-md-8 offset-2">
                                <div class="text-center">
                                    @if (is_egora())
                                    <h3>{{ __('views.Ideological Profile') }}</h3>
                                    @if (isset($ip_score))
                                    <h5>Portfolio Score: {{ number_format($ip_score) }}</h5>
                                    @endif
                                    @elseif (is_egora('community'))
                                    <h3>{{ __('Community Matters') }}</h3>
                                    @elseif (is_egora('municipal'))
                                    <h3>{{ __('Municipal Matters') }}</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <div>
                            @if($user->liked_ideas->isNotEmpty())
                            <div class="card p-2">
                                @foreach($user->liked_ideas as $idea)
                                    <a id="idea{{$idea->id}}" style="display: block; position: relative;top: -70px;visibility: hidden;"></a>
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-9 col-sm-5">
                                                <b># @if ($idea->pivot->position>0) {{$idea->pivot->position}} 
                                                   @else 
                                                        @if (is_egora())
                                                            0 
                                                            @if ($idea->pivot->order < 0)
                                                            ({{negative_order()[$idea->pivot->order]}}) 
                                                            @else
                                                            ({{$idea->pivot->order}}) 
                                                            @endif
                                                        @else
                                                        ({{$idea->pivot->order}}) 
                                                        @endif 
                                                   @endif</b>
                                                
                                                @if ($idea->nation)
                                                    {{$idea->nation->title}}
                                                @elseif ($idea->community)
                                                    {{$idea->community->title}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    {!! make_clickable_links(nl2br(str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($idea->content)))) !!}
                                    <br/>
                                    <br/>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
</div>
@endsection

