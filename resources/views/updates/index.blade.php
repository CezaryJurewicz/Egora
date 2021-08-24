@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 offset-1 text-center">
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
                        <div id="tabs">
                            <ul id="tabs" class="nav nav-pills nav-justified flex-column flex-sm-row nav-fill1 na1v-justified p1b-0" data-tabs="tabs">
                                @if (is_egora())
                                    @foreach(['Statuses'=>'status', 'Ideas'=>'idea', 'Comments'=>'comment', 'All Comments'=>'all', 'Followers'=>'follower'] as $title=>$id)
                                    <li class="nav-item active"><a style="font-size: small;" class="nav-link @if ($filter== $id) active @endif" href="#{{$id}}Tab" data-toggle="tab">{{$title}} ({{$result[$id]->count()}}) </a></li>
                                    @endforeach
                                @else
                                    @foreach([ 'Ideas'=>'idea', 'Comments'=>'comment', 'All Comments'=>'all'] as $title=>$id)
                                    <li class="nav-item active"><a style="font-size: small;" class="nav-link @if ($filter== $id) active @endif" href="#{{$id}}Tab" data-toggle="tab">{{$title}} ({{$result[$id]->count()}}) </a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>  
                    <div class="card-body pb-5 pl-5 pr-5 pt-4 ">
                        <div id="my-tab-content" class="tab-content">
                            @foreach(['Statuses'=>'status', 'Ideas'=>'idea', 'Followers'=>'follower', 'Comments'=>'comment', 'All Comments'=>'all'] as $title=>$id)
                            <div class="tab-pane @if ($filter== $id) active @endif" id="{{$id}}Tab" stle>
                                @if ($result[$id]->count() > 0)
                                <div class="mb-3">
                                    <form action="{{ route('updates.delete_filtered') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <input type="hidden" name="filter" value="{{$id}}"/>
                                        <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete All')}}</button>
                                    </form>
                                </div>
                                @endif

                                @forelse($result[$id] as $line)
                                    @if ($line->updatable)
                                        @if ($line->type == 'status')
                                            @include('blocks.updates.status', ['row' => $line, 'id' => $id])   
                                        @elseif ($line->type == 'follower')
                                            @include('blocks.updates.follower', ['row' => $line, 'id' => $id])   
                                        @elseif (($line->type == 'comment') || ($line->type == 'subcomment'))
                                            @include('blocks.updates.comment', ['row' => $line, 'id' => $id])   
                                        @elseif ($line->type == 'idea')
                                            @include('blocks.updates.idea', ['row' => $line, 'id' => $id])   
                                        @endif
                                    @endif
                                @empty
                                @endforelse
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

