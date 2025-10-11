@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Class Teachers</h4>
            </div><!-- end card header -->

            <div class="card-body">
                @include('partials.class_teachers',['branch_class_section_id' => Request::route('branch_class_section_id')])
            </div>
        </div>
    </div>

    <form class="needs-validation" method="POST" action="{{ route('class-teachers.store') }}" novalidate>
        @csrf
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Teacher List</h4>
                        <div class="form-check form-switch form-switch-md">
                            <input type="checkbox" class="form-check-input" name="is_class_incharge" {{!empty($data['class_has_incharge']) ? 'disabled' : ''}}>
                            <input type="hidden" name="branch_class_section_id" value="{{Request::route('branch_class_section_id')}}">
                            <label class="form-check-label">Class Incharge</label>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            @include('partials.teachers',['employees' => $data['employees'], 'from_branch_setup' => 1])

                            @if($data['employees']->isNotEmpty() && $data['subjects']->isNotEmpty())
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
                        <h4 class="card-title mb-0 flex-grow-1">Subjects</h4>
                        <select class="form-select" style="width: 50%" name="academic_year_id" aria-label="Academic year select" required>
                            <option value="">Academic year</option>
                            @foreach ($data['branch_academic_years'] as $branch_academic_year)
                                <option value="{{ $branch_academic_year['academic_year']['id'] }}">{{ $branch_academic_year['academic_year']['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-tooltip">
                            Academic Year is required!
                        </div>
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
