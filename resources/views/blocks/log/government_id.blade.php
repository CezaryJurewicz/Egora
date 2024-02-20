                    @if (auth('web')->check() && auth('web')->user()->government_id && auth('web')->user()->government_id->status == 'rejected')
                        <div class="card-body">
                            <div id="nid" class="mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        The image of your government ID that you submitted with your ILP Member Declaration was not accepted. Please submit another one.
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <div class="row">
                                        <div class="col-3 text-left">
                                            <form action="{{ route('users.government_id.delete') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                <button type='submit' class='btn btn-primary btn-sm'>{{__('Delete')}}</button>
                                            </form>
                                        </div>
                                        <div class="col-3 text-right offset-6">
                                            <form action="{{ route('users.government_id.reupload') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <button type='submit' class='btn btn-primary btn-sm'>{{__('Upload ID')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif