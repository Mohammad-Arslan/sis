@extends('layouts.master')

@section('content')
@include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Role Assignment</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('roles-permission-assignment-list') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('assign-role-permissions',isset($user->id) ? $user->id : '') }}" method="POST"  novalidate>
                        @csrf
                        <div class="col-md-12 col-sm-12">
							<div class="form-label-group in-border">
                                <input type="text" class="form-control"  value="{{ $user->name }}" disabled>
                                <label for="name" class="form-label">User name</label>
							</div>
                        </div>
                        <div class="border mt-3 border-dashed"></div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header">Roles</div>
                                    <div class="card-body">
                                        <div class="row justify-content-left">
                                        @foreach($roles as $role)
                                        <div class="col-xl-3 col-md-6">
                                            <!-- card -->
                                            <div class="card card-animate" >
                                                <div class="card-body" data-aos="flip-up">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <p class="fw-medium text-muted mb-0">{{ $role->name }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h5 class="text-success fs-14 mb-0"><div class="form-check form-switch form-switch-md text-center" dir="ltr">
                                                                <input type="checkbox"
                                                                @if ($role->assigned && !$role->isRemovable)
                                                                    class="form-check-input"
                                                                @else
                                                                    class="form-check-input h-4 w-4"
                                                                @endif
                                                                name="roles[]" value="{{$role->id}}"
                                                                {!! $role->assigned ? 'checked' : '' !!}
                                                                {!! $role->assigned && !$role->isRemovable ? 'onclick="return false;"' : '' !!}
                                                                ></div>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                        <div>
                                                            {{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $role->display_name }}</h4> --}}
                                                            <small class="text-fade">{{ $role->description }}</small>
                                                        </div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                                                <i class="bx bx-user-circle text-warning"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="border mt-3 mb-3 border-dashed"></div>
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


                        <div class="col-12 text-end">
                            @if (isset($user->id))
                            <button class="btn btn-primary" type="submit">Update</button>
                            @endif
                            <a href="{{ route('roles-permission-assignment-list') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
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
