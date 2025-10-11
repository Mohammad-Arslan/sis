<div class="table-responsive">
    <table class="table table-bordered" id="ptmInfoTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>PTM Date</th>
                <th>PTM Type</th>
                <th>Discussion Summary</th>
                <th>Outcomes</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>PTM Date</th>
                <th>PTM Type</th>
                <th>Discussion Summary</th>
                <th>Outcomes</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
@push('footer_scripts')
    @php
        $student_id = isset($student) ? $student->id : (isset($student_id) ? $student_id : null);
    @endphp
    <script>
        $(document).ready(function() {
            @if(isset($student))
            $('#ptmInfoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('ptm.getPTMInfo', ['student_id' => $student_id]) }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ptm_date',
                        name: 'ptm_date'
                    },
                    {
                        data: 'ptm_type',
                        name: 'ptm_type'
                    },
                    {
                        data: 'discussion_summary',
                        name: 'discussion_summary'
                    },
                    {
                        data: 'outcomes',
                        name: 'outcomes'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            @endif
        });

        // Function to populate form fields for update
        function populateFormFields(data) {
            $('#ptm_date').val(data.ptm_date);
            $('#ptm_type').val(data.ptm_type);
            $('#discussion_summary').val(data.discussion_summary);
            $('#outcomes').val(data.outcomes);
            $('#notes').val(data.notes);
            $('#ptm_id').val(data.id); // Hidden field for ID
        }

        $('#ptmInfoTable').on('click', '.edit', function() {
            const id = $(this).data('id');
            $.ajax({
                url: "{{ route('ptm.edit', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Populate form fields with response data
                    populateFormFields(response);
                    // Show the form
                    $('#ptmInfoForm').show();
                }
            });
        });

        $('#ptmInfoTable').on('click', '.delete', function() {
            const id = $(this).data('id');
            const deleteUrl = "{{ route('ptm.destroy', ['id' => ':id']) }}".replace(':id', id);
            
            Swal.fire({
                html: '<div class="mt-3">' +
                    '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                    '<div class="pt-2 mx-5 mt-4 fs-15">' +
                    '<h4>Are you sure?</h4>' +
                    '<p class="mx-4 mb-0 text-muted">Are you Sure You want to Delete this PTM Record ?</p>' +
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
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#ptmInfoTable').DataTable().ajax.reload(null, false);
                            
                            // Show success message
                            Swal.fire({
                                html: '<div class="mt-3">' +
                                    '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                    '<div class="mt-4 pt-2 fs-15">' +
                                    '<h4>Success !</h4>' +
                                    '<p class="text-muted mx-4 mb-0">' + (response.message || 'PTM Record has been successfully deleted.') + '</p>' +
                                    '</div></div>',
                                showCancelButton: !0,
                                showConfirmButton: !1,
                                cancelButtonClass: "btn btn-primary w-xs mb-1",
                                cancelButtonText: "Okay",
                                buttonsStyling: !1,
                                showCloseButton: !0
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                html: '<div class="mt-3">' +
                                    '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                    '<div class="mt-4 pt-2 fs-15">' +
                                    '<h4>Error !</h4>' +
                                    '<p class="text-muted mx-4 mb-0">An error occurred while deleting the PTM record.</p>' +
                                    '</div></div>',
                                showCancelButton: !0,
                                showConfirmButton: !1,
                                cancelButtonClass: "btn btn-primary w-xs mb-1",
                                cancelButtonText: "Okay",
                                buttonsStyling: !1,
                                showCloseButton: !0
                            });
                        }
                    });
                }
            });
        });

        $('#ptm_cancel_edit').on('click', function() {
            $('#ptm_id').val('');
            $('#ptm_date').val('');
            $('#ptm_type').val('');
            $('#discussion_summary').val('');
            $('#outcomes').val('');
            $('#notes').val('');
        });
    </script>
@endpush
