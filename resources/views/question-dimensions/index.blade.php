@extends('layouts.master')

@push('header_scripts')
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title">Dimensions</h5>
                <div class="d-flex">
                    <a href="{{ route('question-dimensions.create') }}" class="btn btn-success-new me-2">
                        Add Dimensions
                    </a>
                    <a href="{{ route('answer-dimensions.index') }}" class="btn btn-success-new">
                        Add Criteria
                    </a>
                </div>
            </div>
        </div>

        @if ($questionDimensions->count() > 0)
            <div class="card mt-3">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Dimension</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questionDimensions as $questionDimension)
                            <tr>
                                <td class="text-wrap">{{ $questionDimension->title }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('question-dimensions.show', $questionDimension) }}"
                                            class="btn btn-info btn-sm me-1">View</a>
                                        <a href="{{ route('question-dimensions.edit', $questionDimension) }}"
                                            class="btn btn-primary btn-sm me-1">Edit</a>
                                        <form action="{{ route('question-dimensions.destroy', $questionDimension) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this question dimension?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No question dimensions found.</p>
        @endif
    </div>
@endsection

@push('footer_scripts')
    <<<<<<< HEAD <style>
        .table td {
        vertical-align: middle;
        /* Aligns text/buttons vertically in the middle */
        }

        .text-wrap {
        white-space: normal !important;
        /* Allows wrapping of long text */
        word-break: break-word;
        /* Breaks long strings without spaces */
        }
        </style>
        =======
        <script>
            $(document).ready(function() {
                // Delete confirmation with SweetAlert
                $(document).on('click', '.delete-btn', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var url = "{{ route('question-dimensions.destroy', ':id') }}".replace(':id', id);

                    Swal.fire({
                        html: '<div class="mt-3">' +
                            '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                            '<div class="pt-2 mx-5 mt-4 fs-15">' +
                            '<h4>Are you sure?</h4>' +
                            '<p class="mx-4 mb-0 text-muted">Are you sure you want to delete this question dimension?</p>' +
                            '</div>' +
                            '</div>',
                        showCancelButton: true,
                        confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                        confirmButtonText: 'Yes, Delete It!',
                        cancelButtonClass: 'btn btn-danger w-xs mb-1',
                        buttonsStyling: false,
                        showCloseButton: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // Create a form and submit it
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': url
                            });

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_token',
                                'value': '{{ csrf_token() }}'
                            }));

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_method',
                                'value': 'DELETE'
                            }));

                            $('body').append(form);
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
