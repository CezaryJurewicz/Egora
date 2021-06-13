@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
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
                    <p>Using the 23 Administrative Subdivision categories, users of Egora can self-organize 
                        themselves into various voting blocks corresponding to the various administrative 
                        districts within their respective countries. For example, users from United States 
                        of America can use Subdivision 1 to create the category of "Alabama Senator", use 
                        Subdivision 2 to create the category of "Alabama Representative District 1", use 
                        Subdivision 3 to create the category of "Alabama Governor", and so on. However, 
                        for the properly established Administrative Subdivision categories, please defer to 
                        the leadership of your national branch of the ILP.</p>
                    <p>Subsequently, on the Campaigns screen, Egora users can generate a Candidate Rank 
                        Listing for each Administrative Level of their nation – Head of State and the 23 
                        Administrative Subdivisions. Finally, the Members of the ILP will filter out and 
                        nominate the best of those candidates.</p>
                </div>
              </div>
            </div>
        </div>
        
        <div class="panel ">
            <div class="panel-body">
                <div class="row">
                    <div class="col-10 offset-1 text-center">
                        <h3>{{ __('Administrative Subdivisions') }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">{{__('Close')}}</a>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="card p-5">
                    
                <form autocomplete="off" method="POST" action="{{ route('users.subdivisions_update') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT"/>
                    @csrf
                    
                    @for($i=1; $i<24; $i++)
                        <div class="form-group row">
                            <div class="col-12">
                                <label>Subdivision {{$i}}</label>
                            </div>
                            <div class="form-group row col-12">
                                <div class="col-11">
                                    <div style="position: absolute; width: 96%;" class="subdivisionsearch" name="subdivisions[{{$i}}]" value="{{ old('subdivisions.$i')?: (isset($subdivisions[$i]) ? $subdivisions[$i]->title : '') }}"></div>
                                </div>
                                <button style="height: calc(1.7em + 0.75rem + 2px);" class="btn btn-primary btn-sm col-1">{{ __('Save') }}</button>
                            </div>
                        </div>
                    @endfor
                </form>
                </div>
                
                <div class="row">
                    <div class="col-3 mt-3">
                    <a class='btn btn-primary btn-sm btn-block' href="{{ route('users.ideological_profile', $user->active_search_name_hash) }}">{{__('Close')}}</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

