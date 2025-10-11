<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Student List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <table id="studentList-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                   style="width:100%">
                <thead>
                <tr>
                    <th scope="col">Sr</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th scope="col">Sr</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#studentList-datatable').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                bLengthChange: false,
                pageLength: 50,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
            });
        });
    </script>
@endpush
@push('footer_scripts')
    <script>
        $(document).on('click', '.show-progress-report-modal', function(e) {

            var target = $(this).data('target');
            var url = $(this).data('url');
            console.log('show modal', target, url);
            $.ajax({

                url: url,
                type: "GET",
                // dataType: 'html',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function(data) {
                    $('#progress-report-modal-div').html(data);
                    $(target).modal('show');
                },
                error: function() {

                },
                beforeSend: function() {

                },
                complete: function() {

                }
            });
        });
    </script>
@endpush
