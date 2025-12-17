@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit a permission</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('permissions.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('permissions.update',isset($permission->id) ? $permission->id : '') }}" method="POST"  novalidate>
                        @csrf
                        @method('PATCH')
                        <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="Permission Name" value="{{ $permission->name }}" readonly  required>
                                <label for="name" class="form-label">Permission name</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('name'))
                                    {{ $errors->first('name') }}
                                    @else
                                    Permission name is required!
                                    @endif
                                </div>
							</div>
                        </div>
                        <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('display_name')) is-invalid @endif" id="display_name" name="display_name" placeholder="Permission Display Name" value="{{ $permission->display_name }}"  required>
                                <label for="display_name" class="form-label">Permission display name</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('display_name'))
                                    {{ $errors->first('display_name') }}
                                    @else
                                    Permission display name is required!
                                    @endif
                                </div>
							</div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select mb-3" id="system_module_id" name="system_module_id" required>
                                    <option value="" disabled selected>Select Modules</option>
                                    @if (isset($modules))
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->id }}"" @if ($permission->system_module_id == $module->id) selected @endif>{{ $module->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="system_module_id" class="form-label">System Module</label>
                                <div class="invalid-tooltip">System Module is required!</div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" placeholder="Description" value="{{ $permission->description }}"  required>
                                <label for="description" class="form-label">Permission descrition</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('description'))
                                    {{ $errors->first('description') }}
                                    @else
                                    Permission description is required!
                                    @endif
                                </div>
							</div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>

                        <div class="col-12 text-end">
                            @if (isset($permission->id))
                            <button class="btn btn-primary" type="submit">Update permission</button>
                            @endif
                            <a href="{{ route('permissions.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
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
                        $("#name").val(display_name.val().split(" ").join("-").toLowerCase());
                    });
                });

            </script>

    @endpush
