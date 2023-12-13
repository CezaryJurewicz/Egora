            <div class="card mb-3">
                <div class="card-header"><b>Egora</b> Philosophers</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><b>International Logic Party</b> Members</div>
                <div class="card-body text-center">
                    All Nations - {{ $total_verified_ipl_users }}<br/>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><b>International Logic Party</b> Members by Nation</div>
                <div class="card-body text-center">
                @foreach($group_by_nation as $nation)
                {{ $nation->title }} - {{ $nation->users_count }}<br/>
                @endforeach
                </div>
            </div>