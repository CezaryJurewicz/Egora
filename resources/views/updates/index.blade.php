@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1 text-center">
                        <h3>{{ __('Updates') }}</h3>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                @if(is_egora())
                <div class="accordion mb-3" id="accordion">
                    <div class="card" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); border-radius: calc(0.25rem - 1px);">
                      <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Guide
                          </button>
                        </h2>
                      </div>

                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body col-md-10 offset-1">
                            <p>The Updates function let's you know what's going on around Egora. "Status", "Ideas", "Main Comments", and "All Comments" sections keep you informed about what your leads are doing, and the "Followers" section tells you when you have new followers.</p>                            
                        </div>
                      </div>
                    </div>
                </div>
                @endif 
                
                <div class="card mt-5">
                    <div class="card-header">
                        <div id="updates-tabs">
                            <div id="tabs" class="nav justify-content-center" data-tabs="tabs">
                                    @foreach(['Statuses'=>'status', 'Ideas'=>'idea', 'Comments'=>'comment', 'All Comments'=>'all', 'Followers'=>'follower'] as $title=>$id)
                                    <a style="font-size: small;" class="btn btn-primary col-12 col-md-2 m-1 @if ($filter== $id) active @endif" href="#{{$id}}Tab" data-toggle="tab">{{$title}} ({{$result[$id]->count()}}) </a>
                                    @endforeach
                            </div>
                        </div>
                    </div>  
                    <div class="card-body pb-2 pl-2 pr-2 pt-1 pb-md-5 pl-md-5 pr-md-5 pt-md-4">
                        <div id="my-tab-content" class="tab-content">
                            @foreach(['Statuses'=>'status', 'Ideas'=>'idea', 'Followers'=>'follower', 'Comments'=>'comment', 'All Comments'=>'all'] as $title=>$id)
                            <div class="tab-pane @if ($filter== $id) active @endif" id="{{$id}}Tab" stle>
                                @if ($result[$id]->where('egora_id', current_egora_id())->isNotEmpty())
                                    @include('blocks.updates.card', ['rows' => $result[$id]->where('egora_id', current_egora_id()), 'eid' => current_egora_id(), 'filter' => $id])   
                                @endif 
                                
                                @foreach($result[$id]->groupBy('egora_id')->diffKeys([current_egora_id() => []])->sortKeys() as  $eid => $nlines)
                                    @include('blocks.updates.card', ['rows' => $nlines, 'eid' => $eid, 'filter' => $id])
                                @endforeach
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

