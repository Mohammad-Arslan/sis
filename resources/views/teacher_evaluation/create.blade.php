@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Add Teacher Details </h5>

                {{-- Display Success Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Display Error Messages --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Display Warning Messages --}}
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <form action="{{ route('teacher_evaluation.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="branch_id">Branch:</label>
                        <select name="branch_id" class="form-control @error('branch_id') is-invalid @enderror" id="branchSelect" 
                                style="background:white" {{ !$isSuperAdmin ? 'disabled' : '' }}>
                            @if($isSuperAdmin)
                                <option value="">Please select a branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if ($branch->id == old('branch_id')) selected @endif>
                                        {{ $branch->br_name }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" selected>
                                        {{ $branch->br_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if(!$isSuperAdmin)
                            <input type="hidden" name="branch_id" value="{{ $selectedBranch->id }}">
                        @endif
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="employee_id">Teacher:</label>
                        <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                            <option value="">Please select a teacher</option>
                            @if(isset($employees) && $employees->count() > 0)
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @if (old('employee_id') == $employee->id) selected @endif>{{ $employee->preferred_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="evaluation_user_id">Evaluator:</label>
                        <input type="hidden" name="evaluation_user_id" value="{{ Auth::user()->id }}">
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly
                            style="background:white">
                    </div>

                    <div class="d-flex justify-content-end" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('teacher_evaluation.index') }}" class="btn btn-success"
                            style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('footer_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var branchSelect = document.getElementById("branchSelect");
            var teacherSelect = document.querySelector('select[name="employee_id"]');
            var isSuperAdmin = {{ $isSuperAdmin ? 'true' : 'false' }};

            // For non-super_admin users, disable branch selection
            if (!isSuperAdmin) {
                branchSelect.style.pointerEvents = "none";
                branchSelect.style.background = "lightgray";
                
                // Prevent keypresses from opening the dropdown
                branchSelect.addEventListener("keydown", function(e) {
                    e.preventDefault();
                });
            } else {
                // For super_admin users, handle branch change
                branchSelect.addEventListener("change", function() {
                    var branchId = this.value;
                    var teacherSelect = document.querySelector('select[name="employee_id"]');
                    
                    // Clear existing options except the first one
                    teacherSelect.innerHTML = '<option value="">Please select a teacher</option>';
                    
                    if (branchId) {
                        // Show loading state
                        teacherSelect.innerHTML = '<option value="">Loading teachers...</option>';
                        teacherSelect.disabled = true;
                        
                        // Fetch teachers for selected branch
                        fetch(`/teacher_evaluation/teachers/${branchId}`)
                            .then(response => response.json())
                            .then(teachers => {
                                teacherSelect.innerHTML = '<option value="">Please select a teacher</option>';
                                
                                teachers.forEach(function(teacher) {
                                    var option = document.createElement('option');
                                    option.value = teacher.id;
                                    option.textContent = teacher.preferred_name;
                                    teacherSelect.appendChild(option);
                                });
                                
                                teacherSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Error fetching teachers:', error);
                                teacherSelect.innerHTML = '<option value="">Error loading teachers</option>';
                                teacherSelect.disabled = false;
                            });
                    }
                });
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('alert-danger') || alert.classList.contains('alert-warning')) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.remove();
                        }, 500);
                    }
                });
            }, 5000);
        });
    </script>
@endpush
