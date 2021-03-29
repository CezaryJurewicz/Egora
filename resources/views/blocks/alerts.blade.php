<div class="row">
    <div class="col-lg-12">
    @if ($errors->isNotEmpty())
        @foreach ($errors->all() as $error)
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span> </button>
            <strong><i class="fa fa-exclamation-circle"></i> {{ __('some.Error') }}</strong> {{ str_replace('errors.','', __('errors.'.$error))  }}
        </div>
        @endforeach
    @endif

    @if (session('success'))
        <div role="alert" class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span> </button>
            <strong><i class="fa fa-check-circle"></i> {{__('some.Success')}}</strong> @lang(session('success')) 
        </div>
    @endif

    @if (session('message'))
        <div role="alert" class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span> </button>
            <strong><i class="fa fa-info-circle"></i> </strong> @lang(session('message'))
        </div>
    @endif    
    </div>
</div>