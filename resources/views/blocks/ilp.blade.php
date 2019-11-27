                                @if (auth('web')->check() && auth('web')->user()->id == $user->id)
                                    @if ($user->user_type->isIlp)
                                    <div class="text-center">
                                        <a href="{{ route('ilp.menu') }}">
                                            <img  width="200px" src='{{ asset('img/ILP_logo.jpg') }}' title="ILP functionalities">
                                        </a>
                                    </div>
                                    @else
                                    <a class="btn btn-sm btn-primary btn-block" style="background-color: #0e004e; border-color: #0e004e;" href="{{ route('ilp.index') }}">ILP</a>;
                                    @endif
                                @else
                                    <div class="text-center">
                                        <img  width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                                    </div>
                                @endif
                                
                                @if ($user->user_type->class == 'member' && $user->user_type->candidate && (auth('web')->user()?:auth('admin')->user())->can('accept_application', $user))
                                    <div class="text-center mt-2">
                                        <a class="btn btn-block btn-primary" href="{{ route('ilp.accept_application', $user->id) }}">{{ __('Accept Application')}}</a>
                                    </div>
                                @endif
