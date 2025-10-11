@extends('layouts.master')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Class Section List</h4>
            </div><!-- end card header -->

            <div class="card-body">
                @php($route_parameter = $data['user_type'] == 'student' ? [ 'from_branch_setup_student' => 1 ] : ($data['user_type'] == 'teacher' ? [ 'from_branch_setup_teacher' => 1 ] : array()))
                @include('partials.class_sections',['table_id' => 'branch-class-section-data-table','route_parameters' => $route_parameter])
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
@endpush
