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
        @if ($user->campaign->party)
        <p><b>Party Affiliation:</b><br/>
        {{ ($user->campaign->party ? $user->campaign->party->title : null) }}
        </p>
        @elseif ($user->user_type->isIlp)
        <p><b>Party Affiliation:</b><br/>
        International Logic Party
        </p>
        @endif
    </div>
</div>
@endif