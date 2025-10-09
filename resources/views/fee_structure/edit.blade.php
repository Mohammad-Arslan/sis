@extends('layouts.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Edit Fee Structure</h4>
                    <a href="{{ route('fee_structure.index') }}" class="btn btn-danger">Back</a>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('fee_structure.update', $new_school_fee_structure->id) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <!-- Add your form fields here -->
                            <div class="form-section">
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="filter load-select form-select" id="state_id" name="state_id"
                                                placeholder="state" data-target="city_id"
                                                data-url="{{ route('list-cities') }}" aria-label="State select" disabled>
                                                <option value="">Please select</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $state->id == $new_school_fee_structure->state_id ? 'selected' : '' }}>
                                                        {{ $state->state_name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="state_id" class="form-label">Region</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select
                                                class="filter form-select @if ($errors->has('city_id')) is-invalid @endif"
                                                id="city" name="city_id" aria-label="City select" disabled>
                                                <option value="">Please select</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ $city->id == $new_school_fee_structure->city_id ? 'selected' : '' }}>
                                                        {{ $city->city_name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="city" class="form-label">City</label>
                                            {{-- <div class="invalid-tooltip">
                                            @if ($errors->has('city_id'))
                                                {{ $errors->first('city_id') }}
                                            @else
                                                City is required!
                                            @endif
                                        </div> --}}
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="filter form-select" id="academic_year_id_new_school"
                                                name="academic_year_id_new_school" disabled>
                                                <option value="">Please select</option>
                                                @foreach ($academic_years as $academic_year)
                                                    <option @if ($academic_year->id == $new_school_fee_structure->academic_year_id) selected @endif
                                                        value="{{ $academic_year->id }}">{{ $academic_year->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="academic_year_id_new_school" class="form-label">Academic
                                                Year</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="text" name="school_name" class="form-control" readonly
                                                value="{{ $new_school_fee_structure->school_name }}">
                                            <label class="form-label">Proposed School Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="text" name="school_address" class="form-control" readonly
                                                value="{{ $new_school_fee_structure->school_address }}">
                                            <label class="form-label">School Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="filter form-select" name="class_group_id" disabled>
                                                <option value="">Please select</option>
                                                @foreach ($classGroups as $classGroup)
                                                    <option
                                                        {{ $classGroup->id == $new_school_fee_structure->class_group_id ? 'selected' : '' }}
                                                        value="{{ $classGroup->id }}">{{ $classGroup->name }}</option>
                                                @endforeach
                                            </select>
                                            <label class="form-label">School Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="text" name="campus_area" class="form-control" readonly
                                                value="{{ $new_school_fee_structure->campus_area }}" required>
                                            <label class="form-label">Campus Area</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="date" name="date" class="form-control" readonly
                                                value="{{ $new_school_fee_structure->date }}" required>
                                            <label class="form-label">Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="text" name="remarks" class="form-control" readonly
                                                value="{{ $new_school_fee_structure->remarks }}" required>
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="mb-0 card-title flex-grow-1">Edit Fee Structure Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="text" name="nearest_bss_school"
                                                        id="nearest_bss_school" class="form-control" value=""
                                                        required>
                                                    <label class="form-label">Nearest BSS School</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <select class="filter form-select" id="academic_year_id"
                                                        name="academic_year_id">
                                                        <option value="">Please select</option>
                                                        @foreach ($academic_years as $academic_year)
                                                            <option @if ($academic_year->id == $fee_details->academic_year_id) selected @endif
                                                                value="{{ $academic_year->id }}">
                                                                {{ $academic_year->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="academic_year_id" class="form-label">Academic Year</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="fee_charges" id="fee_charges"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">BSS Fee Charges</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="school_fee" id="school_fee"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">Tution Fee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="admission_fee" id="admission_fee"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">Admission Fee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="security_fee" id="security_fee"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">Security Fee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="registration_fee" id="registration_fee"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">Registration Fee</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="final_fee_charges" id="final_fee_charges"
                                                        class="form-control" value="" required>
                                                    <label class="form-label">Final Fee Charges</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="number" name="round_final_fee_charges"
                                                        id="round_final_fee_charges" class="form-control" value=""
                                                        required>
                                                    <label class="form-label">Round Final Fee Charges</label>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4 col-sm-12">
                                            <div class="form-label-group in-border">
                                                <select class="filter form-select" name="fee_status_by_dd" required>
                                                    <option value="">Please select</option>
                                                    <option value="Approved"
                                                        {{ $record->fee_status_by_dd == 'Approved' ? 'selected' : '' }}>
                                                        Approved</option>
                                                    <option value="Pending"
                                                        {{ $record->fee_status_by_dd == 'Pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="Rejected"
                                                        {{ $record->fee_status_by_dd == 'Rejected' ? 'selected' : '' }}>
                                                        Rejected</option>
                                                </select>
                                                <label class="form-label">Fee Status by DD</label>
                                            </div>
                                        </div> --}}
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="date" name="approval_date" class="form-control"
                                                        value="" required>
                                                    <label class="form-label">Created On</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <input type="text" name="fee_details_remarks" class="form-control" va>
                                                    <label class="form-label">Remarks</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                            {{-- <a href="{{ route('fee_structure.index') }}" class="btn btn-danger">Back</a> --}}
                                            <button type="submit" class="btn btn-primary">Add Fee Structure
                                                Options</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between card-header">
                <h4 class="mb-0 card-title flex-grow-1">Fee Options List</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="fee-structure-table"
                            class="table mb-0 align-middle table-bordered table-striped table-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>BSS Campus</th>
                                    <th>Academic Year</th>
                                    <th>School Type</th>
                                    <th>Registration Fee</th>
                                    <th>Fee Charges</th>
                                    <th>Security Fee</th>
                                    <th>Admission Fee</th>
                                    <th>Final Fee</th>
                                    <th>Final Fee (Rounded)</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($new_school_fee_structure->new_fee_structure_details as $record)
                                    <tr>
                                        <td>{{ $record->nearest_bss_school }}</td>
                                        <td>{{ $record->academic_years->title }}</td>
                                        <td>{{ $record->new_school_fee_structure->class_group->name }}</td>
                                        <td>{{ $record->registration_fee }}</td>
                                        <td>{{ $record->fee_charges }}</td>
                                        <td>{{ $record->security_fee }}</td>
                                        <td>{{ $record->admission_fee }}</td>
                                        <td>{{ $record->final_fee_charges }}</td>
                                        <td>{{ $record->round_final_fee_charges }}</td>
                                        <td><select data-id="{{ $record->id }}" name="fee_status_by_dd"
                                                class="fee_status_by_dd form-control">
                                                <option value="Pending"
                                                    {{ $record->fee_status_by_dd == 'Pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="Approved"
                                                    {{ $record->fee_status_by_dd == 'Approved' ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="Rejected"
                                                    {{ $record->fee_status_by_dd == 'Rejected' ? 'selected' : '' }}>
                                                    Rejected</option>
                                            </select>
                                        </td>
                                        <td>{{ $record->remarks }}</td>
                                        <td>

                                            {{-- <a href="{{ route('fee_structure.complete_edit', $record->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}
                                            <form action="{{ route('fee_structure.destroy', $record->id) }}"
                                                method="post" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on('change', '.fee_status_by_dd', function() {
                var id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "fee-structures/change-status",
                    data: {
                        id: id,
                        value: $(this).val()
                    },

                    success: function(response) {
                        // Handle the response from the server
                        if (response.success == true) {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(error);
                    }
                });
            });
        });
    </script>
    {{-- <script>
    $(document).ready(function () {
        // Calculate the Final Fee Charges whenever any of the relevant fields change
        $('#fee_charges, #school_fee, #admission_fee, #security_fee, #registration_fee').on('input', function () {
            var feeCharges = parseFloat($('#fee_charges').val()) || 0;
            var schoolFee = parseFloat($('#school_fee').val()) || 0;
            var admissionFee = parseFloat($('#admission_fee').val()) || 0;
            var securityFee = parseFloat($('#security_fee').val()) || 0;
            var registrationFee = parseFloat($('#registration_fee').val()) || 0;

            var finalFeeCharges = feeCharges + schoolFee + admissionFee + securityFee + registrationFee;
            $('#final_fee_charges').val(finalFeeCharges.toFixed(2));
        });
    });
</script> --}}
@endpush
