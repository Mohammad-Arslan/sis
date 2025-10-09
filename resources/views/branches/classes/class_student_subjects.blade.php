@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <form method="POST" action="{{ route('class-student-subjects.store') }}">
        @csrf
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Students</h4>
                    </div><!-- end card header -->
                    <input type="hidden" name="branch_class_section_id" value="{{Request::route('branch_class_section_id')}}">

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            @include('partials.class_students',['class_students' => $data['class_students'], 'from_branch_setup' => 1])

                            @if($data['class_students']->isNotEmpty() && $data['subjects']->isNotEmpty())
                                <div class="border mt-3 border-dashed"></div>
                                <div class="col-12 text-end p-3">
                                    <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                                </div>
                            @endif
                        </div><!-- end table responsive -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Subject List</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            @include('partials.subjects',['subjects' => $data['subjects']])

                        </div><!-- end table responsive -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
        </div>
    </form>

@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
@endpush
