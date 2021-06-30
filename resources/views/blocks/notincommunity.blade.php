                    @if( Auth::guard('web')->check() && !Auth::guard('web')->user()->can('like', $idea) && !is_null($idea->community) && isset($notification))
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="position1" class="col-md-2 col-form-label">{{ __('Assign Position:') }}</label>
                            <div class="col-md-8 pt-2">To support this idea you must first join the "{{ $idea->community->title }}" community</div>
                        </div>
                    </form>
                    </div>
                    @endif
