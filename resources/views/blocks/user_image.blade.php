                            @if($user->image)
                            <img src="{{ Storage::url($user->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 

                                @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                <div class="mt-3">
                                    <form action="{{ route('media.delete',$user->image->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <div class="input-group">
                                            <button type='submit' class='btn btn-sm btn-warning'>{{__('some.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                            @else
                                @if (auth('web')->user() && $user->id == auth('web')->user()->id)
                                <h5 class="mb-1">{{ __('media.Upload your photo')}}</h5>

                                <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="file" id="file"/>
                                        <input type="hidden" name="type" value="image"/>
                                        <button type='submit' class='btn btn-sm btn-primary'>{{__('some.Upload')}}</button>
                                    </div>
                                </form>
                                @endif
                            @endif          