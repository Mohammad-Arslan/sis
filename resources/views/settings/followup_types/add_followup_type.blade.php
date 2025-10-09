<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Follow Up Type</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('followUpType.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('follow_up_type')) is-invalid @endif" name="follow_up_type" id="follow_up_type" placeholder="Please enter town name" value="{{old('follow_up_type')}}" required>
                            <label for="follow_up_type" class="form-label">Follow up type</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('follow_up_type'))
                                {{ $errors->first('follow_up_type') }}
                                @else
                                Follow Up Type is required!
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
