        @if (auth('web')->check() && auth('web')->user()->following->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <ul id="tabs" class="nav nav-pills nav-fill" data-tabs="tabs">
                    <li class="nav-item active"><a class="nav-link active" href="#leadsTab" data-toggle="tab">
                        @lang('Leads') ({{ auth('web')->user()->following->count() }})
                    </a></li>
                    <li class="nav-item active"><a class="nav-link" href="#followersTab" data-toggle="tab">
                        @lang('Followers') ({{ auth('web')->user()->followers->count() }})    
                    </a></li>
                </ul>
            </div>
            <div class="card-body">
                <div id="my-tab-content" class="tab-content">
                    <div class="tab-pane active" id="leadsTab">
                        @foreach(auth('web')->user()->following->sortBy('active_search_name') as $u)
                        @if($u->active_search_names->first())
                        <div class="row col-md-12">
                        <a @if ($u->last_online_at->diffInDays(now()) > 23) style="color: #838383;" @endif href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                        {{ $u->active_search_name ?? $u->id }}
                        </a>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="tab-pane" id="followersTab">
                        @foreach(auth('web')->user()->followers->sortBy('active_search_name') as $u)
                        @if($u->active_search_names->first())
                        <div class="row col-md-12">
                        <a @if ($u->last_online_at->diffInDays(now()) > 23) style="color: #838383;" @endif href="{{ route('users.ideological_profile', $u->active_search_name_hash) }}">
                        {{ $u->active_search_name ?? $u->id }}
                        </a>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        
