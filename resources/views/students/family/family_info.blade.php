<div id='guardianAlert' role="alert"></div>

@if(isset($family))
    <div class="row px-3">
        <div class="col-md-6 d-flex" scope="row"><p><b>Family Number:</b></p><p><span>&nbsp&nbsp {{ $family->family_no }}</span></p></div>
        <div class="col-md-6 d-flex" scope="row"><p><b>Parent Name:</b></p><p><span>&nbsp&nbsp {{ isset($family->parent) ? $family->parent->guardian_name : '-'}}</span></p></div>
        <div class="col-md-6 d-flex" scope="row"><p><b>Relation:</b></p><p><span>&nbsp&nbsp {{ isset($family->parent->relation) ? $family->parent->relation->relation_name: '-' }}</span></p></div>
        <div class="col-md-6 d-flex" scope="row"><p><b>CNIC:</b></p><p><span>&nbsp&nbsp {{ isset($family->parent) ? $family->parent->CNIC : '-'}}</span></p></div>
    </div>
@endif
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="siblings-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Siblings</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Siblings</th>
            </tr>
        </tfoot>
    </table>

</div>

@push('footer_scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#siblings-data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },
            ajax: "{{ route('family-info.index', [ 'student' => isset($student) ? $student->id : 0 ]) }}",
            columns: [
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'sibling_no',
                    name: 'sibling_no',
                    width: "5%"
                },
            ]
        });
    });
</script>
@endpush
