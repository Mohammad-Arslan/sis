@extends('layouts.master')

@push('header_scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js">
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title">Dimensions Criteria</h5>
                <a href="{{ route('answer-dimensions.create') }}" class="btn btn-success-new">Add Dimensions Criteria</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="filterDimension">Filter by Dimension:</label>
                <select class="form-control" id="filterDimension">
                    <option value="">All Dimensions</option>
                    @php
                        $uniqueDimensions = [];
                    @endphp
                    @foreach ($answerDimensions as $answerDimension)
                        @if ($answerDimension->questionDimension && !in_array($answerDimension->questionDimension->title, $uniqueDimensions))
                            <option value="{{ $answerDimension->questionDimension->title }}">
                                {{ $answerDimension->questionDimension->title }}
                            </option>
                            @php
                                $uniqueDimensions[] = $answerDimension->questionDimension->title;
                            @endphp
                        @endif
                    @endforeach

                </select>
            </div>
        </div>


        @if ($answerDimensions->count() > 0)
            <div class="table-responsive" style="margin-top: 10px">
                <table id="answerDimensionsTable" class="table mt-3">
                    <thead>
                        <tr class="col-12" style="background-color: white">
                            <th class="col-3">Dimensions</th>
                            <th class="col-7">Criteria</th>
                            <th class="col-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answerDimensions as $answerDimension)
                            <tr>
                                <td>
                                    @if ($answerDimension->questionDimension)
                                        {{ $answerDimension->questionDimension->title }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $answerDimension->title }}</td>
                                <td>
                                    <a style="font-size: 10px;"
                                        href="{{ route('answer-dimensions.show', $answerDimension) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a style="font-size: 10px;"
                                        href="{{ route('answer-dimensions.edit', $answerDimension) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-id="{{ $answerDimension->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No answer dimensions found.</p>
        @endif
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            var table = $('#answerDimensionsTable').DataTable();

            // Add event listener to the filter dropdown
            $('#filterDimension').on('change', function() {
                var selectedDimension = $(this).val().toUpperCase();
                table.search(selectedDimension).draw();
            });

            
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create a form to submit the delete request
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '/answer-dimensions/' + id
                        });

                        // Add CSRF token
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        }));

                        // Add method override for DELETE
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        }));

                        // Append form to body and submit
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
