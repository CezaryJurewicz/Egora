                                @if (auth('web')->check() && auth('web')->user()->id == $user->id)
                                    @if ($user->user_type->isIlp)
                                    <div class="text-center">
                                        <a href="{{ route('ilp.menu') }}">
                                            <img  width="200px" src='{{ asset('img/ILP_logo.jpg') }}' title="ILP functionalities">
                                        </a>
                                    </div>
                                    @elseif ($user->can('submit_application', $user))
                                    <a class="btn btn-sm btn-primary btn-block btn-ilp" href="{{ route('ilp.index') }}">ILP</a>
                                    @endif
                                @elseif ($user->user_type->class !== 'user' && !$user->user_type->former)
                                    <div class="text-center">
                                        <img  width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                                    </div>
                                @endif
                                
                                @if (auth('web')->check() && auth('web')->user()->id == $user->id)
                                <div class="mt-2">
                                    @if (auth('web')->user() && auth('web')->user()->government_id && auth('web')->user()->government_id->status == 'submitted')
                                        <small>If your government ID is rejected, you will receive a notification in your Inbox.</small>
                                    @endif
                                </div>
                                @endif
                                
                                @if ($user->user_type->class == 'member' && $user->user_type->candidate && (auth('web')->user()?:auth('admin')->user())->can('accept_application', $user))
                                    <div class="text-center mt-2">
                                        <a class="btn btn-block btn-primary btn-sm" href="{{ route('ilp.accept_application', $user->id) }}">{{ __('Accept Application')}}</a>
                                    </div>
                                @endif
