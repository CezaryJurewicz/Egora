                                @if($user->verification_id)
                                
                                    @if ((auth('web')->user() ?: auth('admin')->user())->can('update', $user) )
                                    <h5 class="mb-1">{{ __('media.Verification ID')}}</h5>
                                    <div class="img-wrap">
                                        <span class="close" title="Delete Image" onclick="event.preventDefault(); document.getElementById('verify-delete-form').submit();">&times;</span>
                                        <img src="{{ Storage::url($user->verification_id->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 
                                    </div>
                                    
                                    <div class="mt-1">
                                        <form id="verify-delete-form" action="{{ route('passports.delete',$user->verification_id->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                        </form>
                                    </div>
                                    @endif

                                    @if (auth('admin')->user() && !$user->user_type->verified)
                                    <div class="mt-3">
                                        <form action="{{ route('users.verify',$user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-warning'>{{__('some.Verify')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    @if (auth('admin')->user() && $user->user_type->verified)
                                    <div class="mt-3">
                                        <form action="{{ route('users.unverify',$user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-warning'>{{__('some.Unverify')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                @else
                                    @if (auth('web')->check() && auth('web')->user()->can('update', $user) && !$user->user_type->verified)
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <b>Your account is currently unverified</b>
                                            <p>Please upload your ID here</p>
                                            
                                            <form action="{{ route('media.verification') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="file" name="file" id="file"/>
                                                    <input type="hidden" name="type" value="passport"/>
                                                </div>
                                                <button type='submit' class='btn btn-sm btn-primary mt-2'>{{__('some.Upload')}}</button>
                                            </form>
                                            
                                        </div>
                                    </div>
                                    @endif
                                @endif
