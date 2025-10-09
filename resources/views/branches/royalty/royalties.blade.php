<table id="royalty-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
    <thead>
    <tr>
        {{-- <th>Branch</th>
        <th>NWA</th> --}}
        <th>Rate (%)</th>
        {{--<th>Sales Tax (%)</th>
        <th>FED (%)</th>--}}
        <th>WEF Date</th>
        <th>Close Date</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
    <tr>
        {{-- <th>Branch</th>
        <th>NWA</th> --}}
        <th>Rate (%)</th>
        {{--<th>Sales Tax (%)</th>
        <th>FED (%)</th>--}}
        <th>WEF Date</th>
        <th>Close Date</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </tfoot>
</table>

@push('footer_scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#royalty-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('branch-royalty.index', $route_parameters) }}",
                columns: [
                    {
                        data: 'royalty_rate',
                        name: 'royalty_rate'
                    },
                    {
                        data: 'with_effect_from',
                        name: 'with_effect_from'
                    },
                    {
                        data: 'closing_date',
                        name: 'closing_date'
                    },
                    {
                        data: 'updated_by',
                        name: 'updated_by'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        });
    </script>
@endpush
