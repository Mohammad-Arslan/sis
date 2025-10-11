@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                   <h1>Royalty Reimbursement</h1>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#franchiseRoyaltyReport').DataTable({
                retrieve: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                columns: [{
                        data: 'br_name',
                        name: 'br_name'
                    },
                    {
                        data: 'students_strength',
                        name: 'students_strength'
                    },
                    {
                        data: 'total_fees',
                        name: 'total_fees'
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    {
                        data: 'total_royalty',
                        name: 'total_royalty'
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });
    </script>
@endpush
