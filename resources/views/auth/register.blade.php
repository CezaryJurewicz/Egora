@extends('layouts.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-light text-center shadow-sm p-2 mb-3">
                Humanity,<br/>
                here all tyrants drown<br/>
                in the ocean of your collective wisdom.<br/>
                --------------------------------------------<br/>
                INTERNATIONAL LOGIC PARTY<br/>
                <br/>
                3-023
            </div>
            
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-6 row">
                                <label for="loginemail" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-8">
                                    <input id="loginemail" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6 row">
                                <label for="loginpassword" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-8">
                                    <input id="loginpassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-6 row mb-0 mr-0">
                                <div class="col-md-8 offset-md-4">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary col-md-2">
                                    {{ __('Log In') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container registration mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Registration') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-1">
                            <div class="text-center">
                                <h4>Egora User Agreement</h4>
                            </div>
                            <p>By this document, <u>&nbsp; &nbsp; <span id="regName">(your name)</span> &nbsp; &nbsp;</u>––hence forth referred to as the “user”––and 
                            the <i>International Logic Party</i>––the citizens and proprietors of the Republic of Egora (a.k.a. Egora), represented by the 
                            administration of Egora––enter into an agreement on the following terms.</p>
                        </div>
                        <div class="offset-1 col-md-10 pl-5 pr-5">
                        <ol>
                            <li>
                                <span class="summary">
                                    Egora is for real people only.
                                    <a data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse1">
                                    <div class="card card-body">
                                        <p>A person gains real political power by using Egora, and this power may not be abused. Therefore,
                                        everyone must use their real identity in Egora, represented by only one user profile. That being the
                                        case, the user consents to the administration of Egora collecting and storing the user’s intentionally
                                        provided personal information for the purposes of authenticating the user’s identity now and in the
                                        future.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    Egora will respect the user’s privacy.
                                    <a data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse2">
                                    <div class="card card-body">
                                        <p>The Egora administration will never investigate or track any of the user’s personal activity in Egora to
                                        exploit the user in any way nor to share that data with others. Furthermore, to protect the user's private personal 
                                        activity, we will not cooperate with anyone seeking to violate it, including other governments
                                        and their agencies.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    The user will work only to improve Egora, never to undermine it.
                                    <a data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse3">
                                    <div class="card card-body">
                                        <p>If the user is dissatisfied with the operations of Egora, they are welcome to try to change them through
                                        the established democratic processes of Egora. If the user finds those processes inadequate, the user is
                                        welcome to publish their own competing alternative to Egora.</p>
                                        <p>Never will the user work to compromise the security of Egora from within or from outside.
                                        Never will the user sabotage the efficiency of Egora by taking any action against it––including the
                                        administration of Egora––such as legal action through any external justice system.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    The user accepts responsibility for their own actions in using Egora.
                                    <a data-toggle="collapse" href="#collapse4" role="button" aria-expanded="false" aria-controls="collapse4">
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse4">
                                    <div class="card card-body">
                                        <p>The user accepts personal responsibility for their own actions while using Egora and pertaining to using
                                        Egora. For example, a user will not hold Egora responsible for the happenings of a real-life meeting
                                        event that was planned through Egora. Of course, this is not to say that a user cannot hold other 
                                        users/attendees responsible, as specified by the relevant local laws.</p>
                                        <p>Furthermore, the user agrees to abide by fair, local laws in using Egora. If the user is suspected
                                        of being in violation of such laws, all public information within Egora will be available for the
                                        prosecution of the user, and the Egora administration reserves the right to aid external agencies in
                                        collecting that information. However, if the International Logic Party agrees that certain laws are
                                        unfair, the International Logic Party may assist the user in resisting those laws.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    Ideas expressed in the user’s Ideological Profile are protected by COPYLEFT.
                                    <a data-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5">
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse5">
                                    <div class="card card-body">
                                        <p>The ideas of a user’s Ideological Profile in Egora are subject to copyleft, thus available to be restated
                                        and developed by anyone. Expressing even an original idea in one’s Ideological Profile does not imply
                                        taking credit for the origination of that idea nor claiming ownership of that idea.</p>
                                        <p>However, any specific concepts/plans/expressions/names included within the text of an idea that
                                        are externally protected by copyrights will remain as such. The user must always abide by the “fair
                                        use” doctrine protecting copyrighted works, despite an otherwise absolute freedom of speech within
                                        their Ideological Profile. The administration of Egora reserves the right to delete ideas from Egora if
                                        and only if they are in violation of the “fair use” doctrine.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    Ideas expressed in the user’s Ideological Profile will be used by the International Logic Party.
                                    <a data-toggle="collapse" href="#collapse6" role="button" aria-expanded="false" aria-controls="collapse6"> 
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse6">
                                    <div class="card card-body">
                                        <p>The ideas of users’ Ideological Profiles will be compiled by Egora into the Idea Dominance Index,
                                        thereby demonstrating which ideas are supported most strongly by the users of Egora. The
                                        <i>International Logic Party</i> will then nominate candidates for government offices around the world based
                                        on the ideas in the Idea Dominance Index. By using Egora you consent to participating in this political
                                        process, directly or indirectly.</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="summary">
                                    Violations of this agreement by the user will be penalized internally.
                                    <a data-toggle="collapse" href="#collapse7" role="button" aria-expanded="false" aria-controls="collapse7"> 
                                        <i class="fa fa-chevron-down pull-right"></i>
                                        <i class="fa fa-chevron-right pull-right"></i>
                                    </a>
                                </span>
                                <div class="collapse" id="collapse7">
                                    <div class="card card-body">
                                        <p>If the user is found to be in violation of this agreement, they will be prosecuted according to the
                                        specific procedures set by the administration of Egora, in accordance with this document.</p>
                                        <p>The most severe penalty that can be imposed upon the user by the administration of Egora for
                                        the violation of this agreement is being publicly exposed to the Egora community, and this penalty may
                                        be selected by the user in lieu of any other penalty imposed. In any case, no user will ever be banned
                                        from using Egora nor be restricted in any basic (non-ILP) functionality.</p>
                                    </div>
                                </div>
                            </li>                        
                        </ol>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-1">
                        
                        So agreed on <u> {{ (new \Carbon\Carbon())->format('d/m/Y') }} </u>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-10 offset-1 text-center">
                            <div class="row text-center">
                                <div class="col-md-5"><u>&nbsp; &nbsp; <span id="regName2">(your name)</span> &nbsp; &nbsp;</u></div>
                                <div class="col-md-2">and</div>
                                <div class="col-md-5 "><u>&nbsp; &nbsp; <i><b>International Logic Party</b></i> &nbsp; &nbsp;</u></div>
                            </div>
                        </div>
                    </div>
               </div>
                
                <form id="register" method="POST" action="{{ route('register') }}">
                    <div class="card-body">
                        @csrf

                        <div class="row">
                            <div class="form-group offset-1 col-md-10 row">
                            <label for="name" class="col-md-3 col-form-label">{{ __('Your Name') }}</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group offset-1 col-md-10 row">
                            <label for="nation" class="col-md-3 col-form-label">{{ __('Nation') }}</label>

                            <div class="col-md-8">
                                <div id="NationSearch" value="{{ old('nation') }}"></div>

                                @error('nation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group offset-1 col-md-10 row">
                            <label for="email" class="col-md-3 col-form-label">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('reg_email') is-invalid @enderror" name="reg_email" value="{{ old('reg_email') }}" required autocomplete="email">

                                @error('reg_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group offset-1 col-md-10 row">
                            <label for="password" class="col-md-3 col-form-label">{{ __('Create Password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group offset-1 col-md-10 row">
                            <label for="password-confirm" class="col-md-3 col-form-label">{{ __('Confirm Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary col-md-2">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
