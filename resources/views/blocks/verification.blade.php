                                    @if (((auth('web')->user() ?: auth('admin')->user())->can('update', $user) || (auth('web')->user() ?: auth('admin')->user())->can('verify', $user) ) && $user->verification_id)
                                    <h5 class="mb-1">{{ __('media.Verification ID')}}</h5>
                                    <div class="img-wrap">
                                        @if ((auth('web')->user() ?: auth('admin')->user())->can('update', $user) )
                                        <span class="close" title="Delete Image" onclick="event.preventDefault(); document.getElementById('verify-delete-form').submit();">&times;</span>
                                        @endif
                                        <a href="{{ route('users.verification_id_image', $user) }}">
                                            <img src="{{ Storage::url($user->verification_id->image->filename) }}"  width1="250" class="img-fluid img-thumbnail" alt=""> 
                                        </a>
                                    </div>
                                    
                                    <div class="mt-1">
                                        <form id="verify-delete-form" action="{{ route('passports.delete',$user->verification_id->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                        </form>
                                    </div>
                                    @endif

                                    @if ((auth('web')->user() ?: auth('admin')->user())->can('verify', $user) && !$user->user_type->verified)
                                    <div class="mt-3">
                                        <form action="{{ route('users.verify',$user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-danger btn-sm btn-block'>{{__('This account is authentic')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    @if ((auth('web')->user() ?: auth('admin')->user())->can('unverify', $user) && $user->user_type->verified)
                                    <div class="mt-3">
                                        <form action="{{ route('users.unverify',$user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <div class="input-group">
                                                <button type='submit' class='btn btn-black btn-sm btn-block'>{{__('Inauthenticate Account')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                    
                                @if(is_null($user->verification_id))
                                    @if (auth('web')->check() && auth('web')->user()->can('update', $user) && !$user->user_type->verified)
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <b>Your account is currently unvalidated – your Ideological Profile is not being counted in the Indexes.</b>
                                            <p>To validate your account, upload an image of your government ID (JPEG, JPG, or PNG format).  The ID number will be maintained in a secure record to prevent fraudulent activity. Your information will not be shared with anyone.</p>
                                            
                                            <form action="{{ route('media.verification') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="file" name="file" id="file" style="overflow: hidden;"/>
                                                    <input type="hidden" name="type" value="passport"/>
                                                </div>
                                                <button type='submit' class='btn btn-sm btn-primary mt-2'>{{__('some.Upload')}}</button>
                                            </form>
                                            
                                        </div>
                                    </div>
                                    @endif
                                @endif
