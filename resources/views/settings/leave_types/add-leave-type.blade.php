<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Leave Type</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('leave-type.store') }}" method="post">
                    @csrf

                    <div class="col-md-6 col-sm-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="Please enter name" value="{{ old('name') }}" required>
                            <label for="name" class="form-label">Name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @else
                                    Name is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="number" min="1" class="form-control @if($errors->has('no_of_days')) is-invalid @endif" id="no_of_days" name="no_of_days" placeholder="Please enter number of days" value="{{ old('no_of_days') }}" required>
                            <label for="no_of_days" class="form-label">Number&nbsp;of&nbsp;Days</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('no_of_days'))
                                    {{ $errors->first('no_of_days') }}
                                @else
                                    Number of days are required!
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-md-6 col-sm-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" placeholder="Please enter description" value="{{ old('description') }}" required>
                            <label for="description" class="form-label">Description</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @else
                                    Description is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
