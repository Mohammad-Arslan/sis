@extends('layouts.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between card-header">
                    <h4 class="mb-0 card-title flex-grow-1">Add New Fee Structures</h4>
                    <a href="{{ route('fee_structure.index') }}" class="btn btn-danger">Back</a>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('fee_structure.store') }}">
                        @csrf
                        <div class="form-section">
                            <hr>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select
                                            class="filter load-select form-select @error('state_id') is-invalid @enderror"
                                            id="state_id" name="state_id" data-target="city_id"
                                            data-url="{{ route('list-cities') }}" required>
                                            <option value="">Please select</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                    {{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="state_id" class="form-label">Region</label>
                                        @error('state_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="filter form-select @error('city_id') is-invalid @enderror"
                                            id="city" name="city_id" required>
                                            <option value="">Please select</option>
                                            @if (old('state_id'))
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                        {{ $city->city_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="city" class="form-label">City</label>
                                        @error('city_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select
                                            class="filter form-select @error('academic_year_id_new_school') is-invalid @enderror"
                                            id="academic_year_id_new_school" name="academic_year_id_new_school">
                                            <option value="">Please select</option>
                                            @foreach ($academic_years as $academic_year)
                                                <option value="{{ $academic_year->id }}"
                                                    {{ old('academic_year_id_new_school', $academic_year->active == 1 ? $academic_year->id : '') == $academic_year->id ? 'selected' : '' }}>
                                                    {{ $academic_year->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="academic_year_id_new_school" class="form-label">Academic Year</label>
                                        @error('academic_year_id_new_school')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @php
                                    $inputs = [
                                        ['name' => 'school_name', 'label' => 'Proposed School Name', 'type' => 'text'],
                                        ['name' => 'school_address', 'label' => 'School Address', 'type' => 'text'],
                                        ['name' => 'campus_area', 'label' => 'Campus Area', 'type' => 'text'],
                                        ['name' => 'date', 'label' => 'Date', 'type' => 'date'],
                                        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'text'],
                                    ];
                                @endphp

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="filter form-select @error('class_group_id') is-invalid @enderror"
                                            name="class_group_id" required>
                                            <option value="">Please select</option>
                                            @foreach ($classGroups as $classGroup)
                                                <option value="{{ $classGroup->id }}"
                                                    {{ old('class_group_id') == $classGroup->id ? 'selected' : '' }}>
                                                    {{ $classGroup->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label class="form-label">School Type</label>
                                        @error('class_group_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @foreach ($inputs as $input)
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="{{ $input['type'] }}" name="{{ $input['name'] }}"
                                                class="form-control @error($input['name']) is-invalid @enderror"
                                                value="{{ old($input['name']) }}"
                                                {{ $input['name'] !== 'remarks' ? 'required' : '' }}>
                                            <label class="form-label">{{ $input['label'] }}</label>
                                            @error($input['name'])
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div id="dynamic-form-container">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="mb-0 card-title flex-grow-1">Fee Structure Options</h4>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                @php
                                                    $fields = [
                                                        ['nearest_bss_school', 'Nearest BSS School', 'text'],
                                                        ['academic_year_id', 'Academic Year', 'select'],
                                                        ['fee_charges', 'BSS Fee Charges', 'number'],
                                                        ['school_fee', 'Tution Fee', 'number'],
                                                        ['admission_fee', 'Admission Fee', 'number'],
                                                        ['security_fee', 'Security Fee', 'number'],
                                                        ['registration_fee', 'Registration Fee', 'number'],
                                                        ['final_fee_charges', 'Final Tution Fee Charges', 'number'],
                                                        [
                                                            'round_final_fee_charges',
                                                            'Round Final Tution fee charges',
                                                            'number',
                                                        ],
                                                        ['approval_date', 'Created On', 'date'],
                                                        ['fee_details_remarks', 'Remarks', 'text'],
                                                    ];
                                                @endphp

                                                @foreach ($fields as [$name, $label, $type])
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="form-label-group in-border">
                                                            @if ($type === 'select' && $name === 'academic_year_id')
                                                                <select
                                                                    class="filter form-select @error($name) is-invalid @enderror"
                                                                    name="{{ $name }}" required>
                                                                    <option value="">Please select</option>
                                                                    @foreach ($academic_years as $academic_year)
                                                                        <option value="{{ $academic_year->id }}"
                                                                            {{ old($name, $academic_year->active == 1 ? $academic_year->id : '') == $academic_year->id ? 'selected' : '' }}>
                                                                            {{ $academic_year->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <input type="{{ $type }}"
                                                                    name="{{ $name }}"
                                                                    class="form-control @error($name) is-invalid @enderror"
                                                                    value="{{ old($name) }}"
                                                                    {{ $name !== 'fee_details_remarks' ? 'required' : '' }}>
                                                            @endif
                                                            <label class="form-label">{{ $label }}</label>
                                                            @error($name)
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Add New Fee Structures</button>
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
                                    <th>School Fee</th>
                                    <th>Registration Fee</th>
                                    <th>BSS Fee Charges</th>
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
                                @foreach ($savedRecords as $record)
                                    {{-- @dd($record->new_school_fee_structure) --}}
                                    {{-- @dd($record) --}}
                                    <tr>
                                        <td>{{ $record->nearest_bss_school }}</td>
                                        <td>{{ $record->academic_years->title }}</td>
                                        <td>
                                            @if (isset($record->new_school_fee_structure->class_group->name))
                                                {{ $record->new_school_fee_structure->class_group->name }}
                                            @endif
                                        </td>

                                        <td>{{ $record->registration_fee }}</td>
                                        <td>{{ $record->school_fee }}</td>
                                        <td>{{ $record->fee_charges }}</td>
                                        <td>{{ $record->security_fee }}</td>
                                        <td>{{ $record->admission_fee }}</td>
                                        <td>{{ $record->final_fee_charges }}</td>
                                        <td>{{ $record->round_final_fee_charges }}</td>
                                        <td><select data-id="{{ $record->id }}" class="form-control"
                                                name="fee_status_by_dd" id="fee_status_by_dd">
                                                <option value="Pending">Pending</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if (isset($record->new_school_fee_structure->remarks))
                                                {{ $record->new_school_fee_structure->remarks }}
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('fee_structure.edit', $record->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableRows = document.querySelectorAll(".record-row");
            tableRows.forEach(row => {
                row.style.display = "none";
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $(document).on('change', '#fee_status_by_dd', function() {
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
                        value: $("#fee_status_by_dd").val()
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
        document.addEventListener('DOMContentLoaded', function() {
            const dynamicFormContainer = document.getElementById('dynamic-form-container');
            const addFormSectionButton = document.getElementById('add-form-section');

            function createFormSection() {
                const newFormSection = document.createElement('div');
                newFormSection.className = 'card mt-4';
                newFormSection.innerHTML = `
                    <div class="card-header align-items-center d-flex">
                        <h4 class="mb-0 card-title flex-grow-1">Fee Structure Details</h4>
                        <button type="button" class="btn btn-danger ms-2" onclick="removeFormSection(this)">
                            <i class="fas fa-trash-alt"></i>
                        </button>

                    </div>
                    <div class="card-body">

                    <div class="row">
                        <!-- Existing fields -->

                        <!-- Nearest BSS School -->
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" name="nearest_bss_school[]" class="form-control"
                                    required>
                                <label class="form-label">Nearest BSS School</label>
                            </div>
                        </div>

                        <!-- Academic Year -->
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" name="academic_year_id" required>
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">
                                            {{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Academic Year</label>
                            </div>
                        </div>



                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="fee_charges[]" id="fee_charges"
                                    class="form-control" required>
                                <label class="form-label">Fee Charges</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="school_fee[]" id="school_fee"
                                    class="form-control" required>
                                <label class="form-label">Tution Fee</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="admission_fee[]" id="admission_fee"
                                    class="form-control" required>
                                <label class="form-label">Admission Fee</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="security_fee[]" id="security_fee"
                                    class="form-control" required>
                                <label class="form-label">Security Fee</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="registration_fee[]"
                                    id="registration_fee" class="form-control" required>
                                <label class="form-label">Registration Fee</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="final_fee_charges[]"
                                    id="final_fee_charges" class="form-control" required >
                                <label class="form-label">Final Tution Fee Charges</label>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="number" name="round_final_fee_charges[]"
                                    id="round_final_fee_charges" class="form-control" required>
                                <label class="form-label">Round Final Tution fee charges</label>
                            </div>
                        </div>


                        <!-- Fee Status by DD -->


                        <!-- Approval Date -->
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="date" name="approval_date[]" class="form-control"
                                    required>
                                <label class="form-label">Approval Date</label>
                            </div>

                        </div>

                        </div>
                        </div>
                        `;
                dynamicFormContainer.appendChild(newFormSection);
            }

            function removeFormSection(button) {
                const formSection = button.closest('.card');
                if (formSection) {
                    dynamicFormContainer.removeChild(formSection);
                }
            }

            addFormSectionButton.addEventListener('click', function() {
                createFormSection();
            });

            dynamicFormContainer.addEventListener('click', function(event) {
                const deleteButton = event.target.closest('.btn-danger');
                if (deleteButton) {
                    removeFormSection(deleteButton);
                }
            });


        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            // Calculate the Final Fee Charges whenever any of the relevant fields change
            $('#fee_charges, #school_fee, #admission_fee, #security_fee, #registration_fee').on('input',
                function() {
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
