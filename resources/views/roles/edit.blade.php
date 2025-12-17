@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Editing Role: {{ $role->name }} </h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('roles.index') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('roles.update',isset($role) ? $role : '') }}" method="POST"  novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="Role Name" value="{{ $role->name }}" readonly required>
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
                                <input type="text" class="form-control @if($errors->has('display_name')) is-invalid @endif" id="display_name" name="display_name" placeholder="Permission Display Name" value="{{ $role->display_name }}"  required>
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
                                <input type="text" class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" placeholder="Description" value="{{ $role->description }}"  required>
                                <label for="description" class="form-label">Role description</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('description'))
                                    {{ $errors->first('description') }}
                                    @else
                                    Role description is required!
                                    @endif
                                </div>
							</div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card mb-3">
                                            <div class="card-header">Permissions</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    {{-- @foreach($permissions as $permission)
                                                    <div class="col-xl-3 col-md-6">
                                                        <div class="card card-animate">
                                                            <div class="card-body" data-aos="flip-left">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1">
                                                                        <p class="fw-medium text-muted mb-0">{{ $permission->name }}</p>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <h5 class="text-success fs-14 mb-0">
                                                                            <input type="checkbox" class="form-checkbox h-4 w-4" name="permissions[]" value="{{$permission->id}}"
                                                                            {!! $permission->assigned ? 'checked' : '' !!}
                                                                            >
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                                    <div>
                                                                        <small class="text-muted">{{ $permission->description }}</small>
                                                                    </div>
                                                                    <div class="avatar-sm flex-shrink-0">
                                                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                                                            <i class="bx bx-key text-success"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    @endforeach --}}

                                                    <table id="tree-table" class="table table-hover table-bordered table-responsive">
                                                        <tbody>
                                                          <th>Permissions</th>
                                                          <th width="8%" class="text-center">Action</th>
                                                          @php
                                                            $i = 1
                                                          @endphp
                                                          @foreach($permissions as $permission)
                                                            <tr data-id="{{ $i }}" data-parent="0" data-level="1">
                                                                <td class="fw-bold" data-column="name"> <i class="ri-file-transfer-fill"></i> {{ $permission->name }}</td>
                                                                <td class="align-items-center">
                                                                    <div class="form-check form-switch form-switch-md text-center" dir="ltr">
                                                                        <input type="checkbox" class="form-check-input" name="{{ $permission->name }}" id="{{ str_replace(' ', '',strtolower($permission->name)) }}" onclick="gotoSelection('{{ str_replace(' ', '',strtolower($permission->name)) }}')" {!! $permission->assigned ? 'checked' : '' !!}>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @foreach ($permission->modules_permission as $permissionDetail)
                                                                <tr data-id="{{ $permissionDetail->id }}" data-parent="{{ $i }}" data-level="2">
                                                                    <td data-column="name">{{ $permissionDetail->name }}</td>
                                                                    <td class="align-items-center">
                                                                        <div class="form-check form-switch form-switch-md text-center" dir="ltr">
                                                                            <input type="checkbox" class="form-check-input  {{ str_replace(' ', '',strtolower($permission->name)) }}" name="permissions[]" value="{{$permissionDetail->id}}"
                                                                            {!! $permissionDetail->assigned ? 'checked' : '' !!}
                                                                            >
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @php
                                                                $i++;
                                                            @endphp
                                                          @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        <div class="border mt-3 border-dashed"></div>

                        <div class="col-12 text-end">
                            @if (isset($role))
                            <button class="btn btn-primary" type="submit">Update role</button>
                            @endif
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

                function gotoSelection(chkbox){
                    $(document).ready(function () {
                        $("#"+chkbox).change(function () {
                            $("."+chkbox).prop('checked', $(this).prop("checked"));
                        });
                    });
                }

            </script>

    @endpush
