@extends('layouts.master')

@section('title', 'Salary Management')

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line"></i> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-money-dollar-circle-line"></i> Employee Salary Management
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('payrolls.salary.create') }}" class="btn btn-primary">
                                <i class="ri-add-line"></i> Assign Salary
                            </a>
                            <a href="{{ route('payrolls.salary.bulk.create') }}" class="btn btn-success">
                                <i class="ri-group-line"></i> Bulk Assignment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="salary-table" class="table table-striped table-hover">
                            <thead class="table-default">
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Basic Salary</th>
                                    <th>Gross Salary</th>
                                    <th>Effective From</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
$(document).ready(function() {
    $('#salary-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('payrolls.salary.data') }}",
            type: 'GET'
        },
        columns: [
            { data: 'employee_name', name: 'employee_name' },
            { data: 'department', name: 'department_name' },
            { data: 'designation', name: 'designation_name' },
            { data: 'basic_salary', name: 'basic_salary' },
            { data: 'gross_salary', name: 'gross_salary' },
            { data: 'effective_from', name: 'effective_from' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading...",
            emptyTable: "No salary records found"
        }
    });
});
</script>
@endpush
