@if ($user->campaign)
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
        <p style="word-wrap: break-word;"><b>{{ __('Visitor URL:') }}</b><br/>
        {{ _url_replace(route('users.vote_ip', _clean_search_name($user->active_search_name)),1) }}
        {{ _clean_search_name($user->active_search_name) }}
        </p>
        <div class="mt-2">
            <div class="simpleCopy" btn_title="{{ __('Copy Link') }}" value="{{ _url_replace(route('users.vote_ip', _clean_search_name($user->active_search_name))) }}" btn_class="btn btn-sm btn-primary btn-block"></div>
        </div>
    </div>
</div>
@endif
