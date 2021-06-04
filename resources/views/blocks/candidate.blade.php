@if (auth('web')->check() && auth('web')->user() && $user->campaign)
<div class="panel mt-3">
    <div class="panel-body">
        <h5 class="mb-2">Political Candidate</h5>
        <p><b>{{ __('Administrative Level:') }}</b><br/>
        @if ($user->campaign->subdivision)
        Subdivision {{$user->campaign->order}} - {{ $user->campaign->subdivision->title }}
        @else
        Head of State
        @endif
        </p>
    </div>
</div>
@endif