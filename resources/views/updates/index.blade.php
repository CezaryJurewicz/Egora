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
                
                <div class="card pb-5 pl-5 pr-5 pt-4 mt-5">
                    <div class="row mb-4">
                        <div class="col-12">
                            <form id="filter-form" action="{{ route('updates.index') }}" method="GET">
                                Filter:
                                <select id="filter" type="text" class="form-control" name="filter" 
                                    onchange="event.preventDefault(); document.getElementById('filter-form').submit();"
                                    value="{{ old('filter') }}">
                                    @foreach(['Statuses'=>'status', 'Ideas'=>'idea', 'Comments'=>'comment', 'All Comments'=>'all', 'Followers'=>'follower'] as $title=>$id)
                                    <option @if((old('filter') && old('filter')==$id) || ($filter && $filter==$id) || ($filter==0 && $filter==$id)) selected @endif value="{{$id}}">{{$title}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    @forelse($lines as $line)
                        @if ($line->updatable)
                            @if ($line->type == 'status')
                                @include('blocks.updates.status', ['row' => $line])   
                            @elseif ($line->type == 'follower')
                                @include('blocks.updates.follower', ['row' => $line])   
                            @elseif (($line->type == 'comment') || ($line->type == 'subcomment'))
                                @include('blocks.updates.comment', ['row' => $line])   
                            @elseif ($line->type == 'idea')
                                @include('blocks.updates.idea', ['row' => $line])   
                            @endif
                        @endif
                    @empty
                    @endforelse
                    
                    {{  $lines->render() }}
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

