@extends('layouts.master')

@section('content')
    <style>
        .custom-list-group {
            list-style: none;
            padding: 0;
            font-family: "Courier New", monospace;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #333;
            /* Darker text color */
        }

        .values {
            display: flex;
            justify-content: space-between;
            padding: 8px;
        }

        .label {
            width: 25%;
            text-align: center;
            font-weight: bold;
            color: #555;
            /* Slightly darker text color */
        }

        .value {
            width: 25%;
            text-align: center;
            font-weight: normal;
            /* Regular font weight */
            color: #000;
            /* Black text color */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="mt-4 row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 card-title">Saved Fee Structures</h4>
                    @permission('add-fee-structure')
                    <a href="{{ route('fee_structure.create') }}" class="btn btn-primary">Add New School Fee Structure</a>
                    @endpermission
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id" name="state_id" placeholder="state"
                                    data-target="city_id" data-city-url="{{ route('list-cities') }}"
                                    data-url="{{ route('filter-fee-structure') }}" aria-label="State select" required>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">State/Province</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                    id="city" name="city_id" aria-label="City select" required>
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
                                <div class="invalid-tooltip">
                                    @if ($errors->has('city_id'))
                                        {{ $errors->first('city_id') }}
                                    @else
                                        City is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive fee-structure-table-wrapper">
                            @include('fee_structure.partials.feestructure_table')
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @push('header_scripts')
        @endpush
        @push('footer_scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Attach state change event
                    $('#state_id').change(function() {
                        updateTable($(this));
                    });

                    // Attach city change event
                    $('#city').change(function() {
                        updateTable(false);
                    });

                    $('#academic_year_id').change(function() {
                        updateTable(false);
                    });

                    // Function to update the DataTable with filtered data
                    function updateTable(thiss) {
                        const stateId = $('#state_id').val();
                        const cityId = $('#city').val();
                        const academicYear = $('#academic_year_id').val();

                        if(thiss){
                            const url = thiss.data('city-url');
                            const target = thiss.data('target');
                            const target_name = target?.split('_')[0];

                            $.ajax({
                                url: url + '?id=' + thiss.val(),
                                type: "GET",
                                cache: false,
                                success: function(data) {
                                    console.log('here after the city');
                                    var options = `<option value="">Please select a ${target_name}</option>`;
                                    $.each(data, function(index, value) {
                                        console.log('in the last else');
                                        options +=
                                            '<option value="' + value.id + '">' + value[
                                                `${target_name}_name`] + '</option>';
                                    });
                                    $('select[name="' + target + '"]').html(options).attr('disabled', false);
                                }
                            });
                        }

                        $.ajax({
                            url: '{{ route('filter-fee-structure') }}',
                            type: 'GET',
                            data: {
                                state_id: stateId,
                                city_id: cityId,
                                academic_year_id: academicYear,
                            },
                            success: function(data) {
                                $('.fee-structure-table-wrapper').html(data);
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.error('Error fetching data:', errorThrown);
                            }
                        });
                    }
                });
            </script>
        @endpush
