                            @if($user->image)
                                <img src="{{ Storage::url($user->image->filename) }}" class="img-fluid img-thumbnail" alt=""> 
                                
                                @if (auth('web')->check() && auth('web')->user()->can('delete', $user->image))
                                <div class="mt-1">
                                    <form action="{{ route('media.delete',$user->image->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <div class="input-group">
                                            <button type='submit' class='btn btn-sm btn-warning btn-block'>{{__('some.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                            @else
                                @if (auth('web')->check() && auth('web')->user()->can('create', [App\Media::class, $user]) )
                                <form action="{{ route('media.store', $user) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="file" id="file"/>
                                        <input type="hidden" name="type" value="image"/>
                                    </div>
                                    <button type='submit' class='btn btn-sm btn-primary mt-2'>{{__('some.Upload')}}</button>
                                </form>
                                @endif
                            @endif          