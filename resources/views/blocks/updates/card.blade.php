                                    <div class="card mb-3">
                                        <div class="card-header" style="padding: .1rem 1rem; background-color: {{  bg_color_by_egora_id($eid) }} !important;">
                                            &nbsp;
                                        </div>
                                        <div class="card-body">

                                        <div class="mb-3">
                                            <form action="{{ route('updates.delete_filtered') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                <input type="hidden" name="filter" value="{{$filter}}"/>
                                                <input type="hidden" name="egora_id" value="{{$eid}}"/>
                                                <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete All')}}</button>
                                            </form>
                                        </div>

                                        @forelse($rows as $line)
                                            @if ($line->updatable)
                                                @if ($line->type == 'status')
                                                    @include('blocks.updates.status', ['row' => $line, 'id' => $id])   
                                                @elseif ($line->type == 'follower')
                                                    @include('blocks.updates.follower', ['row' => $line, 'id' => $id])   
                                                @elseif (($line->type == 'comment') || ($line->type == 'subcomment'))
                                                    @include('blocks.updates.comment', ['row' => $line, 'id' => $id])   
                                                @elseif ($line->type == 'idea')
                                                    @include('blocks.updates.idea', ['row' => $line, 'id' => $id])   
                                                @endif
                                            @endif
                                        @empty
                                        @endforelse   
                                        </div>
                                    </div>