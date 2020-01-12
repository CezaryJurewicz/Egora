@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Founding Members') }}</h4>
                <div class="card">
                    <div class="card-body text-justify">
                        <div class="text-center">
                            <p><b>
                                Cezary Jurewicz - Engineer, Activist, Comedian (Poland; USA)<br/><br/>
                                Velko Krunić - Lyft Driver with 5-star rating (Bosnia; USA)<br/><br/>
                                Eren Oǧuzhan - Engineer, Business & Management (Turkey; USA)<br/><br/>
                                Sylwia Domagała - Activist (Poland; USA)<br/><br/>
                                David Padzimąrz - Visionary, Architect, Analyst (Poland; USA)<br/><br/>
                                Robert Stanley - Street-philosopher, Political Activist, Podcaster (Texas, USA)<br/><br/>
                                Eric Meyer - Voluntarist Sceptic (Missouri, USA)<br/><br/>
                                Marcin Sękiewicz - Logistics Pathfinder (Poland)<br/><br/>
                                Boris Vidanovič - Business Owner (Serbia; USA)<br/><br/>
                                Milan Malbasa - Logistics Specialist (Croatia; USA)<br/><br/>
                                Gail Garza - Activist, Guardian Angel (Illinois, USA)<br/><br/>
                                Aaron Leiva - Student, Activist (Equador; USA)<br/><br/>
                                Jessica Chrzan - Student, Activist (Poland; USA)<br/><br/>
                                Allie Good-Gadziemski - Teacher, Environmentalist, Yogi, Christian, Mother (Michigan, USA)<br/><br/>
                                Adam Zigulis - Jack of all trades, master of none (Illinois, USA)<br/><br/>
                                Andriy Rudiy - Musician (Ukraine; USA)<br/><br/>
                                Jeffrey Olichwier - Realtor, Entrepreneur (Illinois, USA)<br/><br/>
                                Taras Hryniw - Business Owner, Activist (Illinois, USA)<br/><br/>
                                Sean Hardin - Activist (Minneapolis, USA)<br/><br/>
                                Richard Grabert - Humanitarian, Bad-ass contractor (Illinois, USA)<br/><br/>
                                Vicki Elberfeld - Educator, Storyteller (Illinois, USA)<br/><br/>
                                Nazar Kravtsiv - Explorer of Life (Ukraine)<br/><br/>
                                Roksolana Bahan - Leader, Officer, and Coordinator (Ukraine)<br/><br/>
                                Panajot Baka - Scientist, Psychologist (Albania)<br/><br/>
                                </b>
                            </p>
                            
                            
                            <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3">
                                <a class='btn btn-ilp btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->active_search_names->first()->hash) }}">{{__('Close')}}</a>
                            </div>
                            <div class="col-md-3 offset-6">
                                <a class='btn btn-ilp btn-block' href="{{ route('ilp.menu') }}">{{__('ILP Functions')}}</a>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

