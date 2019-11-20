@extends('layouts.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a class="nav-link" href="{{ route('register') }}">
                <img  width="100%" src='{{ asset('img/Egora_Final.jpeg') }}'>
            </a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>“Egora” is a contraction of “electronic” and “agora”. Agora is an ancient Greek
                    term meaning “public space”. For ancient Greeks, the agora served as an open
                    place for people to do business, to socialize, and to hold discussions. The
                    Egora follows in that ancient Greek spirit, except it is an internet platform that
                    also enables a rational, efficient, and incorruptible form of political organization –
                    Intelligent Democracy.</p>
                    <p>As such, Egora is the home of the International Logic Party (ILP), which uses
                    this platform to organize itself. You do not have to be a member of the ILP to
                    use Egora, but be aware that your input has a direct impact on the proceedings
                    of the ILP. Here, your voice has real power, and we want to hear it!</p>
                    <p>Lastly, Egora is free to use and protected by Copyleft. If you are dissatisfied
                    with this Egora, you can create your own version of it. Egora welcomes all
                    competition. May the best ideas win!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
