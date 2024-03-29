                            @if($user->image)
                                <div class="img-wrap">
                                @if (auth('web')->check() || auth('admin')->check())
                                    @if ((auth('web')->user()?:auth('admin')->user())->can('delete', $user->image))
                                    <span class="close" title="Delete Image" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">&times;</span>
                                    @endif
                                @endif
                                    <img src="{{ Storage::url($user->image->filename) }}" class="img-fluid img-thumbnail" alt=""> 
                                </div>
                                
                                @if (auth('web')->check() || auth('admin')->check())
                                @if ((auth('web')->user()?:auth('admin')->user())->can('delete', $user->image))
                                <form id="delete-form" action="{{ route('media.delete',$user->image->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE"/>
                                </form>
                                @endif
                                @endif
                            @else
                                @if (auth('web')->check() && auth('web')->user()->can('create', [App\Media::class, $user]) )
                                <small>
                                    If your profile image depicts people realistically, it should only be an image of you, the owner of the account. If your torso is depicted, it must be covered.
                                </small>
                                <form action="{{ route('media.store', $user) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="file" id="file" style="overflow: hidden;"/>
                                        <input type="hidden" name="type" value="image"/>
                                    </div>
                                    <button type='submit' class='btn btn-sm btn-primary mt-2'>{{__('some.Upload')}}</button>
                                </form>
                                @endif
                            @endif          