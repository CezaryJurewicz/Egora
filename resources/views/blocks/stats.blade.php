            <div class="card mb-3">
                <div class="card-header">Egora Users</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">ILP Members</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_ipl_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">ILP Members by Nation</div>
                <div class="card-body text-center">
                @foreach($group_by_nation as $nation)
                {{ $nation->title }} - {{ $nation->users->count() }}<br/>
                @endforeach
                </div>
            </div>