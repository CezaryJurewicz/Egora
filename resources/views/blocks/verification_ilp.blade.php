                                @if (auth('admin')->check())
                                    @if (((auth('web')->user() ?: auth('admin')->user())->can('update', $user) || (auth('web')->user() ?: auth('admin')->user())->can('verify', $user) ) && $user->government_id && $user->government_id->status == 'submitted')
                                        <h5 class="mb-1">{{ __('media.Verification ID')}}</h5>
                                        <div class="img-wrap">
                                            <a href="{{ route('users.government_id_image', $user) }}">
                                                <img src="{{ Storage::url($user->government_id->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 
                                            </a>
                                        </div>

                                        <div class="mt-1">
                                            <form id="verify-delete-form" action="{{ route('passports.delete',$user->government_id->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE"/>
                                            </form>
                                        </div>

                                        @if ((auth('web')->user() ?: auth('admin')->user())->can('verify', $user) && $user->government_id->status == 'submitted')
                                        <div class="mt-3">
                                            <form action="{{ route('users.accept_id',$user->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="_method" value="PUT"/>
                                                <div class="input-group">
                                                    <button type='submit' class='btn btn-danger btn-sm btn-block'>{{__('Accept ID')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <form action="{{ route('users.reject_id',$user->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                <div class="input-group">
                                                    <button type='submit' class='btn btn-black btn-sm btn-block'>{{__('Reject ID')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                    @endif
                                @endif
