@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Create a role</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('roles.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('roles.store') }}" method="POST"  novalidate>
                        @csrf
                        <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="Role Name" value="" readonly required>
                                <label for="name" class="form-label">Role name</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('name'))
                                    {{ $errors->first('name') }}
                                    @else
                                    Role name is required!
                                    @endif
                                </div>
							</div>
                        </div>
                        <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('display_name')) is-invalid @endif" id="display_name" name="display_name" placeholder="Role Display Name" value=""  required>
                                <label for="display_name" class="form-label">Role display name</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('display_name'))
                                    {{ $errors->first('display_name') }}
                                    @else
                                    Role display name is required!
                                    @endif
                                </div>
							</div>
                        </div>

                        <div class="col-md-12 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" placeholder="Description" value=""  required>
                                <label for="description" class="form-label">Role display name</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('description'))
                                    {{ $errors->first('description') }}
                                    @else
                                    Role display name is required!
                                    @endif
                                </div>
							</div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>

                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Create role</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @endsection


        @push('header_scripts')


        @endpush

        @push('footer_scripts')

            <script type="text/javascript">
                $(function() {
                    $("#display_name").on("focusout", function() {
                        var display_name = $(this);
                        $("#name").val(display_name.val().split(" ").join("_").toLowerCase());
                    });
                });
            </script>

    @endpush
