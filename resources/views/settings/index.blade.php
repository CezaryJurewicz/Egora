@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body">
                <h3>{{ __('Control') }}</h3>
                
                <div>
                    @if($settings->isNotEmpty())                  
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Value')}}</th>
                                    <th scope="col">{{ __('tables.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                    
                    @forelse ($settings as $i=>$setting)
                                <tr>
                                    <th scope="row">{{$setting->id}}</th>
                                    <td>{{ $setting->name}}</td>
                                    <td>
                                        @if ($setting->type == 'text')
                                        <div style="overflow-y: scroll; height:200px;">{{ Markdown2::parse(no_html($setting->value)) }}</div> 
                                        @else
                                            {{$setting->value}}
                                        @endif
                                    </td>
                                    
                                    
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{{ route('settings.edit', $setting->id) }}">
                                            @lang('Edit')
                                        </a>
                                    </td>
                                </tr>
                    @empty
                        <p>@lang('Empty')</p>
                    @endforelse
                    
                    @if($settings->isNotEmpty())                  
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

