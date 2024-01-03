                <div class="card mb-3">
                    <div class="card-header" style="padding: .1rem 1rem; background-color: {{  bg_color_by_egora_id($eid) }} !important;">
                        &nbsp;
                    </div>
                    <div class="card-body">

                        @forelse($lines as $line)
                        @if ($line->loggable instanceof \App\Notification)   
                            @include('blocks.log.notification', ['row' => $line->loggable])   
                        @elseif ($line->loggable instanceof \App\CommentNotification)   
                            @include('blocks.log.comment', ['row' => $line->loggable])   
                        @endif
                    
                    @empty
                        <!--<p>@lang('ideas.No ideas found')</p>-->
                    @endforelse
                    
                    </div>
                </div>